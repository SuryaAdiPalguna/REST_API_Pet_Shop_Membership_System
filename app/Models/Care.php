<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Care extends Model
{
    /** @use HasFactory<\Database\Factories\CareFactory> */
    use HasFactory;
    use HasUlids;
    use LogsActivity;

    protected $fillable = [
        'name',
        'description',
    ];

    public function puppy_cares()
    {
        return $this->hasMany(HasMany::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->useLogName('care')->logOnly(['name', 'description'])->logOnlyDirty()->dontSubmitEmptyLogs()->setDescriptionForEvent(function (string $event_name) {
            return "Care has been {$event_name}";
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
