from vvecon.rest_api.socket import SocketParser
from vvecon.rest_api.utils import Parser, ReqParser
from vvecon.rest_api.utils.Types import str__, int__


class LecturerParser(Parser, SocketParser):
    """
    All User parsers is being stored here
    """
    boolean = [1, 2]  # 1 - false, 2 - true

    login = ReqParser()
    login.add_arg("email", str__)
    login.add_arg("password", str__)

    signup = ReqParser()
    signup.add_arg("user_name", str__)
    signup.add_arg("email", str__)
    signup.add_arg("password", str__)

    lecturers = ReqParser()

    batch = ReqParser()
    batch.add_arg("batch", str__)

    batches = ReqParser()

    lecture = ReqParser()
    lecture.add_arg("lecture", str__)
    lecture.add_arg("lecturer", int__)
    lecture.add_arg("batch", int__)
    lecture.add_arg("date", str__)
    lecture.add_arg("start", str__)
    lecture.add_arg("end", str__)

    lectures = ReqParser()
    lectures.add_arg("lecturer", int__)

    attendance = ReqParser()
    attendance.add_arg("lecture", int__)
