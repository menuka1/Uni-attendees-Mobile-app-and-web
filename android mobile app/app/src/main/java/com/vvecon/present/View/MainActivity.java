package com.vvecon.present.View;

import android.Manifest;
import android.content.pm.PackageManager;
import android.os.Bundle;
import android.view.LayoutInflater;
import android.widget.LinearLayout;
import android.widget.TextView;

import androidx.appcompat.app.AppCompatActivity;
import androidx.core.app.ActivityCompat;

import com.google.gson.JsonArray;
import com.google.gson.JsonElement;
import com.google.gson.JsonObject;
import com.vvecon.present.Controller.UserController;
import com.vvecon.present.Core.Util;
import com.vvecon.present.R;

public class MainActivity extends AppCompatActivity {
    private Util UTIL;
    private UserController CONTROLLER;
    private JsonObject USER;
    private TextView greeting;
    private TextView name;
    private LinearLayout lecturesHolder;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);

        UTIL = new Util(this);
        CONTROLLER = new UserController(this);
        USER = UTIL.getUser();
        UTIL.set("logged-in", "false");

        greeting = findViewById(R.id.greeting);
        name = findViewById(R.id.name);
        lecturesHolder = findViewById(R.id.lectures);

        UTIL.setText(greeting, UTIL.getGreeting());
        UTIL.setText(name, UTIL.getAsString(USER.get("user_name")));

        if (ActivityCompat.checkSelfPermission(this, Manifest.permission.ACCESS_FINE_LOCATION) != PackageManager.PERMISSION_GRANTED && ActivityCompat.checkSelfPermission(this, Manifest.permission.ACCESS_COARSE_LOCATION) != PackageManager.PERMISSION_GRANTED) {
            ActivityCompat.requestPermissions(this, new String[]{Manifest.permission.ACCESS_FINE_LOCATION, Manifest.permission.ACCESS_COARSE_LOCATION}, 123);
        }
    }

    @Override
    public void onResume() {
        super.onResume();

        USER = UTIL.getUser();

        if (!UTIL.loggedIn()) {
            new Thread(() -> CONTROLLER.login(UTIL.getAsString(USER.get("email")), UTIL.getAsString(USER.get("password")), this::onLogin)).start();
        }

        UTIL.setText(greeting, UTIL.getGreeting());
        UTIL.setText(name, UTIL.getAsString(USER.get("user_name")));

        refreshLectures();
    }

    @Override
    public void onBackPressed() {
        moveTaskToBack(true);
        if (false) {
            super.onBackPressed();
        }
    }

    private void refreshLectures() {
        new Thread(() -> CONTROLLER.lectures(UTIL.getAsInteger(USER.get("id")), this::onLectures)).start();
    }

    private void updateLectures() {
        runOnUiThread(() -> {
            JsonArray lectures = UTIL.getList("lectures");
            if (lectures != null) {
                lecturesHolder.removeAllViews();
                for (JsonElement lectureElement : lectures) {
                    final JsonObject lecture = lectureElement.getAsJsonObject();

                    final TextView lectureContainer = (TextView) LayoutInflater.from(this).inflate(R.layout.lecture, lecturesHolder, false);
                    UTIL.setText(lectureContainer, UTIL.getAsString(lecture.get("lecture")) + "\n" + UTIL.getAsString(lecture.get("from")) + " - " + UTIL.getAsString(lecture.get("to")));

                    lectureContainer.setOnClickListener(v -> toLecture(lecture));

                    lecturesHolder.addView(lectureContainer);
                }
            }
        });
    }

    private void onLectures(JsonObject api_report) {
        JsonArray result = UTIL.parseArray(api_report);
        if (result != null) {
            UTIL.setList("lectures", result);
            updateLectures();
        }
    }

    private void toLecture(JsonObject lecture) {
        JsonObject process = new JsonObject();
        process.addProperty("process", "lecture");
        process.addProperty("status", "attend");
        process.add("lecture", lecture);
        UTIL.setProcess(process);
        UTIL.changeWindow(LectureActivity.class);
    }

    private void onLogin(JsonObject api_report) {
        try {
            JsonObject result = UTIL.parse(api_report);
            if (result != null && !result.has("result")) {
                UTIL.setDict("USER", result);
                UTIL.set("logged-in", "true");
            } else {
                runOnUiThread(() -> {
                    UTIL.logout();
                    UTIL.changeWindow(LoginActivity.class);
                });
            }
        } catch (Exception ignored) {}
    }
}
