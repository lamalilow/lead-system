<?php

namespace App\Models;

use App\Enums\LeadStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lead extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name', 'email', 'phone', 'message', 'status', 'source_id'
    ];

    protected $casts = [
        'status' => LeadStatus::class
    ];

    protected $attributes = [
        'status' => LeadStatus::New
    ];

    public function source()
    {
        return $this->belongsTo(Source::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($lead) {
            if (!$lead->email && !$lead->phone) {
                throw new \Exception('Требуется email или телефон.');
            }
        });
    }
}
