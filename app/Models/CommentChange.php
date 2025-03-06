<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CommentChange extends Model
{
    use HasFactory;

    protected $table = 'yorum_changes';

    protected $fillable = [
        'comment_id',
        'field',
        'old_value',
        'new_value',
        'changed_by'
    ];

    protected $casts = [
        'old_value' => 'array',
        'new_value' => 'array'
    ];

    public function comment(): BelongsTo
    {
        return $this->belongsTo(Comment::class);
    }

    public function changedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
} 