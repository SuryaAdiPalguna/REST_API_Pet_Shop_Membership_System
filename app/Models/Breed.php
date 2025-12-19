<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Breed extends Model
{
    /** @use HasFactory<\Database\Factories\BreedFactory> */
    use HasFactory;
    use HasUlids;
    use LogsActivity;

    protected $fillable = [
        'name',
    ];

    public function puppies()
    {
        return $this->hasMany(Puppy::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->useLogName('breed')->logOnly(['name'])->logOnlyDirty()->dontSubmitEmptyLogs()->setDescriptionForEvent(function (string $event_name) {
            return "Breed has been {$event_name}";
        });
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
