<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Care extends Model
{
    /** @use HasFactory<\Database\Factories\CareFactory> */
    use HasFactory;
    use HasUlids;

    protected $fillable = [
        'name',
        'description',
    ];

    public function puppy_cares()
    {
        return $this->hasMany(HasMany::class);
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
