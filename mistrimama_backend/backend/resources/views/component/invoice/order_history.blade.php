<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Mistri Mama Statment</title>
        <style type="text/css">
            table {
                width: 100%;
                border-collapse: collapse;
                border-spacing: 0;
            }
            @page {
                margin: 10px;
                footer: html_mySignatureNSealFooter;
            }
        </style>
    </head>
    <body>
        <header class="clearfix">
            <table border="0" style="margin-bottom: 30px;">
                <tr>
                    <td align="left" style="width: 40%;;"><strong>Mistri Mama.</strong></td>
                    <td rowspan="4" align="center" style="width: 30%;;">
                        <img style="margin-left: 40px;" src="{{ env('APP_URL').'/public/frontend/logo.png' }}" width="100px" />
                    </td>
                </tr>
                <tr>
                    <td align="left"><img src="{{ env('APP_URL').'/public/frontend/image/phone-icon.png' }}" width="16px" />+8809610-222-111</td>
                </tr>
                <tr>
                    <td align="left">2nd floor, UCEP Cheyne Tower,</td>
                </tr>
                <tr>
                    <td align="left">25 Segun Bagicha Rd, Dhaka 1000</td>
                </tr>
            </table>
            <h3 style="text-align: center; margin-bottom: 30px; border-top: 1px solid #000; border-bottom: 1px solid #000; padding: 5px 0;">Job History</h3>
        </header>
        @if(!empty($orders))
        <main>
            <table style="margin-bottom: 20px; border-bottom: 1px solid #000;">
                <thead>
                    <tr style="background-color: #000;">
                        <th style="font-size: 12px; color: #fff; padding: 5px 5px;" align="left">Sl</th>
                        <th style="font-size: 12px; color: #fff; padding: 5px 5px;">Job No</th>
                        <th style="font-size: 12px; color: #fff; padding: 5px 5px;">Unit</th>
                        <th style="font-size: 12px; color: #fff; padding: 5px 15px;">Unit Price</th>
                        @if($item->emergency_charge > 0)
                        <th style="font-size: 12px; color: #fff; padding: 5px 15px;">Emergency Hour Charge</th>
                        @endif
                        @if($item->outside_charge > 0)
                        <th style="font-size: 12px; color: #fff; padding: 5px 15px;">Outside DMC Charge</th>
                        @endif
                        <th style="font-size: 12px; color: #fff; padding: 5px 15px;">Discount Amount</th>
                        <th style="font-size: 12px; color: #fff; padding: 5px 15px;">Billed Amount</th>
                        <th style="font-size: 12px; color: #fff; padding: 5px 5px;" align="right">TOTAL</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $key => $item)
                    <tr>
                        <td style="font-size: 10px;">{{ $key+1 }}</td>
                        <td style="font-size: 10px;" align="center">{{ $item->order_no }}</td>
                        <td style="font-size: 10px;" align="center">{{ $item->total_unit }}</td>
                        <td style="font-size: 10px;" align="center">{{ $item->total_unit_price }}</td>
                        @if($item->emergency_charge > 0)
                        <td style="font-size: 10px;" align="center">{{ number_format($item->emergency_charge, 0,'.',',') }}</td>
                        @endif
                        @if($item->outside_charge > 0)
                        <td style="font-size: 10px;" align="center">{{ number_format($item->outside_charge, 0,'.',',') }}</td>
                        @endif
                        <td style="font-size: 10px;" align="center">{{ number_format($item->discount, 0,'.',',') }}</td>
                        <td style="font-size: 10px;" align="center">{{ number_format($item->total_price, 0,'.',',') }}</td>
                        <td style="font-size: 10px;" align="right">{{ number_format($item->grant_total, 0,'.',',') }}</td>
                    </tr>
                    @endforeach
                    <tr>
                        <td colspan="11">&nbsp;</td>
                    </tr>
                </tbody>
            </table>
        </main>
        @endif
        <htmlpagefooter name="mySignatureNSealFooter">
            <p style="text-align: center"><em>Invoice was created on a computer and is valid without the signature and seal.</em></p>
        </htmlpagefooter>
    </body>
</html>
