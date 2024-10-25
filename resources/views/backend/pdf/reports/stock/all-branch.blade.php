<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Stock Report</title>
    @include('backend.pdf.layouts.css')
</head>
<body >
@include('backend.pdf.layouts.report-header')
<main>
    <div id="details" class="clearfix">
        <div id="client"class="mt-10">
            <h2 class="name">{{__('pages.stock_report')}}</h2>
            <div class="address">
                @can('access_to_all_branch')
                    {{__('pages.branch')}}: {{__('pages.all_branch')}},
                @else
                    {{__('pages.branch')}}: {{auth()->user()->employee->branch->title}},
                @endcan
                    {{__('pages.date')}}: {{\Carbon\Carbon::now()->format(get_option('app_date_format'))}}
            </div>
        </div>
    </div>


    <table class="table" width="100%" cellspacing="0">
        <thead>
        <tr class="bg-secondary text-white">
            <th width="2%">{{__('pages.sl')}}</th>
            <th width="25%">{{__('pages.product_title')}}</th>
            <th width="10%">{{__('pages.purchase')}}</th>
            <th width="10%">{{__('pages.sell')}} </th>
            <th width="15%">{{__('pages.current_stock_qty')}}</th>
            <th width="18%">{{__('pages.current_stock_amount')}} <sub>(Apx)</sub></th>
        </tr>
        </thead>
        <tbody>


        @foreach($products as $key => $product)
            <tr>
                <td>{{$key+1}}</td>
                <td>{{$product->title}} | {{$product->sku}}</td>
                <td>
                    @can('access_to_all_branch')
                        {{$product->purchaseProducts->sum('quantity')}}   {{$product->unit ? $product->unit->title : ''}}
                    @else
                        {{$product->purchaseProducts->where('business_id', Auth::user()->business_id)->sum('quantity')}} {{$product->unit ? $product->unit->title : ''}}
                    @endcan
                </td>

                <td>
                    @can('access_to_all_branch')
                        {{$product->sellProducts->sum('quantity')}} {{$product->unit ? $product->unit->title : ''}}
                    @else
                        {{$product->sellProducts->where('business_id', Auth::user()->business_id)->sum('quantity')}} {{$product->unit ? $product->unit->title : ''}}
                    @endcan
                </td>

                <td>
                        @php
                            $current_stock_qty = $product->purchaseProducts->sum('quantity') - $product->sellProducts->sum('quantity');
                        @endphp


                    {{$current_stock_qty}} {{$product->unit ? $product->unit->title : ''}}
                </td>
                <td>
                    @php
                        $product_tax = $product->sell_price * $product->tax->value / 100;
                        $current_stock_amount = $current_stock_qty * ($product->sell_price + $product_tax);
                    @endphp

                    {{get_option('app_currency')}} {{number_format($current_stock_amount,2)}}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>


</main>
@include('backend.pdf.layouts.footer')
</body>
</html>
