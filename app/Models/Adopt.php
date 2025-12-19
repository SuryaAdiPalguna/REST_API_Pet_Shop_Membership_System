<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Adopt extends Model
{
    /** @use HasFactory<\Database\Factories\AdoptFactory> */
    use HasFactory;
    use HasUlids;
    use LogsActivity;

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

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->useLogName('adopt')->logOnly(['member_id', 'puppy_id', 'date', 'note'])->logOnlyDirty()->dontSubmitEmptyLogs()->setDescriptionForEvent(function (string $event_name) {
            return "Adopt has been {$event_name}";
        });
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
