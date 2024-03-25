<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DistributedPrize extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function prize()
    {
        return $this->belongsTo(Prize::class);
    }
}
