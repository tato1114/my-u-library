<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Book extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'author',
        'published_year',
        'genre',
        'stock'
    ];

    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    public function scopeFilter(Builder $query, string $filter): void
    {
        if (Str::length($filter) > 0) {
            $query->orWhere('title', 'LIKE', "%$filter%")
                ->orWhere('author', 'LIKE', "%$filter%")
                ->orWhere('genre', 'LIKE', "%$filter%");
        }
    }

    public function checkOuts()
    {
        return $this->hasMany(CheckOut::class);
    }
}
