<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SecuritySetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'type',
        'description',
    ];

    /**
     * Get a security setting value
     */
    public static function getValue(string $key, $default = null)
    {
        $setting = static::where('key', $key)->first();
        
        if (!$setting) {
            return $default;
        }

        switch ($setting->type) {
            case 'integer':
                return (int) $setting->value;
            case 'boolean':
                return filter_var($setting->value, FILTER_VALIDATE_BOOLEAN);
            case 'json':
                return json_decode($setting->value, true);
            default:
                return $setting->value;
        }
    }

    /**
     * Set a security setting value
     */
    public static function setValue(string $key, $value, string $type = 'string', string $description = null)
    {
        $setting = static::where('key', $key)->first();
        
        if ($setting) {
            $setting->update([
                'value' => is_array($value) ? json_encode($value) : (string) $value,
                'type' => $type,
                'description' => $description,
            ]);
        } else {
            static::create([
                'key' => $key,
                'value' => is_array($value) ? json_encode($value) : (string) $value,
                'type' => $type,
                'description' => $description,
            ]);
        }
    }

    /**
     * Get all security settings as an array
     */
    public static function getAllSettings()
    {
        $settings = static::all();
        $result = [];
        
        foreach ($settings as $setting) {
            $result[$setting->key] = static::getValue($setting->key);
        }
        
        return $result;
    }

    /**
     * Get max login attempts setting
     */
    public static function getMaxLoginAttempts()
    {
        return static::getValue('max_login_attempts', 5);
    }

    /**
     * Get lockout duration setting
     */
    public static function getLockoutDuration()
    {
        return static::getValue('lockout_duration_minutes', 30);
    }

    /**
     * Check if login logging is enabled
     */
    public static function isLoginLoggingEnabled()
    {
        return static::getValue('enable_login_logging', true);
    }

    /**
     * Check if account lockout is enabled
     */
    public static function isAccountLockoutEnabled()
    {
        return static::getValue('enable_account_lockout', true);
    }

    /**
     * Get session timeout setting
     */
    public static function getSessionTimeout()
    {
        return static::getValue('session_timeout_minutes', 120);
    }
}
