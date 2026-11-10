<?php

namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $fillable = [
        'name',
        'username',
        'role',
        'area_id',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Fungsi helper untuk role
    public function isWarga() { return $this->role === 'warga'; }
    public function isAdmin() { return in_array($this->role, ['rt', 'rw']); }

    // Label role yang mudah dibaca
    public function roleLabel(): string
    {
        return match ($this->role) {
            'rt' => 'Ketua RT',
            'rw' => 'Ketua RW',
            default => 'Warga',
        };
    }

    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    public function resolvedReports()
    {
        return $this->hasMany(Report::class, 'resolved_by');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }
}
