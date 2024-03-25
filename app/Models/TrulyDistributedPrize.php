<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrulyDistributedPrize extends Model
{
    use HasFactory;

    protected $table = 'truly_distributed_prizes';

    protected $guarded = ['id'];

    public function prize()
    {
        return $this->belongsTo(Prize::class);
    }
}
