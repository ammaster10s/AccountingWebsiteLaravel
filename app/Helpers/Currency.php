<?php
/**
 * Created by PhpStorm.
 * User: HP
 * Date: 11/12/2018
 * Time: 11:21 AM
 */

namespace App\Helpers;


class Currency
{
    public function bahtEng($thb)
    {
        $thb = Currency::engFormat(intval($thb)) . ' Baht';
        return $thb;
    }

    // ตัวเลขเป็นตัวหนังสือ (ไทย)
    public function bahtThai($thb)
    {
        list($thb, $ths) = explode('.', $thb);
        $ths = substr($ths . '00', 0, 2);
        $thaiNum = array('', 'หนึ่ง', 'สอง', 'สาม', 'สี่', 'ห้า', 'หก', 'เจ็ด', 'แปด', 'เก้า');
        $unitBaht = array('บาท', 'สิบ', 'ร้อย', 'พัน', 'หมื่น', 'แสน', 'ล้าน', 'สิบ', 'ร้อย', 'พัน', 'หมื่น', 'แสน', 'ล้าน');
        $unitSatang = array('สตางค์', 'สิบ');
        $THB = '';
        $j = 0;
        for ($i = strlen($thb) - 1; $i >= 0; $i--, $j++) {
            $num = $thb[$i];
            $tnum = $thaiNum[$num];
            $unit = $unitBaht[$j];
            switch (true) {
                case $j == 0 && $num == 1 && strlen($thb) > 1:
                    $tnum = 'เอ็ด';
                    break;
                case $j == 1 && $num == 1:
                    $tnum = '';
                    break;
                case $j == 1 && $num == 2:
                    $tnum = 'ยี่';
                    break;
                case $j == 6 && $num == 1 && strlen($thb) > 7:
                    $tnum = 'เอ็ด';
                    break;
                case $j == 7 && $num == 1:
                    $tnum = '';
                    break;
                case $j == 7 && $num == 2:
                    $tnum = 'ยี่';
                    break;
                case $j != 0 && $j != 6 && $num == 0:
                    $unit = '';
                    break;
            }
            $S = $tnum . $unit;
            $THB = $S . $THB;
        }
        if ($ths == '00') {
            $THS = 'ถ้วน';
        } else {
            $j = 0;
            $THS = '';
            for ($i = strlen($ths) - 1; $i >= 0; $i--, $j++) {
                $num = $ths[$i];
                $tnum = $thaiNum[$num];
                $unit = $unitSatang[$j];
                switch (true) {
                    case $j == 0 && $num == 1 && strlen($ths) > 1:
                        $tnum = 'เอ็ด';
                        break;
                    case $j == 1 && $num == 1:
                        $tnum = '';
                        break;
                    case $j == 1 && $num == 2:
                        $tnum = 'ยี่';
                        break;
                    case $j != 0 && $j != 6 && $num == 0:
                        $unit = '';
                        break;
                }
                $S = $tnum . $unit;
                $THS = $S . $THS;
            }
        }
        return $THB . $THS;
    }

    // ตัวเลขเป็นตัวหนังสือ (eng)
    private function engFormat($number)
    {
        $string = '';
        $suffix = '';
        $max_size = pow(10, 18);
        if (!$number)
            return "zero";
        if (is_int($number) && $number < abs($max_size)) {
            switch ($number) {
                case $number < 0:
                    $prefix = "negative";
                    $suffix = Currency::engFormat(-1 * $number);
                    $string = $prefix . " " . $suffix;
                    break;
                case 1:
                    $string = "one";
                    break;
                case 2:
                    $string = "two";
                    break;
                case 3:
                    $string = "three";
                    break;
                case 4:
                    $string = "four";
                    break;
                case 5:
                    $string = "five";
                    break;
                case 6:
                    $string = "six";
                    break;
                case 7:
                    $string = "seven";
                    break;
                case 8:
                    $string = "eight";
                    break;
                case 9:
                    $string = "nine";
                    break;
                case 10:
                    $string = "ten";
                    break;
                case 11:
                    $string = "eleven";
                    break;
                case 12:
                    $string = "twelve";
                    break;
                case 13:
                    $string = "thirteen";
                    break;
                case 15:
                    $string = "fifteen";
                    break;
                case $number < 20:
                    $string = Currency::engFormat($number % 10);
                    if ($number == 18) {
                        $suffix = "een";
                    } else {
                        $suffix = "teen";
                    }
                    $string .= $suffix;
                    break;
                case 20:
                    $string = "twenty";
                    break;
                case 30:
                    $string = "thirty";
                    break;
                case 40:
                    $string = "forty";
                    break;
                case 50:
                    $string = "fifty";
                    break;
                case 60:
                    $string = "sixty";
                    break;
                case 70:
                    $string = "seventy";
                    break;
                case 80:
                    $string = "eighty";
                    break;
                case 90:
                    $string = "ninety";
                    break;
                case $number < 100:
                    $prefix = Currency::engFormat($number - $number % 10);
                    $suffix = Currency::engFormat($number % 10);
                    $string = $prefix . "-" . $suffix;
                    break;
                case $number < pow(10, 3):
                    $prefix = Currency::engFormat(intval(floor($number / pow(10, 2)))) . " hundred";
                    if ($number % pow(10, 2))
                        $suffix = " and " . Currency::engFormat($number % pow(10, 2));
                    $string = $prefix . $suffix;
                    break;
                case $number < pow(10, 6):
                    $prefix = Currency::engFormat(intval(floor($number / pow(10, 3)))) . " thousand";
                    if ($number % pow(10, 3))
                        $suffix = Currency::engFormat($number % pow(10, 3));
                    $string = $prefix . " " . $suffix;
                    break;
                case $number < pow(10, 9):
                    $prefix = Currency::engFormat(intval(floor($number / pow(10, 6)))) . " million";
                    if ($number % pow(10, 6))
                        $suffix = Currency::engFormat($number % pow(10, 6));
                    $string = $prefix . " " . $suffix;
                    break;
                case $number < pow(10, 12):
                    $prefix = Currency::engFormat(intval(floor($number / pow(10, 9)))) . " billion";
                    if ($number % pow(10, 9))
                        $suffix = Currency::engFormat($number % pow(10, 9));
                    $string = $prefix . " " . $suffix;
                    break;
                case $number < pow(10, 15):
                    $prefix = Currency::engFormat(intval(floor($number / pow(10, 12)))) . " trillion";
                    if ($number % pow(10, 12))
                        $suffix = Currency::engFormat($number % pow(10, 12));
                    $string = $prefix . " " . $suffix;
                    break;
                case $number < pow(10, 18):
                    $prefix = Currency::engFormat(intval(floor($number / pow(10, 15)))) . " quadrillion";
                    if ($number % pow(10, 15))
                        $suffix = Currency::engFormat($number % pow(10, 15));
                    $string = $prefix . " " . $suffix;
                    break;
            }
        }
        return $string;
    }
}