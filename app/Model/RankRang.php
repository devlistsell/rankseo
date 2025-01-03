<?php

namespace Acelle\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RankRang extends Model
{
    use HasFactory;
    
    protected $fillable = ['rank_label', 'min_value', 'max_value'];
}
