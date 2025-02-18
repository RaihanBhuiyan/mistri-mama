<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Mistri Mama Invoice</title>
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
            <h3 style="text-align: center; margin-bottom: 30px; border-top: 1px solid #000; border-bottom: 1px solid #000; padding: 5px 0;">INVOICE</h3>
            <table style="margin-bottom: 30px;">
                <tr>
                    <td align="left" style="width: 60px">Client:</td>
                    <td align="left"><strong>{{ $order->name }}</strong></td>
                    <td align="left"></td>
                    <td align="left" style="width: 170px">Service Provider Name :</td>
                    <td align="left"><strong>{{ $order->serviceProvider->name }}</strong></td>
                </tr>
                <tr>
                    <td align="left">Address:</td>
                    <td align="left">{{ $order->address }}</td>
                    <td align="left"></td>
                    <td align="left">Order Number :</td>
                    <td align="left">{{ $order->order_no }}</td>
                </tr>
                <tr>
                    <td align="left">Area:</td>
                    <td align="left">{{ $order->area }}</td>
                    <td align="left"></td>
                    <td align="left">Order Date :</td>
                    <td align="left">{{ date('d F Y', strtotime($order->date)) }}</td>
                </tr>
            </table>
        </header>
        @if(!empty($order->orderItems))
        <main>
            <table style="margin-bottom: 20px; border-bottom: 1px solid #000;">
                <thead>
                    <tr style="background-color: #000;">
                        <th style="font-size: 12px; color: #fff; padding: 5px 5px;" align="left">SERVICE</th>
                        <th style="font-size: 12px; color: #fff; padding: 5px 15px;">DESCRIPTION</th>
                        <th style="font-size: 12px; color: #fff; padding: 5px 15px;">QTY</th>
                        <th style="font-size: 12px; color: #fff; padding: 5px 15px;">PRICE</th>
                        <th style="font-size: 12px; color: #fff; padding: 5px 15px;">ADDTIONAL QTY</th>
                        <th style="font-size: 12px; color: #fff; padding: 5px 15px;">ADDTIONAL PRICE</th>
                        <th style="font-size: 12px; color: #fff; padding: 5px 5px;" align="right">TOTAL</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->orderItems as $item)
                    <tr>
                        <td>{{ $item->service_name }}</td>
                        <td>{{ $item->service_bit_name }}</td>
                        <td align="center">{{ $item->quantity }}</td>
                        <td align="center">{{ number_format($item->price, 0,'.',',') }}</td>
                        <td align="center">@if($item->quantity > 1) {{$item->quantity - 1}} @else N/A @endif</td>
                        <td align="center">@if($item->quantity > 1) {{$item->additional_price}} @else N/A @endif</td>
                        <td align="right">{{ number_format($item->total_price, 0,'.',',') }}</td>
                    </tr>
                    @endforeach
                    <tr>
                        <td colspan="7">&nbsp;</td>
                    </tr>
                </tbody>
            </table>
        </main>
        <table border="0" style="page-break-inside: avoid;">
            <tr>
                <td style="width: 300px;"></td>
                <td style="text-transform: uppercase; padding: 10px 0; border-top: 1px solid #000; border-bottom: 1px solid #000;"><strong>Subtotal</strong></td>
                <td align="right" style="border-top: 1px solid #000; border-bottom: 1px solid #000;"><strong> <img src="{{ env('APP_URL').'/public/frontend/image/bangladeshi-taka-currency-symbol.jpg' }}" width="14px" /> {{ number_format($order->total_price, 0,'.',',') }}</strong></td>
            </tr>
            <tr>
                <td colspan="3">&nbsp;</td>
            </tr>
            <tr>
                <td></td>
                <td style="text-transform: uppercase;">Emergency  Hour Charge<br>({{ $office_end_time }} to {{ $office_start_time }})</td>
                <td align="right">{{ number_format($order->emergency_charge, 0,'.',',') }}</td>
            </tr>
            <tr>
                <td></td>
                <td style="text-transform: uppercase;">Outside Dhaka Metro City Charge</td>
                <td align="right">{{ number_format($order->outside_charge, 0,'.',',') }}</td>
            </tr>
            <tr>
                <td></td>
                <td style="text-transform: uppercase;"><strong>Discount price</strong></td>
                <td align="right">-{{ number_format($order->discount, 0,'.',',') }}</td>
            </tr>
            <tr>
                <td colspan="3">&nbsp;</td>
            </tr>
            <tr>
                <td></td>
                <td style="text-transform: uppercase; padding: 15px 0; border-top: 1px solid #000; border-bottom: 1px solid #000;"><strong>Total</strong></td>
                <td align="right" style="border-top: 1px solid #000; border-bottom: 1px solid #000;"><strong> <img src="{{ env('APP_URL').'/public/frontend/image/bangladeshi-taka-currency-symbol.jpg' }}" width="14px" /> {{ number_format((($order->total_price + ($order->emergency_charge + $order->outside_charge)) - $order->discount), 0,'.',',') }}</strong></td>
            </tr>
        </table>
        @endif
        <htmlpagefooter name="mySignatureNSealFooter">
            <em>Invoice was created on a computer and is valid without the signature and seal.</em>
        </htmlpagefooter>
    </body>
</html>
