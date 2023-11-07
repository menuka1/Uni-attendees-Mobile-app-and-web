package com.vvecon.present.Core;

import android.content.Context;
import android.content.Intent;
import android.graphics.Bitmap;
import android.text.Editable;
import android.util.Base64;
import android.util.Log;
import android.view.View;
import android.view.inputmethod.InputMethodManager;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.TextView;
import android.widget.Toast;

import androidx.appcompat.app.AlertDialog;
import androidx.appcompat.app.AppCompatActivity;

import com.google.gson.Gson;
import com.google.gson.JsonArray;
import com.google.gson.JsonElement;
import com.google.gson.JsonObject;
import com.squareup.picasso.Picasso;
import com.vvecon.present.Components.RoundedTransformation;
import com.vvecon.present.Components.SpinnerItem;
import com.vvecon.present.View.LoginActivity;
import com.vvecon.present.View.MainActivity;

import java.io.ByteArrayOutputStream;
import java.lang.reflect.Type;
import java.text.ParseException;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Calendar;
import java.util.Date;
import java.util.List;

public class Util {
    private final AppCompatActivity parent;
    private final Gson gson;
    public Util(AppCompatActivity this_)
    {
        parent = this_;
        gson = new Gson();
        Global.init(parent);
    }

    public void set(String key, String value)
    {
        Global.save(value, key);
    }

    public String get(String key)
    {
        return Global.load(key);
    }

    public void setDict(String key, JsonObject value)
    {
        Global.saveJsonObject(value, key);
    }

    public JsonObject getDict(String key)
    {
        return Global.loadJsonObject(key);
    }

    public void setList(String key, JsonArray value)
    {
        Global.saveJsonArray(value, key);
    }

    public JsonArray getList(String key)
    {
        return Global.loadJsonArray(key);
    }

    public void remove(String key)
    {
        Global.remove(key);
    }

    public JsonObject parse(JsonObject api_report)
    {
        if (api_report != null && !api_report.isJsonNull()) {
            if (api_report.has("result"))
            {
                if (api_report.get("result").isJsonPrimitive()) {
                    return api_report;
                }
                return api_report.getAsJsonObject("result");
            } else {
                Log.e("API REPORT PARSE FAILED:", "Failed to locate result.");
            }
        } else {
            Log.e("API REPORT PARSE ERROR:", "Failed to retrieve data.");
        }
        return null;
    }

    public JsonArray parseArray(JsonObject api_report)
    {
        if (api_report != null && !api_report.isJsonNull()) {
            if (api_report.has("result"))
            {
                if (api_report.get("result").isJsonArray()) {
                    return api_report.get("result").getAsJsonArray();
                } else {
                    Log.e("API REPORT INVALID PARSE:", "Failed to find a JsonArray");
                }
            } else {
                Log.e("API REPORT PARSE FAILED:", "Failed to locate result.");
            }
        } else {
            Log.e("API REPORT PARSE ERROR:", "Failed to retrieve data.");
        }
        return new JsonArray();
    }


    public <T>T json(JsonObject result, Type type)
    {
        return gson.fromJson(result, type);
    }

    public String toJson(Object var)
    {
        return gson.toJson(var);
    }
    public <T>T fromJson(String string, Type type) {
        return gson.fromJson(string, type);
    }

    public String getAsString(JsonElement tmp)
    {
        if (tmp != null && !tmp.isJsonNull()) {
            return tmp.getAsString();
        }
        return null;
    }

    public Integer getAsInteger(JsonElement tmp) {
        if (tmp != null && !tmp.isJsonNull()) {
            return tmp.getAsInt();
        }
        return null;
    }

    public Double getAsDouble(JsonElement tmp) {
        if (tmp != null && !tmp.isJsonNull()) {
            return tmp.getAsDouble();
        }
        return null;
    }

    public void changeWindow(Class<?> window){
        Intent intent = new Intent(parent, window);
        parent.startActivity(intent);
        parent.overridePendingTransition(android.R.anim.slide_in_left, android.R.anim.slide_out_right);
    }

    public void changeWindow(Class<?> window, int from, int to){
        Intent intent = new Intent(parent, window);
        parent.startActivity(intent);
        parent.overridePendingTransition(from, to);
    }

    public int getIndexOf(String needle, String[] haystack)
    {
        for (int i = 0; i < haystack.length; i++) {
            if (haystack[i].equals(needle)) {
                return i;
            }
        }
        return -1;
    }

    public int getIndexOf(Integer needle, Integer[] haystack)
    {
        for (int i = 0; i < haystack.length; i++) {
            if (haystack[i].equals(needle)) {
                return i;
            }
        }
        return -1;
    }

    public Boolean inArray(String needle, String[] haystack)
    {
        for (String item : haystack)
        {
            if (item.equals(needle))
            {
                return true;
            }
        }
        return false;
    }

    public Boolean inArray(Integer needle, Integer[] haystack)
    {
        for (Integer item : haystack)
        {
            if (item.equals(needle))
            {
                return true;
            }
        }
        return false;
    }

    public void showError(String errorTitle, String errorMessage)
    {
        if (errorTitle != null && errorMessage != null) {
            parent.runOnUiThread(() -> {
                AlertDialog.Builder builder = new AlertDialog.Builder(parent);
                builder.setTitle(errorTitle);
                builder.setMessage(errorMessage);
                builder.setPositiveButton("OK", (dialog, which) -> {});
                builder.show();
            });
        }
    }

    public void setProcess(JsonObject process)
    {
        this.setDict("process", process);
    }

    public JsonObject getProcess()
    {
        return this.getDict("process");
    }

    public void removeProcess()
    {
        this.remove("process");
    }

    public Boolean equals(String item, String eq)
    {
        return item != null && item.trim().equals(eq);
    }

    public <T extends TextView> void setText(T element, String text)
    {
        if (text != null && element != null) {
            element.setText(text);
        }
    }

    public void setText(Integer id, String text) {
        TextView view = parent.findViewById(id);
        if (view != null && text != null) {
            view.setText(text);
        }
    }

    public void setHint(EditText element, String text)
    {
        if (text != null) {
            element.setHint(text);
        }
    }

    public void setImgSrc(ImageView element, String url)
    {
        if (url != null && !url.equals("")) {
            Picasso.get().load(url).into(element);
        }
    }

    public void setImgRoundSrc(ImageView element, String url, Integer width, Integer height, Integer radius)
    {
        if (url != null && !url.equals("")) {
            Picasso.get().load(url).into(element);
            Picasso.get().load(url).resize(dpToPixel(width) * 2, dpToPixel(height) * 2).transform(new RoundedTransformation(parent, dpToPixel(radius) * 2)).into(element);
        }
    }

    public <T extends TextView> void setEditText(T element, String text) {
        setText(element, text);
        element.requestFocus();
        InputMethodManager imm = (InputMethodManager) parent.getSystemService(Context.INPUT_METHOD_SERVICE);
        if (imm != null) {
            imm.showSoftInput(element, InputMethodManager.SHOW_IMPLICIT);
        }
    }

    public Boolean loggedIn() {
        return this.equals(this.get("logged-in"), "true");
    }

    public void logout() {
        this.remove("USER");
    }

    public String name(Object name)
    {
        return (name != null) ? name.toString().substring(0, 1).toUpperCase()+name.toString().substring(1).toLowerCase() : "";
    }

    public String base64Img(Bitmap bitmap)
    {
        if (bitmap != null) {
            ByteArrayOutputStream byteArrayOutputStream = new ByteArrayOutputStream();
            bitmap.compress(Bitmap.CompressFormat.JPEG, 100, byteArrayOutputStream);
            byte[] byteArray = byteArrayOutputStream.toByteArray();
            return Base64.encodeToString(byteArray, Base64.DEFAULT);
        }
        return "";
    }

    public void setVisibility(View element, Integer visibility) {
        if (element != null) {
            element.setVisibility(visibility);
        }
    }

    public String getText(int id) {
        EditText inputEdit = parent.findViewById(id);
        Editable inputText = inputEdit.getText();
        if (inputText != null) {
            return inputText.toString();
        }
        return null;
    }

    public String getGreeting() {
        int timeId = Integer.parseInt(getTimeId());
        if (timeId == 1){
            return "Good Morning";
        } else if (timeId == 2) {
            return "Good Afternoon";
        } else if (timeId == 3) {
            return "Good Evening";
        } else {
            return "Good Night";
        }
    }

    public String getTimeId()
    {
        Calendar calendar = Calendar.getInstance();
        int hour = calendar.get(Calendar.HOUR_OF_DAY);

        if (hour >= 5 && hour < 12) { // Morning (5 AM to 11:59 AM)
            return "1";
        } else if (hour >= 12 && hour < 15) { // Afternoon (12 PM to 2:59 PM)
            return "2";
        } else if (hour >= 15 && hour < 20) { // Evening (3 PM to 7:59 PM)
            return "3";
        } else { // Night (8 PM to 4:59 AM)
            return "4";
        }
    }

    public Boolean isEmpty(String text) {
        if (text == null) {
            return true;
        } else return text.equals("");
    }

    public int dpToPixel(float dpValue) {
        float scale = parent.getResources().getDisplayMetrics().density;
        return (int) (dpValue * scale + 0.5f);
    }

    public int pixelToDp(int px) {
        float scale = parent.getResources().getDisplayMetrics().density;
        return (int) ((px - 0.5f) / scale);
    }

    public void toast(String message) {
        if (parent != null) {
            parent.runOnUiThread(() -> Toast.makeText(parent, message, Toast.LENGTH_SHORT).show());
        }
    }

    public List<SpinnerItem> getSpinnerItems(ArrayList<String> items) {
        List<SpinnerItem> spinnerItems = new ArrayList<>();

        for (int i=0; i < items.size(); i++) {
            spinnerItems.add(new SpinnerItem(items.get(i), items.get(i)));
        }

        return spinnerItems;
    }

    public String date(String dateStr) {
        if (dateStr != null && dateStr != "") {
            SimpleDateFormat inputFormat = new SimpleDateFormat("E, dd MMM yyyy HH:mm:ss z");
            SimpleDateFormat outputFormat = new SimpleDateFormat("yyyy.MM.dd");
            String outputDateStr = "";
            try {
                Date date = inputFormat.parse(dateStr);
                outputDateStr = outputFormat.format(date);
                System.out.println(outputDateStr);
            } catch (ParseException e) {
                e.printStackTrace();
            }
            return outputDateStr;
        }
        return "";
    }

    public String date(String dateStr, String format) {
        if (dateStr != null && format != null && dateStr != "") {
            SimpleDateFormat inputFormat = new SimpleDateFormat("E, dd MMM yyyy HH:mm:ss z");
            SimpleDateFormat outputFormat = new SimpleDateFormat(format);
            String outputDateStr = "";
            try {
                Date date = inputFormat.parse(dateStr);
                outputDateStr = outputFormat.format(date);
                System.out.println(outputDateStr);
            } catch (ParseException e) {
                e.printStackTrace();
            }
            return outputDateStr;
        }
        return "";
    }

    public String getString(Object text) {
        if (text != null) {
            return text.toString();
        }
        return "";
    }

    public JsonObject getUser()
    {
        JsonObject USER =  this.getDict("USER");
        if (USER.isEmpty())
        {
            this.changeWindow(LoginActivity.class);
        }
        return USER;
    }

    public void checkUser()
    {
        JsonObject USER = this.getDict("USER");
        if (!USER.isEmpty()) {
            this.changeWindow(MainActivity.class);
        }
    }
}
