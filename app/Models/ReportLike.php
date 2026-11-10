<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ReportLike extends Pivot
{
    protected $table = 'report_likes';
    
    public $incrementing = false;
    
    public $timestamps = false; 
}
