import pytz
from vvecon.rest_api.utils.Model import Model, cover
from vvecon.rest_api.utils.Types import CURSOR, CON, DICT, LIST, BOOL


# -- User Model -- #
class UserModel(Model):
    TIME_ZONE = pytz.timezone('America/New_York')

    @staticmethod
    def parse(dictionary: dict, key: str):
        return dictionary[key] if key in dictionary.keys() else ""

    @cover("failed to login")
    def login(self, cursor: CURSOR, email: str, password: str) -> DICT:
        self.execute(cursor, """
        SELECT *
        FROM students
        WHERE email='{email}' AND password='{password}' AND deleted_at IS NULL
        ORDER BY id DESC 
        LIMIT 1;
        """, {}, {"email": email, "password": password})
        return self.get_row_data(cursor)

    @cover("failed to signup")
    def signup(self, cursor: CURSOR, con: CON, user_name: str, email: str, student_id: str, batch: int,
               password: str) -> BOOL:
        self.execute(cursor, """
        INSERT INTO
        students(user_name, email, student_id, batch, password)
        SELECT '{user_name}', '{email}', '{student_id}', '{batch}', '{password}'
        WHERE NOT EXISTS(
            SELECT 1
            FROM students s
            WHERE (s.user_name='{user_name}' OR s.email='{email}' OR s.student_id='{student_id}') AND deleted_at IS NULL
        );
        """, {"batch": batch}, {"user_name": user_name, "email": email, "student_id": student_id, "password": password})
        self.commit(con)
        return self.login(cursor, email, password)

    @cover("failed to get batches")
    def batches(self, cursor: CURSOR) -> LIST:
        self.execute(cursor, """
        SELECT *
        FROM batches;
        """)
        return self.get_data(cursor)

    @cover("failed to get lectures")
    def lectures(self, cursor: CURSOR, student: int) -> LIST:
        self.execute(cursor, """
        SELECT l.id, l.lecture, l.lecturer, l.batch, l.day, TIME_FORMAT(l.`from`, '%h:%i') AS `from`, 
        TIME_FORMAT(l.`to`, '%h:%i') AS `to`
        FROM lectures l
        JOIN students s ON (s.batch=l.batch)
        WHERE DATE(l.day)=DATE(NOW() - INTERVAL 150 MINUTE) AND l.deleted_at IS NULL AND s.id='{student}' 
        AND NOT EXISTS(
            SELECT 1
            FROM attendance a
            WHERE a.lecture=l.id AND a.student=s.id AND a.attendance=2 AND a.deleted_at IS NULL
        )
        AND s.deleted_at IS NULL
        ORDER BY `from`;
        """, {"student": student})
        return self.get_data(cursor)

    @cover("failed to attend the lecture")
    def attend(self, cursor: CURSOR, con: CON, lecture: int, student: int, latitude: str, longitude: str) -> BOOL:
        self.execute(cursor, """
        INSERT INTO
        attendance(lecture, student, attendance, latitude, longitude)
        SELECT '{lecture}', '{student}', 2, '{latitude}', '{longitude}'
        WHERE NOT EXISTS(
            SELECT 1
            FROM attendance a
            WHERE a.lecture='{lecture}' AND a.student='{student}' AND deleted_at IS NULL
        );
        """, {"lecture": lecture, "student": student, "latitude": latitude, "longitude": longitude})
        return self.commit(con)
