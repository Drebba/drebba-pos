@extends('backend.layouts.app')
@section('title') {{__('pages.sells')}} @endsection
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">


        <div class="wiz-box d-flex justify-content-between align-items-center mb-3">
            <h6 class="page-title mb-0">{{__('pages.sell_details')}}</h6>
            <div class="btn-group btn-group-sm custom-btn-group" role="group">
                <a href="{{route('sell.edit', [$sell->id])}}" class="btn btn-brand-primary rounded-0"><i class="bi bi-pencil me-1"></i> {{__('pages.edit')}} </a>
                <a href="{{url('export/sell/invoice/id='.encrypt($sell->id).'/type=1')}}" class="btn btn-brand-secondary"><i class="fa fa-download me-1"></i> {{__('pages.pdf')}} </a>
                <a href="{{url('export/sell/invoice/id='.encrypt($sell->id).'/type=2')}}" target="_blank" class="btn btn-brand-warning"><i class="fa fa-print me-1"></i> {{__('pages.print_invoice')}} </a>
            </div>
        </div>

        <div class="row g-3 mb-3">
            <div class="col-md-4">
                <div class="wiz-card">
                    <div class="wiz-card-body">
                        <table class="table table-bordered table-sm wiz-table mb-0">
                            <thead>
                            <tr class="bg-secondary text-white text-center">
                                <th colspan="2">{{__('pages.customer')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>{{__('pages.full_name')}}</td>
                                <td>{{$sell->customer->name}}</td>
                            </tr>

                            <tr>
                                <td>{{__('pages.phone_number')}}</td>
                                <td>{{$sell->customer->phone}}</td>
                            </tr>

                            <tr>
                                <td>{{__('pages.email')}}</td>
                                <td>{{$sell->customer->email}}</td>
                            </tr>

                            <tr>
                                <td>{{__('pages.address')}}</td>
                                <td>{{$sell->customer->address}}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="wiz-card">
                    <div class="wiz-card-body">
                        <table class="table table-bordered table-sm wiz-table mb-0">
                    <thead>
                    <tr class="bg-primary text-white text-center">
                        <th colspan="2">{{__('pages.sell_details')}}</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr width="50px">
                        <td>{{__('pages.invoice_id')}}</td>
                        <td>{{$sell->invoice_id}}</td>
                    </tr>

                    <tr>
                        <td>{{__('pages.sell_date')}}</td>
                        <td>
                            @formatdate($sell->sell_date) {{\Carbon\Carbon::parse($sell->created_at)->format('h:i:A')}}
                        </td>
                    </tr>

                    <tr>
                        <td>{{__('pages.sold_by')}}</td>
                        <td>{{$sell->user->name}}</td>
                    </tr>
                    </tbody>
                </table>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="wiz-card">
                    <div class="wiz-card-body">
                        <table class="table table-bordered table-sm wiz-table mb-0">
                    <thead>
                    <tr class="bg-secondary text-white text-center">
                        <th colspan="2">{{__('pages.sell_summary')}}</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr width="50px">


                    <tr>
                        <td>{{__('pages.sub_total')}}</td>
                        <td>{{get_option('app_currency')}}{{number_format($sell->sub_total, 2)}}</td>
                    </tr>

                    <tr>
                        <td>{{__('pages.discount')}}</td>
                        <td>{{get_option('app_currency')}}{{number_format($sell->discount, 2)}}</td>
                    </tr>

                    <tr>
                        <td>{{__('pages.grand_total')}}</td>
                        <td>{{get_option('app_currency')}}{{number_format($sell->grand_total_price, 2)}}</td>
                    </tr>
                    <tr>
                        <td>{{__('pages.paid_amount')}}</td>
                        <td><strong>{{get_option('app_currency')}}{{number_format($sell->paid_amount, 2)}}</strong></td>
                    </tr>

                    <tr>
                        <td>{{__('pages.due_amount')}}</td>
                        <td class="text-danger">{{get_option('app_currency')}}{{number_format($sell->due_amount, 2)}}</td>
                    </tr>


                    </tbody>
                </table>
                    </div>
                </div>
            </div>
        </div>


        <div class="mb-3">
            <div class="wiz-box">
                <div class="table-responsive">
                    <table class="table table-bordered text-center wiz-table mb-0">
                        <thead>
                        <tr class="bg-secondary text-white">
                            <th>{{__('pages.sl')}}</th>
                            <th class="text-nowrap">{{__('pages.product_title')}}</th>
                            <th class="text-nowrap">{{__('pages.unit_price')}}</th>
                            <th class="text-nowrap">{{__('pages.tax')}}</th>
                            <th class="text-nowrap col-md-3 col-xl-2">{{__('pages.quantity')}}</th>
                            <th class="text-nowrap col-md-3 col-xl-2">{{__('pages.total_trice')}}</th></tr>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($sell->sellProducts as $key => $sell_product)
                            <tr>
                                <td width="5%">{{$key+1}}</td>
                                <td>
                                    {{$sell_product->product->title}}
                                </td>
                                <td> {{get_option('app_currency')}}{{number_format($sell_product->sell_price, 2)}} </td>
                                <td> {{get_option('app_currency')}}{{number_format($sell_product->tax_amount, 2)}} </td>
                                <td> {{$sell_product->quantity}} {{$sell_product->product->unit->title ?? ''}} </td>
                                <td> {{get_option('app_currency')}}{{number_format($sell_product->total_price, 2)}} </td>
                            </tr>
                        @endforeach


                        </tbody>
                    </table>
                </div>

                <div class="col-md-6 col-xl-4 ms-auto">
                    <table class="table table-bordered wiz-table">
                        <tbody>
                        <tr class="border-top-0">
                            <td class="text-md-center col-6">
                                <span class="fw-medium">{{__('pages.sub_total')}}:</span>
                            </td>
                            <td class="text-end text-md-center">
                                <span class="fw-medium">{{get_option('app_currency')}}{{number_format($sell->sub_total,2)}}</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-md-center">
                                <span class="fw-medium">{{__('pages.discount')}}:</span>
                            </td>
                            <td class="text-end text-md-center">
                                <span class="fw-medium">{{get_option('app_currency')}}{{number_format($sell->discount,2)}}</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-md-center">
                                <span class="fw-medium">{{__('pages.grand_total')}}:</span>
                            </td>
                            <td class="text-end text-md-center">
                                <span class="fw-medium">{{get_option('app_currency')}}{{number_format($sell->grand_total_price,2)}}</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-md-center">
                                <strong> {{__('pages.paid_amount')}}:</strong>
                            </td>
                            <td class="text-end text-md-center"><strong>{{get_option('app_currency')}}{{number_format($sell->paid_amount, 2)}}</strong></td>
                        </tr>
                        <tr>
                            <td class="text-md-center">
                                <span class="fw-medium">{{__('pages.due_amount')}}:</span>
                            </td>
                            <td class="text-end text-md-center">
                                <span class="fw-medium">{{get_option('app_currency')}} {{number_format($sell->due_amount,2)}}</span>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="wiz-card">
            <div class="wiz-card-header">
                <h4 class="wiz-card-title">{{__('pages.partial_payments')}}</h4>
            </div>
            <div class="wiz-card-body">
                <div class="table-responsive">

                    <table class="table table-bordered wiz-table">
                        <thead>
                        <tr class="bg-secondary text-white">
                            <th width="5%">{{__('pages.sl')}}</th>
                            <th class="text-center">{{__('pages.payment_date')}}</th>
                            <th class="text-center">{{__('pages.amount')}}</th>
                            <th class="text-center">{{__('pages.note')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($sell->paymentFromCustomers as $key => $payment)
                            <tr>
                                <td>{{$key+1}}</td>
                                <td class="text-center"> {{\Carbon\Carbon::parse($payment->payment_date)->toDateString()}} </td>
                                <td class="text-center"> {{get_option('app_currency')}} {{number_format($payment->amount, 2)}} </td>
                                <td class="text-center"> {{$payment->note ? $payment->note : '--'}} </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">No Data</td>
                            </tr>
                        @endforelse
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

