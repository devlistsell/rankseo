<?php

namespace Acelle\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RankDifficulty extends Model
{
    use HasFactory;

    protected $fillable = ['rank_id', 'min_score', 'max_score', 'price'];

}
