<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;

    protected $fillable = [
        'book_id',
        'user_id',
        'loaned_at',
        'returned_at',
        'due_at',
    ];

    protected $casts = [
        'loaned_at'   => 'datetime',
        'returned_at' => 'datetime',
        'due_at' => 'datetime',
    ];

    protected $appends = [
        'is_overdue'
    ];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeOverdue()
    {
        return $this->due_date && $this->due_at->isPast();
    }

    public function getIsOverdueAttribute()
    {
        return $this->due_date && $this->due_at->isPast();
    }
}
