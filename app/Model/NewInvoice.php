<?php

namespace Acelle\Model;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class NewInvoice extends Model
{
    protected $table = 'invoice_clients';

    protected $fillable = [
        'uid','invoice_number','invoice_name','date_time','grand_total','payment_status','status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'uid', 'id');
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
