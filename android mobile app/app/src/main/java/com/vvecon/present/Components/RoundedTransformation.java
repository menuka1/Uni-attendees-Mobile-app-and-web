package com.vvecon.present.Components;

import android.graphics.Bitmap;
import android.graphics.Canvas;

import androidx.appcompat.app.AppCompatActivity;
import androidx.core.graphics.drawable.RoundedBitmapDrawable;
import androidx.core.graphics.drawable.RoundedBitmapDrawableFactory;

import com.squareup.picasso.Transformation;

public class RoundedTransformation implements Transformation {

    private final int radius;
    private final AppCompatActivity context;

    public RoundedTransformation(AppCompatActivity context, int radius) {
        this.context = context;
        this.radius = radius;
    }

    @Override
    public Bitmap transform(Bitmap source) {
        RoundedBitmapDrawable roundedDrawable = RoundedBitmapDrawableFactory.create(context.getResources(), source);
        roundedDrawable.setCornerRadius(radius);

        Bitmap result = Bitmap.createBitmap(source.getWidth(), source.getHeight(), source.getConfig());
        Canvas canvas = new Canvas(result);
        roundedDrawable.setBounds(0, 0, canvas.getWidth(), canvas.getHeight());
        roundedDrawable.draw(canvas);

        source.recycle();
        return result;
    }

    @Override
    public String key() {
        return "rounded(radius=" + radius + ")";
    }
}

