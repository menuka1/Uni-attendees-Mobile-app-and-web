package com.vvecon.present.View;

import android.os.Bundle;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.Spinner;
import android.widget.TextView;

import androidx.appcompat.app.AppCompatActivity;

import com.google.gson.JsonArray;
import com.google.gson.JsonElement;
import com.google.gson.JsonObject;
import com.vvecon.present.Components.SpinnerItem;
import com.vvecon.present.Controller.UserController;
import com.vvecon.present.Core.Util;
import com.vvecon.present.R;

import java.util.ArrayList;

public class SignupActivity extends AppCompatActivity {
    private Util UTIL;
    private UserController CONTROLLER;
    private Button signup;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_signup);

        UTIL = new Util(this);
        CONTROLLER = new UserController(this);

        UTIL.checkUser();

        signup = findViewById(R.id.signup);
        TextView toLogin = findViewById(R.id.toLogin);

        signup.setEnabled(true);

        signup.setOnClickListener(v -> signup());
        toLogin.setOnClickListener(v -> UTIL.changeWindow(LoginActivity.class));
    }

    @Override
    public void onResume() {
        super.onResume();
        signup.setEnabled(true);

        new Thread(() -> CONTROLLER.batches(this::onBatches)).start();
        updateBatches();
    }

    private void onBatches(JsonObject api_report) {
        JsonArray result = UTIL.parseArray(api_report);
        if (result != null) {
            UTIL.setList("batches", result);
            updateBatches();
        }
    }

    private void updateBatches() {
        runOnUiThread(() -> {
            JsonArray batches = UTIL.getList("batches");
            if (batches != null) {
                ArrayList<String> batchItems = new ArrayList<>();
                for (JsonElement batch : batches) {
                    batchItems.add(UTIL.getAsString(batch.getAsJsonObject().get("batch")));
                }

                ArrayAdapter<SpinnerItem> batchAdapter = new ArrayAdapter<>(this, android.R.layout.simple_spinner_item, UTIL.getSpinnerItems(batchItems));
                batchAdapter.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
                Spinner batch = findViewById(R.id.batch);
                batch.setAdapter(batchAdapter);
            }
        });
    }

    private Integer getBatchId(String batch) {
        JsonArray batches = UTIL.getList("batches");
        if (batches != null) {
            for (JsonElement batchElement : batches) {
                if (UTIL.getAsString(batchElement.getAsJsonObject().get("batch")).equalsIgnoreCase(batch)) {
                    return UTIL.getAsInteger(batchElement.getAsJsonObject().get("id"));
                }
            }
        }
        return null;
    }

    private void signup() {
        signup.setEnabled(false);

        String userName = UTIL.getText(R.id.userName);
        String email = UTIL.getText(R.id.email);
        String studentId = UTIL.getText(R.id.studentId);
        Spinner batchSelector = findViewById(R.id.batch);
        SpinnerItem batchItem = (SpinnerItem) batchSelector.getSelectedItem();
        Integer batch = getBatchId(batchItem.getValue());
        String password = UTIL.getText(R.id.password);

        if (userName != null && email != null && studentId != null && password != null) {
            CONTROLLER.signup(userName, email, studentId, batch, password, this::onSignup);
        } else {
            UTIL.showError("Invalid Password", "Passwords does not match.");
            signup.setEnabled(true);
        }
    }

    private void onSignup(JsonObject api_report) {
        JsonObject result = UTIL.parse(api_report);
        if (result != null && !result.has("result")) {
            UTIL.setDict("USER", result);
            UTIL.set("logged-in", "true");
            UTIL.changeWindow(MainActivity.class);
        } else {
            runOnUiThread(() -> {
                signup.setEnabled(true);
                UTIL.showError("Signup failed", "Failed to sign up.");
            });
        }
    }
}
