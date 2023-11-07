package com.vvecon.present.View;

import android.Manifest;
import android.app.AlertDialog;
import android.content.pm.PackageManager;
import android.os.Bundle;
import android.widget.Button;
import android.widget.TextView;

import androidx.appcompat.app.AppCompatActivity;
import androidx.core.app.ActivityCompat;

import com.google.android.gms.location.FusedLocationProviderClient;
import com.google.android.gms.location.LocationServices;
import com.google.gson.JsonObject;
import com.vvecon.present.Controller.UserController;
import com.vvecon.present.Core.Util;
import com.vvecon.present.R;

public class LectureActivity extends AppCompatActivity {
    private Util UTIL;
    private UserController CONTROLLER;
    private JsonObject USER;
    private Button attend;
    private JsonObject lecture;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_lecture);

        UTIL = new Util(this);
        CONTROLLER = new UserController(this);
        USER = UTIL.getUser();

        TextView greeting = findViewById(R.id.greeting);
        TextView name = findViewById(R.id.name);
        attend = findViewById(R.id.attend);

        UTIL.setText(greeting, UTIL.getGreeting());
        UTIL.setText(name, UTIL.getAsString(USER.get("user_name")));
        attend.setEnabled(false);

        validateProcess();
        attend.setOnClickListener(v -> attend());

        if (ActivityCompat.checkSelfPermission(this, Manifest.permission.ACCESS_FINE_LOCATION) != PackageManager.PERMISSION_GRANTED && ActivityCompat.checkSelfPermission(this, Manifest.permission.ACCESS_COARSE_LOCATION) != PackageManager.PERMISSION_GRANTED) {
            ActivityCompat.requestPermissions(this, new String[]{Manifest.permission.ACCESS_FINE_LOCATION, Manifest.permission.ACCESS_COARSE_LOCATION}, 123);
        }
    }

    private void validateProcess() {
        JsonObject process = UTIL.getProcess();
        if (process != null && !process.isEmpty()) {
            if (UTIL.equals(UTIL.getAsString(process.get("process")), "lecture") && UTIL.equals(UTIL.getAsString(process.get("status")), "attend")) {
                lecture = process.get("lecture").getAsJsonObject();
                onValidateProcess();
                return;
            }
        }
        onBackPressed();
    }

    private void onValidateProcess() {
        runOnUiThread(() -> {
            TextView lectureName = findViewById(R.id.lecture);
            TextView time = findViewById(R.id.time);

            UTIL.setText(lectureName, UTIL.getAsString(lecture.get("lecture")));
            UTIL.setText(time, UTIL.getAsString(lecture.get("from")) + " - " + UTIL.getAsString(lecture.get("to")));
            attend.setEnabled(true);
        });
    }

    private void attend() {
        AlertDialog.Builder builder = new AlertDialog.Builder(LectureActivity.this);
        builder.setTitle("Confirmation");
        builder.setMessage("Do you want to mark your attendance for this lecture?");

        builder.setPositiveButton("OK", (dialog, which) -> {
            dialog.dismiss();
            markAttendance();
        });

        builder.setNegativeButton("Cancel", (dialog, which) -> {
            dialog.dismiss();
        });

        AlertDialog dialog = builder.create();
        dialog.show();
    }

    private void markAttendance() {
        if (ActivityCompat.checkSelfPermission(this, Manifest.permission.ACCESS_FINE_LOCATION) != PackageManager.PERMISSION_GRANTED && ActivityCompat.checkSelfPermission(this, Manifest.permission.ACCESS_COARSE_LOCATION) != PackageManager.PERMISSION_GRANTED) {
            ActivityCompat.requestPermissions(this, new String[]{Manifest.permission.ACCESS_FINE_LOCATION, Manifest.permission.ACCESS_COARSE_LOCATION}, 123);
            UTIL.toast("Click Again to Mark Attendance!");
        } else {
            FusedLocationProviderClient fusedLocationClient = LocationServices.getFusedLocationProviderClient(this);
            fusedLocationClient.getLastLocation().addOnSuccessListener(location -> {
                if (location != null) {
                    double latitudeValue = location.getLatitude();
                    double longitudeValue = location.getLongitude();

                    String latitude = Double.toString(latitudeValue);
                    String longitude = Double.toString(longitudeValue);

                    CONTROLLER.attend(UTIL.getAsInteger(lecture.get("id")), UTIL.getAsInteger(USER.get("id")), latitude, longitude, this::onAttend);
                } else {
                    UTIL.toast("Failed to Mark Attendance!");
                }
            }).addOnFailureListener((e) -> UTIL.toast("Failed to Mark Attendance!")).addOnCanceledListener(() -> UTIL.toast("Failed to Mark Attendance!"));
        }

    }

    private void onAttend(JsonObject api_report) {
        JsonObject result = UTIL.parse(api_report);
        runOnUiThread(() -> {
            if (result != null && result.get("result").getAsBoolean()) {
                UTIL.toast("Attendance marked successfully!");
                onBackPressed();
            } else {
                UTIL.showError("Marking Failed", "Failed to Mark Attendance");
                attend.setEnabled(true);
            }
        });
    }
}
