from os import environ

from dotenv import load_dotenv
from flask_socketio import SocketIO
from vvecon.rest_api.connections import get_connection
from vvecon.rest_api.utils import Router
from vvecon.rest_api.utils.Abort import *

from Controller.LecturerController import LecturerController
from Controller.UserController import UserController
from Parsers.LecturerParser import LecturerParser
from Parsers.UserParser import UserParser

sock = SocketIO()

__all__ = ['User', 'Lecturer', 'sock']


# load ENV
load_dotenv()

# initialize database connection
server_name = environ.get("SERVER")
database = environ.get("DATABASE")
user_name = environ.get("USER_NAME")
password = environ.get("PASSWORD")
db = get_connection(server_name, database, user_name, password, method="single")


# -- User Router -- #
class User(Router):
    USER = environ.get("USER")

    # all the hosts which has access to the resource of Admin
    API_HOSTS = [
        USER
    ]

    # api keys by their host
    API_KEYS = {
        USER: environ.get("USER_API_KEY"),
    }

    # pre created privileges attribute
    PRIVILEGES = {
        USER: {
            "login": default_abort,
            "signup": default_abort,
            "batches": default_abort,
            "lectures": default_abort,
            "attend": default_abort
        },
    }

    # Initializing parser
    ARGS_PARSER = UserParser()

    # initialize controller
    CONTROLLER = UserController(db)


class Lecturer(Router):
    LECTURER = environ.get("LECTURER")

    API_HOSTS = [
        LECTURER
    ]

    API_KEYS = {
        LECTURER: environ.get("LECTURER_API_KEY")
    }

    PRIVILEGES = {
        LECTURER: {
            "login": default_abort,
            "signup": default_abort,
            "lecturers": default_abort,
            "batch": default_abort,
            "batches": default_abort,
            "lecture": default_abort,
            "lectures": default_abort,
            "attendance": default_abort
        }
    }

    ARGS_PARSER = LecturerParser()

    CONTROLLER = LecturerController(db)
