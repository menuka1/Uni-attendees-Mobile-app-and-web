package com.vvecon.present.Core;

import android.util.Log;

import androidx.appcompat.app.AlertDialog;
import androidx.appcompat.app.AppCompatActivity;

import com.google.gson.Gson;
import com.google.gson.JsonObject;
import com.google.gson.JsonSyntaxException;

import java.io.IOException;
import java.util.HashMap;
import java.util.concurrent.Executor;
import java.util.concurrent.Executors;

import okhttp3.MediaType;
import okhttp3.OkHttpClient;
import okhttp3.Request;
import okhttp3.RequestBody;
import okhttp3.Response;
import okhttp3.ResponseBody;

public class Model {
    public String API_URL;
    public String HOST;
    public String API_KEY;
    public final Executor executor;
    public final AppCompatActivity parent;
    private final Gson gson;

    public Model(AppCompatActivity this_) {
        executor = Executors.newSingleThreadExecutor();
        gson = new Gson();
        parent = this_;
    }

    private JsonObject parseResponse(Integer statusCode, String response, String errorMessage) throws Exception {
        if (response != null) {
            Log.d("RESPONSE", response);
            try {
                if (statusCode == 201) {
                    return gson.fromJson(response, JsonObject.class);
                } else if (statusCode == 404) {
                    Log.e("ERROR MESSAGE:", response);
                    JsonObject message = gson.fromJson(response, JsonObject.class);
                    if (message.has("message"))
                    {
                        String error = message.get("message").getAsString();
                        if (error.startsWith("SYSTEM:"))
                        {
                            errorMessage = error.replaceFirst("^SYSTEM:", "");
                        } else {
                            errorMessage = error;
                        }
                    }
                    throw new Exception(errorMessage);
                } else {
                    Log.e("RESPONSE STATUS CODE:", String.valueOf(statusCode));
                    throw new Exception("Something went wrong, invalid request!");
                }
            } catch (JsonSyntaxException e) {
                e.printStackTrace();
                Log.e("PARSE RESPONSE ERROR:", e.toString());
                throw new Exception("Something went wrong, " + errorMessage);
            }
        } else {
            Log.e("NULL RESPONSE:", "Null Response");
            throw new Exception("Something went wrong, invalid result.");
        }
    }

    public void callAPI(String action, HashMap<String, Object> data, Receiver listener, String errorTitle, String errorMessage, Boolean showErrors) {
        executor.execute(() -> {
            data.put("host", HOST);
            data.put("api_key", API_KEY);

            OkHttpClient client = new OkHttpClient();
            Gson gson = new Gson();
            RequestBody requestBody = RequestBody.create(MediaType.parse("application/json"), gson.toJson(data));
            Request request = new Request.Builder()
                    .url(API_URL + action)
                    .post(requestBody)
                    .build();
            try {
                Response response = client.newCall(request).execute();
                Integer statusCode = response.code();
                ResponseBody responseBody = response.body();
                if (responseBody != null) {
                    String responseData = responseBody.string();
                    JsonObject api_report = parseResponse(statusCode, responseData, errorMessage);
                    if (listener != null) {
                        listener.on(api_report);
                    }
                } else {
                    throw new Exception("Invalid response.");
                }
            } catch (IOException e) {
                e.printStackTrace();
                if (showErrors) { showError(errorTitle, errorMessage);}
                if (listener != null) {
                    listener.on(null);
                }
            } catch (Exception e){
                e.printStackTrace();
                if (showErrors) { showError(errorTitle, e.getMessage()); }
                if (listener != null) {
                    listener.on(null);
                }
            }
        });
    }

    public interface Receiver {
        void on(JsonObject layoutLangData);
    }

    private void showError(String errorTitle, String errorMessage)
    {
        if (parent != null && !parent.isFinishing()) {
            parent.runOnUiThread(() -> {
                AlertDialog.Builder builder = new AlertDialog.Builder(parent);
                builder.setTitle(errorTitle);
                builder.setMessage(errorMessage);
                builder.setPositiveButton("OK", (dialog, which) -> {});
                builder.show();
            });
        }
    }
}
