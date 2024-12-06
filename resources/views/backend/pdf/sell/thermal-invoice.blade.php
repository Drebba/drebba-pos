<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>E-BILL {{ $sell->invoice_id }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Afacad+Flux:wght@100..1000&family=Nunito+Sans:ital,opsz,wght@0,6..12,200..1000;1,6..12,200..1000&family=Silkscreen:wght@400;700&display=swap" rel="stylesheet">
    <style>
       #invoice-POS p,#invoice-POS h1,#invoice-POS h2,#invoice-POS h3,#invoice-POS h4,#invoice-POS h5,#invoice-POS h6,#invoice-POS div,#invoice-POS *,body,* {
            /* font-family: 'Nunito Sans', sans-serif!important; */
            font-family: "Silkscreen", sans-serif!important;
        }
        #invoice-POS {
            box-shadow: 0 0 1in -0.25in rgba(0, 0, 0, 0.5);
            padding: 2mm;
            margin: 0 auto;
            width: 80mm;
            background: #FFF;
            text-align: center;
        }

        #invoice-POS ::selection {
            background: #f31544;
            color: #FFF;
        }

        #invoice-POS ::moz-selection {
            background: #f31544;
            color: #FFF;
        }

        #invoice-POS h1 {
            font-size: 1.5em;
            color: #222;
        }

        #invoice-POS h2 {
            font-size: .9rem;
        }

        #invoice-POS h3 {
            font-size: 1.2em;
            font-weight: 300;
            line-height: 2em;
        }

        #invoice-POS p {
            color: #666;
            line-height: 1.2em;
        }

        #invoice-POS #top,
        #invoice-POS #mid,
        #invoice-POS #bot {
            /* Targets all id with 'col-' */
            border-bottom: 1px solid #EEE;
        }

        #invoice-POS #top {
            min-height: 100px;
        }

        #invoice-POS #mid {
            min-height: 80px;
        }

        #invoice-POS #bot {
            min-height: 50px;
        }

        #invoice-POS #top .logo {
            height: 60px;
            width: 60px;

        }


        #invoice-POS .info {
            display: block;
            margin-left: 0;
        }

        #invoice-POS .title {
            float: right;
        }

        #invoice-POS .title p {
            text-align: right;
        }

        #invoice-POS table {
            width: 100%;
            border-collapse: collapse;
        }

        #invoice-POS .tabletitle {
            font-size: 14px;
            color: #3f3e3e!important;
        }

        #invoice-POS .service {
            border-bottom: 1px solid #EEE;
        }

        #invoice-POS .item {
            width: 24mm;
        }


        #invoice-POS #legalcopy {
            margin-top: 5mm;
        }

        .text-center {
            text-align: center;
        }

        .bill {
            font-weight: 800;
            font-size: 21px;

        }

        .invoice_no {
            font-size: 14px;
        }

        .my-0 {
            margin: 0 !important;
        }

        .py-0 {
            padding: 0 !important;
        }

        .py-1 {
            padding: 4px 0 !important;
        }

        *,
        p,
        .itemtext {
            font-size: 12px !important;
            color: #666!important;
        }
    </style>
</head>

<body>




    <div id="invoice-POS">

        <!--End InvoiceTop-->
        @if (!$kot)
        <div id="mid">
            <div class="info">


                <div class="text-center">
                    <img src="{{asset(get_option('app_logo'))}}" alt="" style="margin: auto!important;width:60px">

                </div>
                <p class="text-center my-0 py-0">
                    {{get_option('address')}}
                    </br>

                    {{get_option('phone')}}

                    <br>

                    Date : {{ date('d M Y') }}
                </p>

                <p class="text-center my-0 py-0 py-1">
                    <span class="invoice_no">E-Bill : </span> <span class="bill ">{{ $sell->invoice_id }}</span>
                    <br>
                    @if ($sell->table)
                    <span class="invoice_no">Table : </span> <span class="bill ">{{ $sell->table->name }}</span>
                    @endif
                </p>

            </div>
        </div>
        @else
        <p class="text-center my-0 py-0 py-1">
            <span class="invoice_no">E-Bill : </span> <span class="bill ">{{ $sell->invoice_id }}</span>
            <br>
            @if ($sell->table)
            <span class="invoice_no">Table : </span> <span class="bill ">{{ $sell->table->name }}</span>
            @endif
        </p>
        @endif

        <!--End Invoice Mid-->
        <div id="bot">
            <div class="table-responsive">
                <table class="table w-100" width="100%">
                    <thead>
                        <tr>
                            <td colspan="4"></td>
                        </tr>
                        <tr>
                            <td colspan="4"></td>
                        </tr>
                    <tr class="w-100 text-white">
                        <th class="tabletitle">{{__('pages.sl')}}</th>
                        <th class="tabletitle">Name</th>
                        @if (!$kot)
                        <th class="tabletitle">Price</th>
                        @endif
                        <th class="tabletitle">Qty</th>
                        @if (!$kot)
                        <th class="tabletitle">SubTotal</th>
                        @endif
                    </tr>
                    </thead>
                    <tbody>

                    @foreach($sell->sellProducts as $key => $sell_product)
                        <tr>
                            <td width="3%" class="itemtext">{{$key+1}}</td>
                            <td class="itemtext">
                                {{$sell_product->product->title}}
                            </td>
                           @if (!$kot)
                            <td class="itemtext">  {{number_format($sell_product->sell_price,0)}} </td>
                            @endif
                            <td class="itemtext"> {{$sell_product->quantity}}  {{$sell_product->product->unit ? $sell_product->product->unit->title : ''}} </td>
                            @if (!$kot)
                            <td class="itemtext"> {{number_format($sell_product->total_price,0)}} </td>
                            @endif
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="4"></td>
                    </tr>
                    <tr>
                        <td colspan="4"></td>
                    </tr>
                    </tbody>
                    <tfoot>
                    @if (!$kot)
                    <tr style="border-top: 1px solid rgb(212, 211, 211);">
                        <th colspan="4" class="text-right pr-3">
                            {{__('pages.sub_total')}}:
                        </th>
                        <th>
                            {{get_option('app_currency')}} {{number_format($sell->sub_total,0)}}
                        </th>
                    </tr>

                    @if ($sell->discount)

                    <tr>
                        <th colspan="4" class="text-right pr-3">
                            {{__('pages.discount')}}:
                        </th>
                        <td>
                            {{get_option('app_currency')}} {{number_format($sell->discount,0)}}
                        </td>
                    </tr>
                    @endif

                    <tr>
                        <th colspan="4" class="text-right pr-3">
                            {{__('pages.grand_total')}}:
                        </th>
                        <th>
                            {{get_option('app_currency')}} {{number_format($sell->grand_total_price,0)}}
                        </th>
                    </tr>
                    @endif
                </tfoot>
                </table>
            </div>
            <!--End Table-->
            @if (!$kot)
            <div id="legalcopy">
                <p class="legal text-center my-0 py-0"><strong>Thank you for visiting us!
                    </strong>
                </p>
            </div>
          @endif
        </div>
        <!--End InvoiceBot-->
    </div>
    <!--End Invoice-->
</body>

</html>
