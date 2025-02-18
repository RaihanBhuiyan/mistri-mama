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
                footer: html_mySignatureNSealFooter;
            }
        </style>
    </head>
    <body>
        <header class="clearfix">
            <table border="0" style="margin-bottom: 30px;">
                <tr>
                    <td rowspan="4" style="width: 30%;"></td>
                    <td rowspan="4" align="center" style="width: 30%;;">
                        <img style="margin-left: 40px;" src="{{ env('APP_URL').'/public/frontend/logo.png' }}" width="100px" />
                    </td>
                    <td align="left" style="width: 40%;;"><strong>Mistri Mama.</strong></td>
                </tr>
                <tr>
                    <td align="left"><img src="{{ env('APP_URL').'/public/frontend/image/phone-icon.png' }}" width="16px" />+8809610-222-111</td>
                </tr>
                <tr>
                    <td align="left">Sky View Ocean Tower (1st floor),</td>
                </tr>
                <tr>
                    <td align="left">38 Segunbagicha, Dhaka, Bangladesh</td>
                </tr>
            </table>
            <h3 style="text-align: center; margin-bottom: 30px; border-top: 1px solid #000; border-bottom: 1px solid #000; padding: 5px 0;">CASHOUT HISTORY</h3>
        </header>
        @if(!empty($cashout_histories) && (count($cashout_histories) > 0))
        <main>
            <table style="margin-bottom: 20px; border-bottom: 1px solid #000;">
                <thead>
                    <tr style="background-color: #000;">
                        <th style="font-size: 12px; color: #fff; padding: 5px 5px;" align="left">SL</th>
                        <th style="font-size: 12px; color: #fff; padding: 5px 5px;" align="left">MFS Type</th>
                        <th style="font-size: 12px; color: #fff; padding: 5px 5px;" align="left">MFS Number</th>
                        <th style="font-size: 12px; color: #fff; padding: 5px 5px;" align="left">Trxn ID</th>
                        <th style="font-size: 12px; color: #fff; padding: 5px 5px;" align="right">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cashout_histories as $key => $item)
                    <tr>
                        <td style="font-size: 10px;">{{ $key+1 }}</td>
                        <td style="font-size: 10px;">{{ $item->mfs }}</td>
                        <td style="font-size: 10px;">{{ $item->mfs_number }}</td>
                        <td style="font-size: 10px;">{{ $item->accountWithdraw->trxno }}</td>
                        <td style="font-size: 10px;" align="right">{{ number_format($item->amount, 0,'.',',') }}</td>
                    </tr>
                    @endforeach
                    <tr>
                        <td colspan="5">&nbsp;</td>
                    </tr>
                </tbody>
            </table>
        </main>
        @endif
        <htmlpagefooter name="mySignatureNSealFooter">
            <em>Cashout history was created on a computer and is valid without the signature and seal.</em>
        </htmlpagefooter>
    </body>
</html>
