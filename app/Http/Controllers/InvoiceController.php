<?php

namespace App\Http\Controllers;

use App\Helpers\Currency;
use App\Helpers\GenVoucher;
use App\Helpers\StatusCode;
use App\Models\Agent;
use App\Models\InvoiceDetail;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use GuzzleHttp;
use Illuminate\Support\Facades\Log;
use PDF;
use Carbon\Carbon;

class InvoiceController extends Controller
{
    private $reservation;
    private $statusCode;
    private $getFunction;
    private $invoiceDetail;
    private $agent;
    private $client;
    private $currency;
    protected $total_adult = 0;
    protected $total_child = 0;
    protected $grand_total = 0;
    protected $total_complete = 0;
    protected $setData = [];
    protected $getData;

    public function __construct()
    {
        $this->reservation = new Reservation();
        $this->statusCode = new StatusCode();
        $this->getFunction = new GenVoucher();
        $this->invoiceDetail = new InvoiceDetail();
        $this->agent = new Agent();
        $this->client = new GuzzleHttp\Client();
        $this->currency = new Currency();
        
    }

    public function index(Request $request)
    {
        $data['page_title'] = 'Invoice';
        $data['agentId'] = $request->input('agentId');
        $data['dateType'] = $request->input('dateType');
        $data['grandTotal'] = 0;
        
        $type = $data['agentId'] != 'b2c' ? 1 : 2;

        $startDate = date('m/d/Y', strtotime('today'));
        $endDate = date('m/d/Y', strtotime('today'));

        $data['startDate'] = $request->input('startDate') ?? $startDate;
        $data['endDate'] = $request->input('endDate') ?? $endDate;
        $data['invRun'] = 0;
        $data['invNo'] = $this->invoiceDetail->newGetRunning(); //$data['invRun'];
        $data['agent'] = $this->getAgentData();

        $startDate = date('Y-m-d', strtotime($data['startDate']));
        $endDate = date('Y-m-d', strtotime($data['endDate']));
        $data['invoice'] = $this->genInvoiceData($data['agentId'], $type, $data['dateType'], $startDate, $endDate);
        return view('invoice.index', $data);
    }

    //Should Remove duplicate call flow
    private function genInvoiceData($agentId, $type, $dataType, $startDate, $endDate)
    {
        $invoiceURL = env('APP_URL') . '/api/invoice/list?agentId=' . $agentId . '&type=' . $type . '&dateType=' . $dataType . '&startDate=' . $startDate . '&endDate=' . $endDate;
        $invoice = $this->client->get($invoiceURL)->getBody();

        Log::info('GET|' . $invoiceURL);
        // dd(json_decode($invoice));
        return json_decode($invoice);
    }

    private function getAgentData()
    {
        $agentURL = env('APP_URL') . '/api/agent/list';
        $agent = $this->client->get($agentURL)->getBody();
        return json_decode($agent);
    }

    public function getInvoice(Request $request)
    {
        $agentId = $request->input('agentId');
        $dateType = $request->input('dateType');
        $voucherNo = $request->input('voucherNo');

        $typeId = $request->input('type') ?? '';
        $startDate = $request->input('startDate') ?? date('Y-m-d');
        $endDate = $request->input('endDate') ?? date('Y-m-d', strtotime('today'));
//        $limit = $request->input('limit') ?? 300;
        $offset = $request->input('offset') ?? 0;

        $order = $this->reservation->getOrder($agentId, $typeId, $voucherNo, $dateType, $startDate, $endDate, $offset);

        $this->setData = $this->statusCode->getStatusCode(200000);
        foreach ($order as $k => $row) {
            $agentName = $this->reservation->getAgentName($row->agents_id);
            $customer = $this->reservation->getTitleName($row->titlename_id) . ' ' . $row->res_fname . ' ' . $row->res_lname;

            $type = $row->agents_id != 0 ? 'B2B' : 'B2C';

            $this->setData['data'][$k] = [
                'no' => $k + 1,
                'voucherNo' => $row->res_id_str,
                'refNo' => $row->rbt_ref,
                'bookingDate' => $row->res_date,
                'route' => $row->rbt_name,
                'travelDate' => substr($row->rbt_travel_date, 0, 10),
                'type' => $type,
                'agentId' => $row->agents_id,
                'agentName' => $agentName,
                'customer' => $customer,
                'address' => $row->res_address,
                'email' => $row->res_email,
                'phone' => $row->res_phone,
                'adult' => $row->rbt_adult_num,
                'child' => $row->rbt_child_num,
                'priceAdult' => $row->rbt_adult_prices,
                'priceChild' => $row->rbt_child_prices,
                'totalAdult' => ($row->rbt_adult_num * $row->rbt_adult_prices),
                'totalChild' => ($row->rbt_child_num * $row->rbt_child_prices),
                'grandTotal' => $row->rbt_prices
            ];
        }

        return response()->json($this->setData);
    }

    public function getVoucherNo(Request $request)
    {
        $invNo = $request->input('invNo');

        $invoices = $this->invoiceDetail->where('invNo', $invNo)->orderBy('voucherNo', 'asc')->get();
        $this->setData = $this->statusCode->getStatusCode(200000);
        foreach ($invoices as $k => $row) {
            $this->setData['data'][$k] = [
                'voucherNo' => $row->voucherNo
            ];
        }
        return response()->json($this->setData);
    }

    public function createInvoice(Request $request)
    {
        Log::info('POST|CREATE_INVOICE|REQ|' . json_encode($request->all()));
        $invRun = $request->input('invRun');
        $invNo = $request->input('invNo');
        $items = $request->input('items');
        $vouchers = $request->input('vouchers');
        $agents = $request->input('agents');
        $amounts = $request->input('amounts');
       
        $bookingDate = $request->input('bookingDate');
        $travelDate = $request->input('travelDate');
        $dataType = $request->input('datatype');
        $customDate = $request->input('custom' ?? date('Y-m-d'));
        $newDate = Carbon::parse($customDate)->format('Y-m-d');

        $type = "BookingDate";
        if ($dataType == '2') {
            $type = "TravelDate";
            $bookingDate = $travelDate;
        } else if ($dataType == '3') {
            $type = 'CustomDate';
            $bookingDate = $newDate;
        }
        $this->saveInvoice($items, $invNo, $vouchers, $agents, $amounts, $bookingDate, $type);

        DB::table('invoice_running')->increment('no', 1);

        return redirect('history')->with('success', 'successfully!');
    }

    public function genInvoice(Request $request)
    {
        $invNo = $request->input('invNo');
        $data['invNo'] = $invNo;
        $voucherNo = '';
        $agentId = '';
        $amount = 0;
        $status = '';
        $date = '';

        $invoice = $this->invoiceDetail->select('voucherNo', 'agentId', 'amount', 'status', 'datatype', 'inv_date')->where('invNo', $invNo)->get();
        foreach ($invoice as $row) {
            $voucherNo = $row->voucherNo;
            $agentId = $row->agentId;
            $amount += $row->amount;
            $status = $row->status;
            $dataType = $row->datatype;
            $date = substr($row->inv_date, 0, 10);
        }
        $data['agentId'] = $agentId;
        $data['amount'] = number_format($amount, 0);
        $data['amountText'] = ucfirst($this->currency->bahtEng($amount));
        $data['status'] = $status;
        $data['agent'] = $this->agent->where('ag_id', $agentId)->get();
        $data['order'] = $this->reservation->getReservation($voucherNo);
        if ($dataType == "CustomDate" || $dataType == "TravelDate") {
            $data['date'] = $this->getFunction->DateFormat($date, 'm');
        } else {
            if ($data['order'][0]['bookingDate']) {
                $data['date'] = $this->getFunction->DateFormat($data['order'][0]['bookingDate'], 'm');
            } else {
                $data['date'] = $this->getFunction->DateFormat(substr($date, 0, 10), 'm');
            }
        }

        if ($invNo == "ADV6203067")
            $data['date'] = $this->getFunction->DateFormat('2019-03-21', 'm');

        $pdf = PDF::loadView('pdf.invoice', $data);
        return @$pdf->stream($invNo . '.pdf');
    }

    public function queryAgent($id)
    {
        $agentName = $this->agent->select('ag_name')->where('ag_id', $id)->first();
        $name = $agentName->ag_name;
        return $name;
    }

    /**
     * @param $items
     * @param $invNo
     * @param $vouchers
     * @param $agents
     * @param $amounts
     * @param $bookingDate
     * @param $type
     */
    private function saveInvoice($items, $invNo, $vouchers, $agents, $amounts, $bookingDate, $type): void
    {
        $today = date('Y-m-d H:i:s');
        $voucher = [];
        foreach ($items as $item) {
          
           array_push($voucher, $vouchers[$item]);
            // $agent[] = ['agents' => $agents[$item]];
            // $amont[] = ['amounts' => $amounts[$item]];
           
        }

        $query = DB::table('invoice_detail')->whereIn('voucherNo', $voucher)->get();

        $numVouch = $query->count();

        $invoice = DB::table('invoice_detail')->where('invNo', $invNo)->get();

        $numInv = $invoice->count();
        
        // dd($numVouch);
        // dd($numInv);
        if ( $numInv == 0) {       //Make new one if not already created
            foreach ($items as $item) {
                DB::table('invoice_detail')->Insert([
                    'id' => null,
                    'invRun' => 0,
                    'invNo' => $invNo,
                    'voucherNo' => $vouchers[$item],
                    'agentId' => $agents[$item],
                    'amount' => $amounts[$item],
                    'status' => 'Y',
                    'datatype' => $type,
                    'inv_date' => is_array($bookingDate) ? $bookingDate[$item] : $bookingDate,
                    'creation_date' => $today,
                    'last_update' => $today
                ]);
                // dd($item);
            }
        } elseif ($numVouch == 0 && $numInv > 0 && $invoice->where('status', '=', 'N')->count() == $numInv) {

            DB::table('invoice_detail')->where('invNo',  $invNo)->delete();

            foreach ($items as $item) {
                DB::table('invoice_detail')->Insert([
                    'id' => null,
                    'invRun' => 0,
                    'invNo' => $invNo,
                    'voucherNo' => $vouchers[$item],
                    'agentId' => $agents[$item],
                    'amount' => $amounts[$item],
                    'status' => 'Y',
                    'datatype' => $type,
                    'inv_date' => is_array($bookingDate) ? $bookingDate[$item] : $bookingDate,
                    'creation_date' => $today,
                    'last_update' => $today
                ]);
            }
        } elseif (($numVouch > 0) && $query->where('status', '=', 'N')->count() == $numVouch) {

            $invoice = DB::table('invoice_detail')
                ->whereIn('invNo', $query->pluck('invNo'))
                ->get();
            $numInv = $invoice->count();

//            dd($numInv > 0 && $invoice->where('status', '=', 'N')->count() == $numInv );
            if ($numInv == 0) {
                foreach ($items as $item) {
                    DB::table('invoice_detail')->Insert([
                        'id' => null,
                        'invRun' => 0,
                        'invNo' => $invNo,
                        'voucherNo' => $vouchers[$item],
                        'agentId' => $agents[$item],
                        'amount' => $amounts[$item],
                        'status' => 'Y',
                        'datatype' => $type,
                        'inv_date' => is_array($bookingDate) ? $bookingDate[$item] : $bookingDate,
                        'creation_date' => $today,
                        'last_update' => $today
                    ]);
                }
            } elseif ($numInv > 0 && $query->contains('status', 'N')) {
                foreach ($items as $item) {
                    $values = array(
                        'id' => null,
                        'invRun' => 0,
                        'invNo' => $invNo,
                        'voucherNo' => $vouchers[$item],
                        'agentId' => $agents[$item],
                        'amount' => $amounts[$item],
                        'status' => 'Y',
                        'datatype' => $type,
                        'inv_date' => is_array($bookingDate) ? $bookingDate[$item] : $bookingDate,
                        'creation_date' => $today,
                        'last_update' => $today
                    );
                }
                // dd($values);
                $this->invoiceDetail::whereIn('primary_key', 'value')->update($values);
            }

        }
    }
}
