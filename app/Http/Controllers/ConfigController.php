<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use Illuminate\Http\Request;

class ConfigController extends Controller
{
    private $agent;
    protected $setData;

    public function __construct()
    {
        $this->agent = new Agent();
    }

    public function getAgent(Request $request)
    {
        $agentId = $request->input('agentId');
        $agentName = $request->input('agentName');

        $agent = $this->agent->where('ag_status', 0)->where('ag_active', 'Y');
        if ($agentId) {
            $agent->where('ag_id', $agentId);
        }
        if ($agentName) {
            $agent->where('ag_name', 'like', '%' . $agentName . '%');
        }
        $agentList = $agent->whereNotIn('ag_id', [1, 15, 33, 34, 38])->orderBy('ag_name', 'asc')->get();
        foreach ($agentList as $k => $row) {
            $this->setData['data'][$k] = [
                'no' => $k + 1,
                'agentId' => $row->ag_id,
                'agentName' => $row->ag_name
            ];
        }

        return response()->json($this->setData);
    }
}
