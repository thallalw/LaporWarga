<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'user_id', 'title', 'message', 'type', 'is_read'
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // kirim satu notifikasi ke banyak user sekaligus
    public static function notify($userIds, string $title, string $message, string $type = 'info'): void
    {
        $now = now();
        $rows = collect($userIds)->unique()->map(fn ($id) => [
            'user_id'    => $id,
            'title'      => $title,
            'message'    => $message,
            'type'       => $type,
            'is_read'    => false,
            'created_at' => $now,
            'updated_at' => $now,
        ])->values()->all();

        if (!empty($rows)) {
            static::insert($rows);
        }
    }
}

