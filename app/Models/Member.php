<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Member extends Model
{
    /** @use HasFactory<\Database\Factories\MemberFactory> */
    use HasFactory;
    use HasUlids;
    use LogsActivity;

    protected $fillable = [
        'name',
        'phone',
        'email',
        'address',
        'is_active',
    ];

    public function adopts()
    {
        return $this->hasMany(Member::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->useLogName('member')->logOnly(['name', 'phone', 'email', 'address', 'is_active'])->logOnlyDirty()->dontSubmitEmptyLogs()->setDescriptionForEvent(function (string $event_name) {
            return "Member has been {$event_name}";
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
