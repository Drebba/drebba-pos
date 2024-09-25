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

        <div class="wiz-box mb-3">
            @can('access_to_all_branch')
                <form action="{{url('report/stock-report/filter')}}" method="get">
                    <div class="d-flex gap-3">
                        <div class="flex-grow-1">
                            <div class="form-group text-left">
                                <select name="branch_id" class="form-control select2-basic">
                                    <option value="">{{__('pages.all_branch')}}</option>
                                    @foreach($branches as $branch)
                                        <option value="{{$branch->id}}" {{Request::get('branch_id') == $branch->id ? 'selected' : ''}}>{{$branch->title}} </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <button class="btn btn-brand-primary btn-brand">{{__('pages.search')}}</button>
                        </div>
                    </div>
                </form>
            @endcan
        </div>

        <div class="wiz-card">
            <div class="wiz-card-header">
                <h5 class="wiz-card-title">Stock Report</h5>
            </div>
            <div class="wiz-card-body">

                <div class="table-responsive">
                    <table class="table table-bordered table-hover text-center wiz-table mw-col-width-skip-first">
                        <thead>
                        <tr class="bg-secondary text-white">
                            <th width="2%">SL</th>
                            <th>Product Title</th>
                            <th width="10%">Purchase Qty</th>
                            <th width="15%">Received From Requisition</th>
                            <th width="10%">Sell Qty </th>
                            <th width="15%">Send To Requisition</th>
                            <th width="10%">Current Stock Qty</th>
                            <th width="15%">Sell Value <sub>(Apx)</sub></th>
                        </tr>
                        </thead>
                        <tbody>

                        @php
                            $grand_total_purchase = 0;
                            $grand_total_sell_value = 0;
                        @endphp

                        @foreach($product_requisitions as $key => $product)
                            <tr class="@if($product['current_stock'] < 1 ) bg-danger text-white @elseif($product['current_stock'] < 20) bg-warning text-white @else  @endif">
                                <td>{{$key+1}}</td>
                                <td>{{$product['product_title']}} | {{$product['product_sku']}}</td>
                                <td>{{$product['purchase_qty']}} {{$product['unit']}}</td>
                                <td>{{$product['branch_requisitions_from_qty']}} {{$product['unit']}}</td>
                                <td>{{$product['sell_qty']}} {{$product['unit']}}</td>
                                <td>{{$product['branch_requisitions_to_qty'] }} {{$product['unit']}}</td>
                                <td>{{$product['current_stock']}} {{$product['unit']}}</td>
                                <td>{{number_format($product['current_stock'] * $product['product_sell_price'], 2)}}</td>
                            </tr>

                            @php
                                $grand_total_sell_value += $product['current_stock'] * $product['product_sell_price'];
                            @endphp
                        @endforeach

                        <tr>
                            <td colspan="7" class="text-right"><b> {{__('pages.total')}}</b></td>
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

@section('js')

@endsection

