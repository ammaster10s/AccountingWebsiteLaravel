<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Reservation extends Model
{
    protected $titleName;
    protected $agentName;
    protected $setData;
    protected $sorting = 7;

    public function getOrder($agents_id, $typeId, $voucherNo, $dateType, $startDate, $endDate,  $offset)
    {
        if ($dateType == 2) {
            $this->sorting = 10;
        }

        $invoice = DB::table('invoice_detail')->select('voucherNo')->where('status', 'Y')->get();

        foreach ($invoice as $k => $row) {
            $this->setData .= "'" . $row->voucherNo . "',";
        }

        $this->setData = substr($this->setData, 0, -1);

        $sql = "SELECT R.*, A.ag_name FROM ( ";
        $sql .= "SELECT res_id_str, titlename_id, res_fname, res_lname, agents_id, bookingstatus_id, res_date, rbt_prices, rbt_name, rbt_travel_date, rbt_ref, rbt_adult_num, rbt_child_num, ";
        $sql .= "rbt_adult_prices, rbt_child_prices, res_address, res_email, res_phone ";
        $sql .= "FROM reservations ";
        $sql .= "LEFT JOIN reservation_boattransfer_items ON reservations . res_id = reservation_boattransfer_items . reservations_id ";
        $sql .= "WHERE bookingstatus_id = '3' AND rbt_name <> '' ";

        $sql .= "UNION ";
        $sql .= "SELECT res_id_str, titlename_id, res_fname, res_lname, agents_id, bookingstatus_id, res_date, rct_prices, rct_name, rct_travel_date, rct_ref, rct_adult_num, rct_child_num, ";
        $sql .= "rct_adult_prices, rct_child_prices, res_address, res_email, res_phone ";
        $sql .= "FROM reservations ";
        $sql .= "LEFT JOIN reservation_bustransfer_items ON reservations . res_id = reservation_bustransfer_items . reservations_id ";
        $sql .= "WHERE bookingstatus_id = '3' AND rct_name <> '' ";

        $sql .= "UNION ";
        $sql .= "SELECT res_id_str, titlename_id, res_fname, res_lname, agents_id, bookingstatus_id, res_date, rpt_prices, rpt_name, rpt_travel_date, rpt_ref, rpt_adult_num, rpt_child_num, ";
        $sql .= "rpt_adult_prices, rpt_child_prices, res_address, res_email, res_phone ";
        $sql .= "FROM reservations ";
        $sql .= "LEFT JOIN reservation_pickuptransfer_items ON reservations . res_id = reservation_pickuptransfer_items . reservations_id ";
        $sql .= "WHERE bookingstatus_id = '3' AND rpt_name <> '' ";

        $sql .= "UNION ";
        $sql .= "SELECT res_id_str, titlename_id, res_fname, res_lname, agents_id, bookingstatus_id, res_date, rrt_prices, rrt_name, rrt_travel_date, rrt_ref, rrt_adult_num, rrt_child_num, ";
        $sql .= "rrt_adult_prices, rrt_child_prices, res_address, res_email, res_phone ";
        $sql .= "FROM reservations ";
        $sql .= "LEFT JOIN reservation_traintransfer_items ON reservations . res_id = reservation_traintransfer_items . reservations_id ";
        $sql .= "WHERE bookingstatus_id = '3' AND rrt_name <> '' ";

        $sql .= "UNION ";
        $sql .= "SELECT res_id_str, titlename_id, res_fname, res_lname, agents_id, bookingstatus_id, res_date, rplt_prices, rplt_name, rplt_travel_date, rplt_ref, rplt_adult_num, rplt_child_num, ";
        $sql .= "rplt_prices, rplt_prices, res_address, res_email, res_phone ";
        $sql .= "FROM reservations ";
        $sql .= "LEFT JOIN reservation_privatelandtransfer_items ON reservations . res_id = reservation_privatelandtransfer_items . reservations_id ";
        $sql .= "WHERE bookingstatus_id = '3' AND rplt_name <> '' ";

        $sql .= "UNION ";
        $sql .= "SELECT res_id_str, titlename_id, res_fname, res_lname, agents_id, bookingstatus_id, res_date, rtt_prices, rtt_name, rtt_travel_date, rtt_ref, rtt_adult_num, rtt_child_num, ";
        $sql .= "rtt_adult_prices, rtt_child_prices, res_address, res_email, res_phone ";
        $sql .= "FROM reservations ";
        $sql .= "LEFT JOIN reservation_tour_items ON reservations . res_id = reservation_tour_items . reservations_id ";
        $sql .= "WHERE bookingstatus_id = '3' AND rtt_name <> '' ";

        $sql .= "UNION ";
        $sql .= "SELECT rpa_id_str, titlename_id, rpa_fname, rpa_lname, agents_id, bookingstatus_id, rpa_date, rpt_prices, rpt_name, REPLACE(rpt_item_travel_date_arr, '~', ''), rpt_ref, rpt_adult_num, rpt_child_num, ";
        $sql .= "rpt_adult_prices, rpt_child_prices, rpa_address, rpa_email, rpa_phone ";
        $sql .= "FROM reservation_packages ";
        $sql .= "LEFT JOIN reservationpackage_item ON reservation_packages . rpa_id = reservationpackage_item . reservationpackages_id ";
        $sql .= "LEFT JOIN packages ON reservationpackage_item . packages_id = packages . pac_id ";
        $sql .= "WHERE bookingstatus_id = '3' AND rpt_name <> '' ";
        $sql .= ") AS R LEFT JOIN agents A ON R . agents_id = A . ag_id ";

        if ($dateType == 1) {
            $sql .= "WHERE res_date >= '" . $startDate . "' ";
            $sql .= "AND res_date <= '" . $endDate . "' ";
        } else {
            $sql .= "WHERE rbt_travel_date >= '" . $startDate . "' ";
            $sql .= "AND rbt_travel_date <= '" . $endDate . "' ";
        }

        if ($typeId == 1) {
            $sql .= "AND agents_id != '0' ";
        }

        if ($typeId == 2) {
            $sql .= "AND agents_id = '0' ";
        }

        if ($agents_id) {
            $sql .= "AND agents_id = '" . $agents_id . "' ";
        }

        if ($voucherNo) {
            $sql .= "AND res_id_str = '" . $voucherNo . "' ";
        }

        $sql .= "ORDER BY " . $this->sorting ;

//        if (env('APP_DEBUG'))
//            Log::info('RAW STATEMENT|' . DB::raw($sql));

        // dd(DB::raw($sql));
        $reservation = DB::select($sql);
        //dd($reservation);
        return $reservation;
    }

    public function getReservation($voucherNo)
    {
        if (substr($voucherNo, 0, 1) == 'R' || substr($voucherNo, 0, 1) == 'S') {
            $reservation = DB::table('reservations')->select('titlename_id', 'res_fname', 'res_lname', 'res_address', 'res_email', 'res_phone', 'res_date')->where('res_id_str', $voucherNo)->get();
            foreach ($reservation as $row) {
                $customer = $this->getTitleName($row->titlename_id) . ' ' . $row->res_fname . ' ' . $row->res_lname;
                $this->setData[] = [
                    'customer' => $customer,
                    'address' => $row->res_address,
                    'email' => $row->res_email,
                    'phone' => $row->res_phone,
                    'bookingDate' => $row->res_date
                ];
            }
        } else {
            $reservation = DB::table('reservation_packages')->select('titlename_id', 'rpa_fname', 'rpa_lname', 'rpa_address', 'rpa_email', 'rpa_phone', 'rpa_date')->where('rpa_id_str', $voucherNo)->get();
            foreach ($reservation as $row) {
                $customer = $this->getTitleName($row->titlename_id) . ' ' . $row->rpa_fname . ' ' . $row->rpa_lname;

                $this->setData[] = [
                    'customer' => $customer,
                    'address' => $row->rpa_address,
                    'email' => $row->rpa_email,
                    'phone' => $row->rpa_phone,
                    'bookingDate' => $row->rpa_date
                ];
            }
        }

        return $this->setData;
    }

    public function getTitleName($id)
    {
        $titleName = DB::table('lis_titlename')->where('lis_id', $id)->first();
        if (isset($titleName)) $this->titleName = $titleName->lis_name;
        return $this->titleName;
    }

    public function getAgentName($id)
    {
        $agent = DB::table('agents')->where('ag_id', $id)->first();
        if (isset($agent) > 0) $this->agentName = $agent->ag_name;
        return $this->agentName;
    }
}
