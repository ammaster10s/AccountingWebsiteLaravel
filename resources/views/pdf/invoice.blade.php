<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Online Invoice Template</title>
    <link rel="stylesheet" href="{{ asset('css/invoice.css') }}">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style>

        .description {
            margin-bottom: 20px; 
        }

        .paid-by {
            margin-top: 20px;
        }

        @font-face {
            font-family: 'THSarabunNew';
            font-style: normal;
            font-weight: normal;
            src: url("{{ asset('fonts/THSarabunNew.ttf') }}") format('truetype');
        }

        @font-face {
            font-family: 'THSarabunNew';
            font-style: normal;
            font-weight: bold;
            src: url("{{ asset('fonts/THSarabunNew Bold.ttf') }}") format('truetype');
        }

        @font-face {
            font-family: 'THSarabunNew';
            font-style: italic;
            font-weight: normal;
            src: url("{{ asset('fonts/THSarabunNew Italic.ttf') }}") format('truetype');
        }

        @font-face {
            font-family: 'THSarabunNew';
            font-style: italic;
            font-weight: bold;
            src: url("{{ asset('fonts/THSarabunNew BoldItalic.ttf') }}") format('truetype');
        }

        body {
            font-size: 14px;
        }

        #text-cancel {
            font-size: 48px;
            color: red;
        }

        .padding {
            padding: 10px;
        }

        .expand {
            padding: 20px 5px;
        }

        .text-right {
            text-align: right;
        }
        .text-left {
            text-align: left;
        }
    </style>
</head>

<body>

    <table width="100%">
        <tr>
            @if($status=='Y')
            <td colspan="2"><img src="images/company-logo.png" alt="logo" /></td>
            @else
            <td width="60%"><img src="images/company-logo.png" alt="logo" /></td>
            <td id="text-cancel" align="center">CANCEL</td>
            @endif
        </tr>
        <tr>
            <td width="55%" valign="top">
                <p>
                    <strong>S.S. ADV CO.,LTD.</strong><br />
                    <strong>T.A.T LICENSE NO. 12/00766</strong><br />
                    Address : 172 Khaosan Road. Taladyod Pranakorn,<br />
                    Bangkok Thailand 10200<br />
                    Tel : 02 629 3415, 02 282 0418<br />
                    Mobile : 081 917 5148 FAX : 02 282 3830<br />
                    E-mail : info@adv-tour.com<br />
                    TAX : 0105562175460
                </p>
            </td>
            <td width="45%" valign="top">
                <table width="100%" class="text-right">
                    @if($agentId!=0 && $agentId!=169)
                    @foreach($agent as $row)
                    <tr>
                        <td width="40%" valign="top" class="text-right">Agent :</td>
                        <td width="60%" class="text-left">{{ $row->ag_name }}</td>
                    </tr>
                    <tr>
                        <td valign="top" class="text-right">Address :</td>
                        <td  class="text-left">{{ $row->ag_address }}</td>
                    </tr>
                    <tr>
                        <td class="text-right">E-mail :</td>
                        <td  class="text-left">{{ $row->ag_email }}</td>
                    </tr>
                    <tr>
                        <td class="text-right">Phone :</td>
                        <td  class="text-left">{{ $row->ag_tel }}</td>
                    </tr>
                    @endforeach
                    @else
                    @foreach($order as $row)
                    <tr>
                        <td width="40%" class="text-right">Customer :</td>
                        <td width="60%">{{ $row['customer'] }}</td>
                    </tr>
                    <tr>
                        <td valign="top" class="text-right">Address :</td>
                        <td>{{ $row['address'] }}</td>
                    </tr>
                    <tr>
                        <td class="text-right">E-mail :</td>
                        <td>{{ $row['email'] }}</td>
                    </tr>
                    <tr>
                        <td class="text-right">Phone :</td>
                        <td>{{ $row['phone'] }}</td>
                    </tr>
                    @endforeach
                    @endif
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="2" align="center">
                <h2>RECEIPT</h2>
            </td>
        </tr>
        <tr>
            <td width="60%"></td>
            <td width="40%" valign="top">
                <table width="100%" class="text-right">
                    <tr>
                        <td width="40%" class="text-right"><strong>No :</strong></td>
                        <td width="60%" class="text-left"><strong>{{$invNo}}</strong></td>
                    </tr>
                    <tr>
                        <td class="text-right"><strong>Date :</strong></td>
                        <td class="text-left"><strong>{{ $date }}</strong></td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <table width="100%" class="description" border="1" cellspacing="0" cellpadding="1">
                    <thead>
                        <tr>
                            <th width="3%" class="padding">No</th>
                            <th width="67.5%" class="text-center padding">Description</th>
                            <th class="text-right padding">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="expand">1</td>
                            <td class="expand">Transportation costs.</td>
                            <td class="expand text-right">{{ $amount.'.-' }}</td>
                        </tr>
                    <tfoot>
                        <tr>
                            <th class="padding" colspan="2">Total : {{ $amountText }}</th>
                            <th class="padding text-right">{{ '-'.$amount.'-' }}</th>
                        </tr>
                    </tfoot>
                    </tbody>
                </table>
            </td>
        </tr>
    </table>

    <table width="100%">
        <tr>
            <td>
                <table width="100%" class="paid-by" border="1" cellspacing="0" cellpadding="1">
                    <tr>
                        <td>
                            <div class="total-paid">
                                <div class="padding paid-by">
                                    PAID BY :
                                </div>
                                <div class="padding text">
                                    (&nbsp;&nbsp;&nbsp;) CASH
                                </div>
                                <div class="padding text">
                                    (&nbsp;&nbsp;&nbsp;) CHEQUE BANK ...................................... BRANCH
                                    ............................ NO ................... DATE
                                    ................................
                                </div>
                            </div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <div class="total_service">
        <div class="service">
            <div class="service_line"></div>
            <p class="text_customer">CUSTOMER / SERVICE RECIPIENT</p>
        </div>
        <div class="bill">
            <div class="service_line"></div>
            <p class="text_bill">BILL COLLECTOR</p>
        </div>
    </div>
    <hr />

    <div class="pay-buttons">
        INCASE OF PAYMENT MADE BY CHEQUE THIS RECEIPT IS NOT VALID UNTIL CHEQUE HONOURED<br>
    </div>

</body>

</html>