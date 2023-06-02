<?php

namespace App\Helpers;

class StatusCode
{
    public function getStatusCode($code = null, $value = null)
    {
        switch ($code) {
            case 200000:
                $data = array('resultCode' => 200000, 'message' => 'successfully');
                break;
            case 403000:
                $data = array('resultCode' => 403000, 'message' => 'Required Validation Parameter');
                break;
            case 403001:
                $data = array('resultCode' => 403001, 'message' => 'Not category in the system');
                break;
            case 403002:
                $data = array('resultCode' => 403002, 'message' => 'Not product in the system');
                break;
            case 403003:
                $data = array('resultCode' => 403003, 'message' => 'Over credit');
                break;
            case 403004:
                $data = array('resultCode' => 403004, 'message' => 'Not Data Value');
                break;
            case 403005:
                $data = array('resultCode' => 403005, 'message' => 'The Allotment not enough');
                break;
            case 403007:
                $data = array('resultCode' => 403007, 'message' => 'Please booking ' . $value . ' hour in advance');
                break;
            default:
                $data = array('resultCode' => 403006, 'message' => 'Page not found');
        }

        return $data;
    }
}