<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PuppyCare extends Model
{
    /** @use HasFactory<\Database\Factories\PuppyCareFactory> */
    use HasFactory;
    use HasUlids;

    protected $fillable = [
        'puppy_id',
        'care_id',
        'period',
    ];
    protected $with = ['puppy', 'care'];

    public function puppy()
    {
        return $this->belongsTo(Puppy::class);
    }

    public function care()
    {
        return $this->belongsTo(Care::class);
    }
}
