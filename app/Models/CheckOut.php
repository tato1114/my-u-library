<?php

namespace App\Models;

use App\Models\Scopes\Api\CheckOut as CheckOutScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CheckOut extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'book_id',
        'user_id',
        'check_out_date',
        'return_date',
        'status'
    ];

    protected static function booted(): void
    {
        static::addGlobalScope(new CheckOutScope);
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
