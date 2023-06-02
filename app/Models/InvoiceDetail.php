<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class InvoiceDetail extends Model
{
    protected $table = 'invoice_detail';
    protected $running = 1;
    protected $status;
    const CREATED_AT = 'creation_date';
    const UPDATED_AT = 'last_update';

    public function agent()
    {
        return $this->hasOne('App\Models\Agent', 'ag_id', 'agentId');
    }

    public function getRunning()
    {
        $invoice = DB::table('invoice_running')->select('no')->orderBy('id', 'desc')->first();
        if (!empty($invoice) > 0) {
            $this->running = $invoice->no;
        } else {
            DB::table('invoice_running')->insert(['no' => 1]);
        }

        return $this->running;
    }

    public function newGetRunning()
    {
        $invoice = DB::table('invoice_detail')->select('invNo')->orderBy('id', 'desc')->first();
        if ( ! $invoice )
            // We get here if there is no order at all
            // If there is no number set it to 0, which will be 1 at the end.

            $number = 0;
        else
            $number = substr($invoice->invNo, 3);

        // If we have ORD000001 in the database then we only want the number
        // So the substr returns this 000001

        // Add the string in front and higher up the number.
        // the %07d part makes sure that there are always 6 numbers in the string.
        // so it adds the missing zero's when needed.

        $this->running = 'ADV' . sprintf('%04d', intval($number) + 1);
        return $this->running;
    }

    public function getStatus($invRun)
    {
        $invoice = DB::table('invoice_detail')->select('status')->where('invRun', $invRun)->first();
        if (!empty($invoice) > 0) {
            $this->status = $invoice->status;
        }
        return $this->status;
    }
}
