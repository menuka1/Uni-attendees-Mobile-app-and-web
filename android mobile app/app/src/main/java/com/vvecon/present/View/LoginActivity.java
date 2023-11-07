package com.vvecon.present.View;

import android.os.Bundle;
import android.widget.Button;
import android.widget.TextView;

import androidx.appcompat.app.AppCompatActivity;

import com.google.gson.JsonObject;
import com.vvecon.present.Controller.UserController;
import com.vvecon.present.Core.Util;
import com.vvecon.present.R;

public class LoginActivity extends AppCompatActivity {
    private Util UTIL;
    private UserController CONTROLLER;
    private Button login;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_login);

        UTIL = new Util(this);
        CONTROLLER = new UserController(this);

        UTIL.checkUser();

        login = findViewById(R.id.login);
        TextView toSignup = findViewById(R.id.toSignup);

        login.setEnabled(true);

        login.setOnClickListener(v -> login());
        toSignup.setOnClickListener(v -> UTIL.changeWindow(SignupActivity.class));
    }

    @Override
    public void onResume() {
        super.onResume();
        login.setEnabled(true);
    }

    private void login() {
        login.setEnabled(false);

        String email = UTIL.getText(R.id.email);
        String password = UTIL.getText(R.id.password);

        if (email != null && password != null) {
            CONTROLLER.login(email, password, this::onLogin);
        }
    }

    private void onLogin(JsonObject api_report) {
        JsonObject result = UTIL.parse(api_report);
        if (result != null && !result.has("result")) {
            UTIL.setDict("USER", result);
            UTIL.set("logged-in", "true");
            UTIL.changeWindow(MainActivity.class);
        } else {
            runOnUiThread(() -> {
                login.setEnabled(true);
                UTIL.showError("Login failed", "Invalid Email Address or Password.");
            });
        }
    }
}

