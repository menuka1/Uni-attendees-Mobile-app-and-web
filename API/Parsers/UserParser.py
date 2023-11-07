from vvecon.rest_api.socket import SocketParser
from vvecon.rest_api.utils import Parser, ReqParser
from vvecon.rest_api.utils.Types import str__, int__


class UserParser(Parser, SocketParser):
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
    signup.add_arg("student_id", str__)
    signup.add_arg("batch", int__)
    signup.add_arg("password", str__)

    batches = ReqParser()

    lectures = ReqParser()
    lectures.add_arg("student", int__)

    attend = ReqParser()
    attend.add_arg("lecture", int__)
    attend.add_arg("student", int__)
    attend.add_arg("latitude", str__)
    attend.add_arg("longitude", str__)
