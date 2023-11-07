from vvecon.rest_api import App
from Resources import User, Lecturer, sock
# REARRANGE : import eventlet

# initializing API
app = App(__name__, static_url_path="/public", static_folder="Public")
sock.init_app(app)  # REARRANGE : sock.init_app(app, async_mode='eventlet')


# add User class to API resources
app.add_resource(User, "/")
app.add_resource(Lecturer, "/lecturer/")

if __name__ == "__main__":
    # run the server
    sock.run(app, debug=True, port=5013, allow_unsafe_werkzeug=True)
    # REARRANGE : eventlet.wsgi.server(eventlet.listen(("0.0.0.0", 8000)), sock)
