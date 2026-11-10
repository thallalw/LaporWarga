<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = [
        'user_id', 'category_id', 'area_id', 'title', 'specific_address', 
        'description', 'photo_path', 'status', 'priority', 'handling_priority', 'handling_priority_by', 'resolved_by', 
        'resolution_note', 'resolution_budget', 'resolution_photo_path', 'resolved_at', 'archived_at'
    ];

    protected $casts = [
        'resolved_at' => 'datetime',
        'archived_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function resolver()
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }

    // RT/RW yang menetapkan prioritas penanganan
    public function prioritizer()
    {
        return $this->belongsTo(User::class, 'handling_priority_by');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    public function likes()
    {
        return $this->belongsToMany(User::class, 'report_likes')->withPivot('created_at');
    }
    
    // Aksesor untuk menghitung total like dengan mudah
    public function getLikesCountAttribute()
    {
        return $this->likes()->count();
    }
}