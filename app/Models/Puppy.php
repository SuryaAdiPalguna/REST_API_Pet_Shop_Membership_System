<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Puppy extends Model
{
    /** @use HasFactory<\Database\Factories\PuppyFactory> */
    use HasFactory;
    use HasUlids;

    protected $fillable = [
        'breed_id',
        'name',
    ];
    protected $with = ['breed'];

    public function breed()
    {
        return $this->belongsTo(Breed::class);
    }

    public function adopts()
    {
        return $this->hasMany(Adopt::class);
    }

    public function puppy_cares()
    {
        return $this->hasOne(PuppyCare::class);
    }

    public function scopeSearch(Builder $query, string $search)
    {
        $query->where('name', 'like', "%{$search}%");
    }

    public function getRouteKeyName()
    {
        return 'id';
    }
}
