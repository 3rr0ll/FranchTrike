<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use App\Models\Role;
use Illuminate\Contracts\Auth\MustVerifyEmail;


class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens;

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'is_active',
        'login_attempts',
        'locked_until',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'locked_until' => 'datetime',
            'is_active' => 'boolean',
        ];
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
    public function operator()
    {
        return $this->hasOne(Operator::class, 'user_id', 'id');
    }

    public function reviewedApplications()
    {
        return $this->hasMany(FranchiseApplication::class, 'reviewed_by');
    }

    public function verifiedOperatorDocuments()
    {
        return $this->hasMany(OperatorDocument::class, 'verified_by');
    }

    public function verifiedDriverDocuments()
    {
        return $this->hasMany(DriverDocument::class, 'verified_by');
    }

    public function siteNotifications()
    {
        return $this->hasMany(SiteNotification::class);
    }

    public function statusChanges()
    {
        return $this->hasMany(ApplicationStatusHistory::class, 'changed_by');
    }

    // Login Security Methods
    public function loginLogs()
    {
        return $this->hasMany(LoginLog::class);
    }

    public function isLocked()
    {
        return $this->locked_until && $this->locked_until->isFuture();
    }

    public function incrementLoginAttempts()
    {
        $this->increment('login_attempts');
        $this->refresh();
    }

    public function resetLoginAttempts()
    {
        $this->update([
            'login_attempts' => 0,
            'locked_until' => null,
        ]);
    }

    public function lockAccount($minutes = 30)
    {
        $this->update([
            'locked_until' => now()->addMinutes($minutes),
        ]);
    }

    public function unlockAccount()
    {
        $this->update([
            'locked_until' => null,
            'login_attempts' => 0,
        ]);
    }

    public function getRemainingLockTime()
    {
        if (!$this->isLocked()) {
            return 0;
        }
        return now()->diffInSeconds($this->locked_until, false);
    }
}
