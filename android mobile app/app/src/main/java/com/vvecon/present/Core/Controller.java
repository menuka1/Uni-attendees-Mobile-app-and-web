package com.vvecon.present.Core;

import androidx.appcompat.app.AppCompatActivity;

import com.google.gson.JsonObject;


public class Controller {
    public AppCompatActivity parent;

    public interface Handler {
        void on(JsonObject api_report);
    }

}
