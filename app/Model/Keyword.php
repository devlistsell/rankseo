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

    public static function search($request)
    {
        $query = self::filter($request);
        $query = $query->orderBy($request->sort_order, $request->sort_direction);
        return $query;
    }

    public function scopeFilter(Builder $query, $filters)
    {
        if (!empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        if (!empty($filters['keyword'])) {
            $query->where('message', 'like', '%' . $filters['keyword'] . '%');
        }

        return $query;
    }
}
