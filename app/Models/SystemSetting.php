<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
    ];

    /**
     * Get a setting value by key
     */
    public static function get($key, $default = null)
    {
        $setting = static::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    /**
     * Set a setting value by key
     */
    public static function set($key, $value)
    {
        return static::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
    }

    /**
     * Get the logo URL from database
     */
    public static function getLogoUrl()
    {
        $logoPath = static::get('logo_path');
        if ($logoPath && \Storage::disk('public')->exists($logoPath)) {
            return \Storage::disk('public')->url($logoPath);
        }
        return null;
    }

    /**
     * Check if logo exists in database
     */
    public static function hasLogo()
    {
        $logoPath = static::get('logo_path');
        return $logoPath && \Storage::disk('public')->exists($logoPath);
    }
}
