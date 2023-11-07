package com.vvecon.present.Model;

import androidx.appcompat.app.AppCompatActivity;

import com.vvecon.present.Core.Model;

import java.util.HashMap;

public class UserModel extends Model {
    public UserModel(AppCompatActivity this_) {
        super(this_);
        API_URL = "https://presentapi.vvecon.com/";
        HOST = "user@presentapi.vvecon.com";
        API_KEY = "mNkgCO77A3Rajm0PsXy7DyJzDrnF9fwGPQKB+PHdLdL9dyvw4V1KIwgBdvq8ylZ2nnA64ZbDrASuH5ZF6Febc+0lpZYyMV";
    }

    public void login(String email, String password, Model.Receiver listener)
    {
        HashMap<String, Object> data = new HashMap<>();
        data.put("email", email);
        data.put("password", password);
        callAPI("login", data, listener, "Login failed", "Failed to log in.", true);
    }

    public void signup(String userName, String email, String studentId, Integer batch, String password, Model.Receiver listener)
    {
        HashMap<String, Object> data = new HashMap<>();
        data.put("user_name", userName);
        data.put("email", email);
        data.put("student_id", studentId);
        data.put("batch", batch);
        data.put("password", password);
        callAPI("signup", data, listener, "Signup failed", "Failed to sign up.", true);
    }

    public void batches(Model.Receiver listener) {
        callAPI("batches", new HashMap<>(), listener, "Request Failed", "Failed to get batches.", false);
    }

    public void lectures(Integer student, Model.Receiver listener) {
        HashMap<String, Object> data = new HashMap<>();
        data.put("student", student);
        callAPI("lectures", data, listener, "Request Failed", "Failed to get lectures", false);
    }

    public void attend(Integer lecture, Integer student, String latitude, String longitude, Model.Receiver listener) {
        HashMap<String, Object> data = new HashMap<>();
        data.put("lecture", lecture);
        data.put("student", student);
        data.put("latitude", latitude);
        data.put("longitude", longitude);
        callAPI("attend", data, listener, "Marking Failed", "Failed to mark attendance.", true);
    }
}
