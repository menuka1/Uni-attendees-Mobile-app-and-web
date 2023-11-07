package com.vvecon.present.Core;

import android.content.Context;
import android.content.SharedPreferences;

import com.google.gson.Gson;
import com.google.gson.JsonArray;
import com.google.gson.JsonElement;
import com.google.gson.JsonObject;
import com.google.gson.JsonParser;

public class Global {
    private static final String SHARED_PREFS_KEY = "SESSION";
    private static SharedPreferences prefs;
    private static SharedPreferences.Editor editor;
    public Global(Context context)
    {
        if (prefs == null) {
            prefs = context.getSharedPreferences(SHARED_PREFS_KEY, Context.MODE_PRIVATE);
            editor = prefs.edit();
        }
    }

    public static void init(Context context) {
        if (prefs == null) {
            prefs = context.getApplicationContext().getSharedPreferences(SHARED_PREFS_KEY, Context.MODE_PRIVATE);
            editor = prefs.edit();
        }
    }

    public static void save(String string, String strKey)
    {
        editor.putString(strKey, string);
        editor.apply();
    }

    public static String load(String strKey)
    {
        return prefs.getString(strKey, "");
    }

    public static void remove(String key) {
        prefs.getString(key, "");
        editor.remove(key);
        editor.apply();
    }

    public static void saveJsonObject(JsonObject jsonObject, String jsonKey) {
        Gson gson = new Gson();
        String json = gson.toJson(jsonObject);

        editor.putString(jsonKey, json);
        editor.apply();
    }

    public static JsonObject loadJsonObject(String jsonKey) {
        String json = prefs.getString(jsonKey, "");
        JsonParser parser = new JsonParser();

        JsonElement jsonObject = parser.parse(json);
        if (jsonObject.isJsonNull()) {
            return new JsonObject();
        }

        return jsonObject.getAsJsonObject();
    }

    public static void saveJsonArray(JsonArray jsonArray, String arrayKey) {
        Gson gson = new Gson();
        String json = gson.toJson(jsonArray);

        editor.putString(arrayKey, json);
        editor.apply();
    }

    public static JsonArray loadJsonArray(String arrayKey) {
        String json = prefs.getString(arrayKey, "");
        JsonParser parser = new JsonParser();

        JsonElement jsonArray = parser.parse(json);
        if (jsonArray.isJsonNull()) {
            return new JsonArray();
        }

        return jsonArray.getAsJsonArray();
    }
}
