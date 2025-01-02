<?php

namespace Acelle\Model;

use Illuminate\Database\Eloquent\Model;

class NewInvoice extends Model
{
    protected $table = 'invoice_clients';

    protected $fillable = [
        'uid','invoice_number','invoice_name','date_time','grand_total','payment_status','status',
    ];
}
