package com.vvecon.present.Components;

import androidx.annotation.NonNull;

public class SpinnerItem {
    private final String displayText;
    private final String value;

    public SpinnerItem(String displayText, String value) {
        this.displayText = displayText;
        this.value = value;
    }

    @NonNull
    @Override
    public String toString() {
        return displayText;
    }

    public String getValue() {
        return value;
    }
}
