# -- GLOBAL VARS -- #
import base64
import ftplib
import io
import pytz
from vvecon.rest_api.utils.Controller import cover, Controller
from vvecon.rest_api.utils.Types import CURSOR, ANY, OBJ_, NONE, CON
from Model.LecturerModel import LecturerModel

TIME_ZONE = pytz.timezone('America/New_York')


# -- Lecturer Controller -- #
class LecturerController(Controller):
    FTP_URL = 'https://elit-x.co'
    FTP_BASE = '/public/uploads/'
    FTP_HOST = '66.29.146.94'
    FTP_USER = 'admin@elit-x.co'
    FTP_PASSWORD = 'PMss@123'

    def __init__(self, db: OBJ_) -> NONE:
        # initialize database connections
        self.db = db
        # initialize user model
        self.lecturer_model = LecturerModel()

    def upload_file(self, data, file, path):
        if not hasattr(self, "FTP_HOST") or self.__class__.FTP_HOST is None or not hasattr(self, "FTP_USER") \
                or self.__class__.FTP_USER is None or not hasattr(self, "FTP_PASSWORD") \
                or self.__class__.FTP_PASSWORD is None or not hasattr(self, "FTP_URL") \
                or self.__class__.FTP_URL is None or not hasattr(self, "FTP_BASE") or self.__class__.FTP_BASE is None:
            return self.failed(file, "SYSTEM: ftp is disabled, initialize ftp configuration.")
        ftp_host = self.__class__.FTP_HOST
        ftp_user = self.__class__.FTP_USER
        ftp_passwd = self.__class__.FTP_PASSWORD
        ftp = ftplib.FTP(ftp_host)
        ftp.login(user=ftp_user, passwd=ftp_passwd)
        ftp.cwd(self.__class__.FTP_BASE + path)
        ftp.storbinary(f'STOR {file}', io.BytesIO(data))
        ftp.quit()
        return self.__class__.FTP_URL + self.__class__.FTP_BASE + path + file

    @staticmethod
    def is_base64_image(encoded_data):
        try:
            decoded_data = base64.b64decode(encoded_data)
            io.BytesIO(decoded_data)
            return True
        except (ValueError, TypeError, IOError, IndexError, base64.binascii.Error):
            return False

    @staticmethod
    def byte_image(b64):
        base64_image_bytes = b64.encode()
        image_data = base64.b64decode(base64_image_bytes)
        image_file = io.BytesIO(image_data)
        image_bytes = image_file.getvalue()
        return image_bytes

    @cover("failed to login")
    def login(self, email: str, password: str, cursor: CURSOR) -> ANY:
        return self.lecturer_model.login(cursor, email, password)

    @cover("failed to signup")
    def signup(self, user_name: str, email: str, password: str, cursor: CURSOR, con: CON) -> ANY:
        return self.lecturer_model.signup(cursor, con, user_name, email, password)

    @cover("failed to get lecturers")
    def lecturers(self, cursor: CURSOR) -> ANY:
        return self.lecturer_model.lecturers(cursor)

    @cover("failed to add a new batch")
    def batch(self, batch: str, cursor: CURSOR, con: CON) -> ANY:
        return self.lecturer_model.batch(cursor, con, batch)

    @cover("failed to get batches")
    def batches(self, cursor: CURSOR) -> ANY:
        return self.lecturer_model.batches(cursor)

    @cover("failed to add a new lecture")
    def lecture(self, lecture: str, lecturer: int, batch: int, date: str, start: str, end: str, cursor: CURSOR,
                con: CON) -> ANY:
        return self.lecturer_model.lecture(cursor, con, lecture, lecturer, batch, date, start, end)

    @cover("failed to get lectures")
    def lectures(self, lecturer: int, cursor: CURSOR) -> ANY:
        return self.lecturer_model.lectures(cursor, lecturer)

    @cover("failed to get attendance")
    def attendance(self, lecture: int, cursor: CURSOR) -> ANY:
        return self.lecturer_model.attendance(cursor, lecture)
