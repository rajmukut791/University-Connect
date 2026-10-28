<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostExtensionRequest extends Model
{
    protected $fillable = ['post_type', 'post_id', 'user_id', 'dates', 'original_dates', 'reason', 'status', 'reviewed_by', 'review_note', 'reviewed_at'];
    protected $casts = ['dates' => 'array', 'original_dates' => 'array', 'reviewed_at' => 'datetime'];

    public function user() { return $this->belongsTo(User::class); }
    public function reviewer() { return $this->belongsTo(User::class, 'reviewed_by'); }
}
