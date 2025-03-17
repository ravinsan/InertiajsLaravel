<?php

namespace App\Services;

use Illuminate\Support\Str;

class SlugService
{
    public static function createSlug($model, $slugField, $title)
    {
        $slug = self::generateSlug($title);

        // Ensure slug is unique
        $originalSlug = $slug;
        $counter = 1;
        while ($model::where($slugField, $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter++;
        }

        return $slug;
    }

    private static function generateSlug($title)
    {
        // Check if the title is in English or any other language
        if (self::isEnglish($title)) {
            // Use Laravel's Str::slug for English titles
            return Str::slug($title);
        } else {
            // For Hindi and other languages, preserve the characters
            return self::preserveNonLatinCharacters($title);
        }
    }

    private static function isEnglish($text)
    {
        // Check if the text contains only English characters
        return preg_match('/^[\x00-\x7F]*$/', $text);
    }

    private static function preserveNonLatinCharacters($text)
    {
        // Convert spaces to hyphens and remove any unwanted characters
        $text = preg_replace('/\s+/u', '-', $text);
        $text = preg_replace('/[^\p{L}\p{N}\-]/u', '', $text); // Allow letters, numbers, and hyphens
        return strtolower($text);
    }
}
