<?php

namespace Acelle\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;


class KeywordHistory extends Model
{
    protected $table = 'keyword_histories';

    protected $fillable = [
        'keyword_id', 'uid', 'ranking', 'date_time',
    ];

    public function keyword()
    {
        return $this->belongsTo(Keyword::class, 'keyword_id');
    }

    public function formatDate()
    {
        return Carbon::parse($this->date_time)->format('F j, Y');
    }

    /**
     * Format the time field.
     *
     * @return string
     */
    public function formatTime()
    {
        return Carbon::parse($this->date_time)->timezone('Asia/Kolkata')->format('H:i:s');
    }
}


