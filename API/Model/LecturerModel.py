import pytz
from vvecon.rest_api.utils.Model import Model, cover
from vvecon.rest_api.utils.Types import CURSOR, CON, BOOL, DICT, LIST


# -- Lecture Model -- #
class LecturerModel(Model):
    TIME_ZONE = pytz.timezone('America/New_York')

    @staticmethod
    def parse(dictionary: dict, key: str):
        return dictionary[key] if key in dictionary.keys() else ""

    @cover("failed to login")
    def login(self, cursor: CURSOR, email: str, password: str) -> DICT:
        self.execute(cursor, """
        SELECT *
        FROM lecturers
        WHERE email='{email}' AND password='{password}' AND deleted_at IS NULL
        ORDER BY id DESC 
        LIMIT 1;
        """, {}, {"email": email, "password": password})
        return self.get_row_data(cursor)

    @cover("failed to signup")
    def signup(self, cursor: CURSOR, con: CON, user_name: str, email: str, password: str) -> BOOL:
        self.execute(cursor, """
        INSERT INTO
        lecturers(user_name, email, password)
        SELECT '{user_name}', '{email}', '{password}'
        WHERE NOT EXISTS(
            SELECT 1
            FROM students s
            WHERE (s.user_name='{user_name}' OR s.email='{email}') AND deleted_at IS NULL
        );
        """, {}, {"user_name": user_name, "email": email, "password": password})
        self.commit(con)
        return cursor.lastrowid > 0

    @cover("failed to get lecturers")
    def lecturers(self, cursor: CURSOR) -> LIST:
        self.execute(cursor, """
        SELECT *
        FROM lecturers
        WHERE deleted_at IS NULL
        ORDER BY id DESC;
        """)
        return self.get_data(cursor)

    @cover("failed to add a new batch")
    def batch(self, cursor: CURSOR, con: CON, batch: str) -> BOOL:
        self.execute(cursor, """
        INSERT INTO
        batches(batch)
        VALUES('{batch}')
        """, {}, {"batch": batch})
        return self.commit(con)

    @cover("failed to get batches")
    def batches(self, cursor: CURSOR) -> LIST:
        self.execute(cursor, """
        SELECT *
        FROM batches
        WHERE deleted_at IS NULL
        ORDER BY id DESC;
        """)
        return self.get_data(cursor)

    @cover("failed to add a new lecture")
    def lecture(self, cursor: CURSOR, con: CON, lecture: str, lecturer: int, batch: int, date: str, start: str,
                end: str) -> BOOL:
        self.execute(cursor, """
        INSERT INTO
        lectures(lecture, lecturer, batch, day, `from`, `to`)
        VALUES('{lecture}', '{lecturer}', '{batch}', '{date}', '{start}', '{end}') 
        """, {"lecturer": lecturer, "batch": batch, "date": date, "start": start, "end": end}, {"lecture": lecture})
        return self.commit(con)

    @cover("failed to get lectures")
    def lectures(self, cursor: CURSOR, lecturer: int) -> LIST:
        self.execute(cursor, """
        SELECT id, lecture, lecturer, batch, DATE_FORMAT(day, '%Y-%m-%d') AS date, 
        TIME_FORMAT(`from`, '%h:%i') AS start, TIME_FORMAT(`to`, '%h:%i') AS end, deleted_at
        FROM lectures
        WHERE lecturer='{lecturer}' AND deleted_at IS NULL
        ORDER BY id DESC;
        """, {"lecturer": lecturer})
        return self.get_data(cursor)

    @cover("failed to get attendance")
    def attendance(self, cursor: CURSOR, lecture: int) -> LIST:
        self.execute(cursor, """
        SELECT s.user_name, s.student_id, IF(a.id IS NOT NULL, a.latitude, 0) AS latitude, 
        IF(a.id IS NOT NULL, a.longitude, 0) AS longitude, IF(a.id IS NOT NULL, 
            IF((6371 * 2 * ASIN(SQRT(POWER(SIN(('{latitude}' - ABS(a.latitude)) * PI() / 180 / 2), 2) 
            + COS('{latitude}' * PI() / 180) * COS(a.latitude * PI() / 180) * POWER(SIN(('{longitude}' 
            - ABS(a.longitude)) * PI() / 180 / 2), 2)))) <= '{km_range}', 3, 2), 1) AS attendance
        FROM students s
        JOIN batches b ON (b.id=s.batch AND b.deleted_at IS NULL)
        JOIN lectures l ON (l.id='{lecture}' AND l.batch=b.id AND l.deleted_at IS NULL)
        LEFT JOIN attendance a ON (s.id=a.student AND l.id=a.lecture)
        WHERE s.deleted_at IS NULL
        ORDER BY s.student_id;
        """, {"lecture": lecture, "latitude": 6.8202297, "longitude": 80.0369526, "km_range": 0.5})
        return self.get_data(cursor)
