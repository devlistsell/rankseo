<?php
namespace Acelle\Model;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Keyword extends Model
{
    protected $table = 'keywords';

    protected $fillable = [
        'uid', 'keyword', 'ranking', 'difficulty', 'difficulty_id', 'date_time', 'status',
    ];

    public static $itemsPerPage = 25;

    public function user()
    {
        return $this->belongsTo(User::class, 'uid');
    }

    public function histories()
    {
        return $this->hasMany(KeywordHistory::class, 'keyword_id');
    }
    
    /**
     * Format the date field.
     *
     * @return string
     */
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

    public function rank()
    {
        return $this->hasOne(Rank::class, 'id', 'ranking');
    }

    public function rankDifficulty()
    {
        return $this->hasOneThrough(
            RankDifficulty::class,
            Rank::class,
            'id',
            'rank_id',
            'ranking',
            'id'
        );
    }

    public function scopeLast7Days($query)
    {
        return $query->where('date_time', '>=', now()->subDays(7));
    }

    public function calculatePrice()
    {
        $rankDifficulty = $this->rankDifficulty;
        return $rankDifficulty ? $rankDifficulty->price : 0;
    }

}
