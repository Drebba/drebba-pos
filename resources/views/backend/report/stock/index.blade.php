@extends('backend.layouts.app')
@section('title') Stock Report @endsection
@section('content')
<!-- Begin Page Content -->
<div class="container-fluid settings-page">

    <div class="mb-3 d-flex justify-content-between align-items-center">
        <div class="btn-group btn-group-justified nav-buttons" role="group" aria-label="Basic example">
            <a href="{{url('report/stock-report')}}" class="btn btn-sm btn-outline-primary px-2 px-md-3 {{ active_if_full_match('report/stock-report') }}"><i class="fas fa-money-check me-1"></i> Stock Summary </a>
        </div>

        <div class="btn-group btn-group-sm filter-pdf-btn custom-btn-group" role="group">
            <form action="{{url('report/stock-report-pdf')}}" method="get">
                <input type="hidden" name="action_type" value="download">
                <button type="submit" class="btn btn-sm btn-brand-danger rounded-end-0 px-2 px-md-3"><i class="fas fa-file-download me-1"></i> {{__('pages.pdf')}} </button>
            </form>

            <form action="{{url('report/stock-report-pdf')}}" method="get" target="_blank">
                <input type="hidden" name="action_type" value="print">
                <button type="submit" class="btn btn-sm btn-brand-warning rounded-0 px-2 px-md-3"><i class="fa fa-print me-1"></i> {{__('pages.print')}} </button>
            </form>

            <form action="{{url('report/stock-report-pdf')}}" method="get">
                <input type="hidden" name="action_type" value="csv">
                <button type="submit" class="btn btn-sm btn-brand-success rounded-start-0 px-2 px-md-3"><i class="fa fa-file-csv me-1"></i> {{__('pages.csv')}} </button>
            </form>
        </div>
    </div>


    <div class="wiz-card">
        <div class="wiz-card-header">
            <h5 class="wiz-card-title">Stock Report</h5>
        </div>
        <div class="wiz-card-body">

            <div class="table-responsive" @can('access_to_all_branch') @endcan>
                <table class="table table-bordered table-hover text-center wiz-table mw-col-width-skip-first">
                    <thead>
                    <tr>
                        <th style="width: 60px">{{__('pages.sl')}}</th>
                        <th>{{__('pages.product')}}</th>
                        <th>{{__('pages.purchase')}} {{__('pages.quantity')}}</th>
                        <th>{{__('pages.sells')}} {{__('pages.quantity')}}</th>
                        <th>{{__('pages.current_stock_quantity')}}</th>
                        <th>{{__('pages.purchase_values')}} <sub>({{__('pages.apx')}})</sub></th>
                        <th>{{__('pages.current_stock_value')}} <sub>({{__('pages.apx')}})</sub></th>
                    </tr>
                    </thead>
                    <tbody>


                    @php
                        $grand_total_purchase = 0;
                        $grand_total_sell_value = 0;
                    @endphp

                    @foreach($products as $key => $product)

                            @php
                                $current_stock_qty =  $product->current_stock_quantity;
                            @endphp


                        <tr class="@if($current_stock_qty < 1 ) bg-danger text-white @elseif($current_stock_qty < 20) bg-warning text-white @else  @endif">
                            <td>{{$key+1}}</td>
                            <td>{{$product->title}} | {{$product->sku}}</td>
                            <td>

                                    {{$product->total_purchase_qty}} {{$product->unit->title ?? ''}}
                            </td>

                            <td>

                                    {{$product->total_sell_qty}} {{$product->unit->title ?? ''}}
                            </td>

                            <td>
                                {{$current_stock_qty}} {{$product->unit->title ?? ''}}
                            </td>

                            <td>

                                    @php
                                        $total_purchase = $product->purchaseProducts->where('business_id', Auth::user()->business_id)->sum('total_price');
                                    @endphp

                                {{get_option('app_currency')}}{{number_format($total_purchase, 2)}}
                            </td>

                            <td>
                                @php
                                    $product_tax = $product->sell_price * $product->tax->value / 100;
                                    $current_stock_amount = $current_stock_qty * ($product->sell_price);
                                @endphp

                                {{get_option('app_currency')}}{{number_format($current_stock_amount,2)}}
                            </td>
                        </tr>

                        @php
                            $grand_total_purchase += $total_purchase;
                            $grand_total_sell_value += $current_stock_amount;
                        @endphp

                    @endforeach

                    <tr>
                        <td colspan="5" class="text-right"><b> {{__('pages.total')}}</b></td>
                        <td><b> {{get_option('app_currency')}}{{number_format($grand_total_purchase, 2)}}</b></td>
                        <td><b> {{get_option('app_currency')}}{{number_format($grand_total_sell_value, 2)}}</b></td>
                    </tr>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- /.container-fluid -->
@endsection

@section('css')
    <link rel="stylesheet" type="text/css" href="{{asset('admin/plugin/select2/select2.min.css')}}" />
    <link rel="stylesheet" type="text/css" href="{{asset('admin/plugin/select2/select2-bootstrap/select2-bootstrap-5-theme.css')}}" />
@endsection
@section('js')
    <script src="{{asset('admin/plugin/select2/select2.min.js')}}"></script>
    <script>
        $('.select2-basic').select2({
            theme: "bootstrap-5",
            width: $( this ).data( 'width' ) ? $( this ).data( 'width' ) : $( this ).hasClass( 'w-100' ) ? '100%' : 'style',
            placeholder: $( this ).data( 'placeholder' ),
        });
    </script>
@endsection
