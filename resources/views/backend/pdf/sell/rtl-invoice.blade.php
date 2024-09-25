<!DOCTYPE html>
@if(session()->get('is_rtl_support') == 'yes')
    <html dir="rtl" lang="{{app()->getLocale()}}">
    @else
        <html lang="{{app()->getLocale()}}">
        @endif
        <head>
            <title>Sell Invoice</title>
            <meta charset="UTF-8">
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

            @include('backend.pdf.layouts.css')
            <style>
                body {
                    font-family: 'Arial', sans-serif;

                }

                /* RTL specific styles */
                html[dir="rtl"] {
                    text-align: right !important;
                }

                html[dir="rtl"] .clearfix {
                    /* Adjust for floated elements in RTL */
                    clear: both;
                }

                html[dir="rtl"] .table {
                    /* Adjust table layout for RTL if needed */
                    direction: rtl;
                }

                html[dir="rtl"] .table th,
                html[dir="rtl"] .table td {
                    text-align: right !important;
                }

                /* Add additional RTL specific styles as needed */
            </style>
        </head>
        <body>
        @include('backend.pdf.layouts.invoice-header')

        <main>
            <div id="details" class="clearfix" style="border-bottom: 1px solid #AAAAAA">
                <div style="width: 50%;float: left">
                    <p style="line-height: 1px;margin-bottom: 5px">{{__('pages.customer_name')}} : {{$sell->customer->name}}</p>
                    <p style="margin-top: 0px">{{__('pages.customer_phone')}}: {{$sell->customer->phone}}</p>
                </div>

                <div style="width: 50%;float: left;text-align: right">
                    <p style="line-height: 1px;margin-bottom: 5px">{{__('pages.invoice_id')}}: {{$sell->invoice_id}}</p>
                    <p style="margin-top: 0px">{{__('pages.date')}}
                        : {{\Carbon\Carbon::parse($sell->created_at)->format(get_option('app_date_format'))}} {{\Carbon\Carbon::parse($sell->created_at)->format('h:i:A')}}</p>
                </div>
            </div>


            <div class="table-responsive">
                <table class="table" width="100%" cellspacing="0">
                    <thead>
                    <tr class="bg-secondary text-white">
                        <th style="text-align: right; color: white;">{{__('pages.sl')}}</th>
                        <th style="text-align: right; color: white;">{{__('pages.product_title')}}</th>
                        <th style="text-align: right; color: white;">{{__('pages.unit_price')}}</th>
                        <th style="text-align: right; color: white;">{{__('pages.tax')}}</th>
                        <th style="text-align: right; color: white;">{{__('pages.quantity')}}</th>
                        <th style="text-align: right; color: white;">{{__('pages.total_price')}}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($sell->sellProducts as $key => $sell_product)
                        <tr>
                            <td width="6%" style="text-align: right;">{{$key+1}}</td>
                            <td style="text-align: right;">
                                {{$sell_product->product->title}}
                            </td>
                            <td style="text-align: right;"> {{get_option('app_currency')}} {{number_format($sell_product->sell_price, 2)}} </td>
                            <td style="text-align: right;"> {{get_option('app_currency')}} {{number_format($sell_product->tax_amount, 2)}} </td>
                            <td style="text-align: right;"> {{$sell_product->quantity}}  {{$sell_product->product->unit ? $sell_product->product->unit->title : ''}} </td>
                            <td style="text-align: right;"> {{get_option('app_currency')}} {{number_format($sell_product->total_price, 2)}} </td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="4"></td>
                        <td class="pr-3" style="text-align: right;">
                            {{__('pages.sub_total')}}:
                        </td>
                        <td style="text-align: right;">
                            {{get_option('app_currency')}} {{number_format($sell->sub_total,2)}}
                        </td>
                    </tr>

                    <tr>
                        <td colspan="4"></td>
                        <td style="text-align: right;" class="text-right pr-3">
                            {{__('pages.discount')}}:
                        </td>
                        <td style="text-align: right;">
                            {{get_option('app_currency')}} {{number_format($sell->discount,2)}}
                        </td>
                    </tr>

                    <tr>
                        <td colspan="4"></td>
                        <td style="text-align: right;" class="text-right pr-3">
                            {{__('pages.grand_total')}}:
                        </td>
                        <td style="text-align: right;">
                            {{get_option('app_currency')}} {{number_format($sell->grand_total_price,2)}}
                        </td>
                    </tr>

                    <tr>
                        <td colspan="4"></td>
                        <td style="text-align: right;" class="text-right pr-3">
                            <strong> {{__('pages.paid_amount')}}:</strong>
                        </td>
                        <td style="text-align: right;"><strong>{{get_option('app_currency')}} {{number_format($sell->paid_amount, 2)}}</strong></td>
                    </tr>


                    <tr>
                        <td colspan="4"></td>
                        <td style="text-align: right;" class="text-left pr-3">
                            {{__('pages.due_amount')}}:
                        </td>
                        <td style="text-align: right;" class="text-danger">
                            {{get_option('app_currency')}} {{number_format($sell->due_amount,2)}}
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </main>

        @include('backend.pdf.layouts.footer')
        </body>
        </html>
