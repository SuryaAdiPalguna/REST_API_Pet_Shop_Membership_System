<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Adopt extends Model
{
    /** @use HasFactory<\Database\Factories\AdoptFactory> */
    use HasFactory;
    use HasUlids;

    protected $fillable = [
        'member_id',
        'puppy_id',
        'date',
        'note',
    ];
    protected $with = ['member', 'puppy'];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function puppy()
    {
        return $this->belongsTo(Puppy::class);
    }

    public function scopeSearch(Builder $query, string $search)
    {
        $query->whereHas('member', function ($query) use ($search) {
            $query->where('name', 'like', "%{$search}%");
        })->orWhereHas('puppy', function ($query) use ($search) {
            $query->where('name', 'like', "%{$search}%");
        });
    }

    public function getRouteKeyName()
    {
        return 'id';
    }
}
