<?php

namespace App\Models;

use App\Models\Scopes\Api\CheckOut as CheckOutScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

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

    public function scopeFilter(Builder $query, string $filter): void
    {
        if (Str::length($filter) > 0) {
            $query->orWhere('check_out_date', 'LIKE', "%$filter%")
                ->orWhere('status', $filter)
                ->orWhereHas('book', fn(Builder $query) => $query->where('title', 'LIKE', "%$filter%")->orWhere('author', 'LIKE', "%$filter%"))
                ->orWhereHas('user', fn(Builder $query) => $query->where('first_name', 'LIKE', "%$filter%")->orWhere('last_name', 'LIKE', "%$filter%")->orWhere('email', 'LIKE', "%$filter%"));
        }
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
