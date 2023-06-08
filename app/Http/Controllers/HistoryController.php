<?php

namespace App\Http\Controllers;

use App\Helpers\StatusCode;
use App\Models\InvoiceDetail;
use Illuminate\Http\Request;
use GuzzleHttp;
use Illuminate\Support\Facades\DB;

class HistoryController extends Controller
{
    private $statusCode;
    private $invoiceDetail;
    private $client;
    protected $setData = [];

    public function __construct()
    {
        $this->statusCode = new StatusCode();
        $this->invoiceDetail = new InvoiceDetail();
        $this->client = new GuzzleHttp\Client();
    }

    public function index(Request $request)
    {
        $data['page_title'] = 'History';
        $data['invNo'] = $request->input('invNo');

        $invNo = $request->input('invNo');

        $this->setData = $this->statusCode->getStatusCode(200000);
        $invoice = $this->invoiceDetail;

        if ($invNo) {
            $invoice = $invoice->where('invNo', 'like', '%' . $invNo . '%')
                ->groupBy('invNo', 'status')
                ->select('invNo', 'status')
                ->get(['invNo', 'status']);

                 
        } else {
        
                $invoice = $invoice     /*->where('invNo', 'like', '%' . $invNo . '%')*/
                    ->groupBy('invNo', 'status')
                    ->select('invNo', 'status')
                    ->get(['invNo', 'status']);
            
        }
    

        
        //$invoice = $this->client->get(env('APP_URL') . '/api/history/list?invNo=' . $data['invNo'])->getBody();
        $data['invoice'] = $invoice;
        return view('invoice.history', $data);
    }

    private function getA($invNo){
        $invoice = $this->invoiceDetail;

        if ($invNo) {
            $invoice = $invoice->where('invNo', 'like', '%' . $invNo . '%')
                ->orderBy('invNo', 'desc')
                ->groupBy('invNo')

                ->get();
        } else {
            $invoice = $invoice->where('invNo', 'like', '%' . $invNo . '%')
            ->orderBy('invNo', 'desc')
            ->groupBy('invNo')

            ->get();
        }

        return $invoice;
    }

    public function getHistory(Request $request)
    {
        $invNo = $request->input('invNo');
        $this->setData['data'] = $this->getA(($invNo));
        return response()->json($this->setData);
    }

    public function updateHistory(Request $request)
    {
        $invNo = $request->input('invNo');
        DB::table('invoice_detail')->where('invNo', $invNo)->update([
            'status' => 'N'
        ]);

        return redirect('history');
    }
}