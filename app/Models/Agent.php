<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Agent extends Model
{
    protected $table = 'agents';

    public function invoiceDetail()
    {
        return $this->hasMany('App\Models\InvoiceDetail', 'agentId', 'ag_id');
    }
}
