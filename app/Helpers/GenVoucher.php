<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;

class GenVoucher
{
    public function setNumberLength($num, $length)
    {
        $sumstr = strlen($num);
        $zero = str_repeat("0", $length - $sumstr);
        $results = $zero . $num;

        return $results;
    }

    public function DateFormat($val, $full)
    {
        $mon = "";
        $a = explode("-", $val);
        if ($full == "S" || $full == "s") {
            switch ($a[1]) {
                case "01" :
                    $mon = "Jan";
                    break;
                case "02" :
                    $mon = "Feb";
                    break;
                case "03" :
                    $mon = "Mar";
                    break;
                case "04" :
                    $mon = "Apr";
                    break;
                case "05" :
                    $mon = "May";
                    break;
                case "06" :
                    $mon = "Jun";
                    break;
                case "07" :
                    $mon = "Jul";
                    break;
                case "08" :
                    $mon = "Aug";
                    break;
                case "09" :
                    $mon = "Sep";
                    break;
                case "10" :
                    $mon = "Oct";
                    break;
                case "11" :
                    $mon = "Nov";
                    break;
                case "12" :
                    $mon = "Dec";
            }
        } else {
            switch ($a[1]) {
                case "01" :
                    $mon = "January";
                    break;
                case "02" :
                    $mon = "February";
                    break;
                case "03" :
                    $mon = "March";
                    break;
                case "04" :
                    $mon = "April";
                    break;
                case "05" :
                    $mon = "May";
                    break;
                case "06" :
                    $mon = "June";
                    break;
                case "07" :
                    $mon = "July";
                    break;
                case "08" :
                    $mon = "August";
                    break;
                case "09" :
                    $mon = "September";
                    break;
                case "10" :
                    $mon = "October";
                    break;
                case "11" :
                    $mon = "November";
                    break;
                case "12" :
                    $mon = "December";
            }
        }
        $value = "$a[2] $mon $a[0]";
        return $value;
    }

    public function DateFormatThai($val, $full)
    {
        $mon = "";
        $a = explode("/", $val);
        if ($full == "S" || $full == "s") {
            switch ($a[0]) {
                case "01" :
                    $mon = "ม.ค.";
                    break;
                case "02" :
                    $mon = "ก.พ.";
                    break;
                case "03" :
                    $mon = "มี.ค.";
                    break;
                case "04" :
                    $mon = "เม.ย.";
                    break;
                case "05" :
                    $mon = "พ.ค.";
                    break;
                case "06" :
                    $mon = "มิ.ย.";
                    break;
                case "07" :
                    $mon = "ก.ค.";
                    break;
                case "08" :
                    $mon = "ส.ค.";
                    break;
                case "09" :
                    $mon = "ก.ย.";
                    break;
                case "10" :
                    $mon = "ต.ค.";
                    break;
                case "11" :
                    $mon = "พ.ย.";
                    break;
                case "12" :
                    $mon = "ธ.ค.";
            }
        } else {
            switch ($a[0]) {
                case "01" :
                    $mon = "มกราคม";
                    break;
                case "02" :
                    $mon = "กุมภาพันธ์";
                    break;
                case "03" :
                    $mon = "มีนาคม";
                    break;
                case "04" :
                    $mon = "เมษายน";
                    break;
                case "05" :
                    $mon = "พฤษภาคม";
                    break;
                case "06" :
                    $mon = "มิถุนายน";
                    break;
                case "07" :
                    $mon = "กรกฎาคม";
                    break;
                case "08" :
                    $mon = "สิงหาคม";
                    break;
                case "09" :
                    $mon = "กันยายน";
                    break;
                case "10" :
                    $mon = "ตุลาคม";
                    break;
                case "11" :
                    $mon = "พฤศจิกายน";
                    break;
                case "12" :
                    $mon = "ธันวาคม";
            }
        }
        $year = $a[2] + 543;
        $value = "$a[1] $mon $year";
        return $value;
    }

    public function getDay($val)
    {
        switch ($val) {
            case "0" :
                $day = "Sun";
                break;
            case "1" :
                $day = "Mon";
                break;
            case "2" :
                $day = "Tue";
                break;
            case "3" :
                $day = "Wed";
                break;
            case "4" :
                $day = "Thu";
                break;
            case "5" :
                $day = "Fri";
                break;
            case "6" :
                $day = "Sat";
                break;
            default :
                $day = "";
        }

        return $day;
    }

    public function getMonth($val)
    {
        switch ($val) {
            case "01" :
                $mon = "January";
                break;
            case "02" :
                $mon = "February";
                break;
            case "03" :
                $mon = "March";
                break;
            case "04" :
                $mon = "April";
                break;
            case "05" :
                $mon = "May";
                break;
            case "06" :
                $mon = "June";
                break;
            case "07" :
                $mon = "July";
                break;
            case "08" :
                $mon = "August";
                break;
            case "09" :
                $mon = "September";
                break;
            case "10" :
                $mon = "October";
                break;
            case "11" :
                $mon = "November";
                break;
            case "12" :
                $mon = "December";
                break;
            default :
                $mon = "";
        }

        return $mon;
    }

    public function convertDateFormat($val)
    {
        $a = explode("/", $val);
        $value = $a[2] . "-" . $a[0] . "-" . $a[1];
        return $value;
    }

    public function showDateFormat($val)
    {
        $a = explode("-", $val);
        $value = $a[1] . "/" . $a[2] . "/" . $a[0];
        return $value;
    }

    public function get_value($table, $field_id, $field_name, $val)
    {
        $sql = DB::table($table)->select($field_id, $field_name)->where($field_id, $val)->get();
        foreach ($sql as $rows) {
            $value = $rows->$field_name;
            return $value;
        }
    }

    public function watermarkImageHeader($SourceFile, $WaterMarkText, $DestinationFile, $axis_x, $axis_y, $fontSize)
    {
        list($width, $height) = getimagesize($SourceFile);
        $image_p = imagecreatetruecolor($width, $height);
        $image = imagecreatefromjpeg($SourceFile);
        imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width, $height);
        $black = imagecolorallocate($image_p, 005, 0, 0);//Colour

        $font = base_path() . '/public/images/photo/voucher/psldisplay-webfont.ttf';
        $font_size = $fontSize; //Set font size.

        imagettftext($image_p, $font_size, 0, $axis_x, $axis_y, $black, $font, $WaterMarkText);

        if ($DestinationFile <> '') {
            imagejpeg($image_p, $DestinationFile, 100);
        } else {
            header('Content-Type: image/jpeg');
            imagejpeg($image_p, null, 100);
        }

        imagedestroy($image);
        imagedestroy($image_p);
    }

    public function watermarkImage($SourceFile, $WaterMarkText, $DestinationFile, $axis_x, $axis_y)
    {
        list($width, $height) = getimagesize($SourceFile);
        $image_p = imagecreatetruecolor($width, $height);
        $image = imagecreatefromjpeg($SourceFile);
        imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width, $height);
        $black = imagecolorallocate($image_p, 005, 0, 0);//Colour

        $font = base_path() . '/public/images/photo/voucher/psldisplay-webfont.ttf';
        $font_size = 18; //Set font size.

        imagettftext($image_p, $font_size, 0, $axis_x, $axis_y, $black, $font, $WaterMarkText);

        if ($DestinationFile <> '') {
            imagejpeg($image_p, $DestinationFile, 100);
        } else {
            header('Content-Type: image/jpeg');
            imagejpeg($image_p, null, 100);
        }

        imagedestroy($image);
        imagedestroy($image_p);
    }

    public function watermarkImageFontBold($SourceFile, $WaterMarkText, $DestinationFile, $axis_x, $axis_y)
    {
        list($width, $height) = getimagesize($SourceFile);
        $image_p = imagecreatetruecolor($width, $height);
        $image = imagecreatefromjpeg($SourceFile);
        imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width, $height);
        $black = imagecolorallocate($image_p, 005, 0, 0);//Colour

        $font = base_path() . '/public/images/photo/voucher/monofont.ttf';
        $font_size = 18; //Set font size.

        imagettftext($image_p, $font_size, 0, $axis_x, $axis_y, $black, $font, $WaterMarkText);

        if ($DestinationFile <> '') {
            imagejpeg($image_p, $DestinationFile, 100);
        } else {
            header('Content-Type: image/jpeg');
            imagejpeg($image_p, null, 100);
        }

        imagedestroy($image);
        imagedestroy($image_p);
    }
}