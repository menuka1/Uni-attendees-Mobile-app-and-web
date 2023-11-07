package com.vvecon.present.Controller;

import androidx.appcompat.app.AppCompatActivity;

import com.vvecon.present.Core.Controller;
import com.vvecon.present.Model.UserModel;

public class UserController extends Controller {
    private final UserModel model;
    public UserController(AppCompatActivity this_)
    {
        parent = this_;
        model = new UserModel(parent);
    }

    public void login(String email, String password, Handler listener)
    {
        model.login(email, password, api_report -> {
            if (listener != null) {
                listener.on(api_report);
            }
        });
    }

    public void signup(String userName, String email, String studentId, Integer batch, String password, Handler listener)
    {
        model.signup(userName, email, studentId, batch, password, api_report -> {
            if (listener != null) {
                listener.on(api_report);
            }
        });
    }

    public void batches(Handler listener) {
        model.batches(api_report -> {
            if (listener != null) {
                listener.on(api_report);
            }
        });
    }

    public void lectures(Integer student, Handler listener) {
        model.lectures(student, api_report -> {
           if (listener != null) {
               listener.on(api_report);
           }
        });
    }

    public void attend(Integer lecture, Integer student, String latitude, String longitude, Handler listener) {
        model.attend(lecture, student, latitude, longitude, api_report -> {
            if (listener != null) {
                listener.on(api_report);
            }
        });
    }
}
