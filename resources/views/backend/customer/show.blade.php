@extends('backend.layouts.app')
@section('title') {{__('pages.customer')}} @endsection
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid profile">

        <div class="row g-3">
            <div class="col-md-3">
                <div class="wiz-card h-auto">
                    <div class="wiz-card-header">
                        <h5 class="wiz-card-title">Customer</h5>
                    </div>
                    <div class="wiz-card-body">
                        <div class="col-7 col-md-9 mx-auto p-0">
                            <div class="ratio ratio-1x1 mb-3">
                                <div class="avatar-box">
                                    <img src="{{asset($customer->photo ? $customer->photo : 'backend/img/user-placeholder.png')}}" class="img-fit-cover">
                                </div>
                            </div>
                        </div>

                        <div>
                            <h5 class="text-center company-name">{{$customer->name}}</h5>
                            <div class="text-center text-muted small mb-4">Member Since in {{$customer->created_at->diffForHumans()}}</div>

                            <table class="table table-sm table-bordered wiz-table">
                                <tr>
                                    <td>{{__('pages.name')}}:</td>
                                    <td>{{$customer->name}}</td>
                                </tr>

                                <tr>
                                    <td>{{__('pages.email')}}:</td>
                                    <td>{{$customer->email}}</td>
                                </tr>


                                <tr>
                                    <td>{{__('pages.phone_number')}}:</td>
                                    <td>{{$customer->phone}}</td>
                                </tr>



                                <tr>
                                    <td>{{__('pages.address')}}:</td>
                                    <td>{{$customer->address}}</td>
                                </tr>

                                <tr>
                                    <td>{{__('pages.created_at')}}:</td>
                                    <td>{{$customer->created_at->format(get_option('app_date_format'))}}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-9">
                <div class="row g-3 mb-3">
                    <!-- Earnings (Monthly) Card Example -->
                    <div class="col-xl-4 col-md-6">
                        <div class="summary-card">
                            <div class="me-3">
                                <h6 class="summary-card-title">{{__('pages.total_sell_amount')}}</h6>
                                <h3 class="summary-card-value" id="sell_of_this_month">
                                    {{get_option('app_currency')}}
                                    {{number_format($customer->sells->sum('grand_total_price'),2)}}
                                </h3>
                            </div>
                            <div>
                                <span class="summary-card-icon btn-soft-success"><i class="fa-solid fa-hand-holding-dollar"></i></span>
                            </div>
                        </div>
                    </div>

                    <!-- Earnings (Monthly) Card Example -->
                    <div class="col-xl-4 col-md-6">
                        <div class="summary-card">
                            <div class="me-3">
                                <h6 class="summary-card-title">{{__('pages.total_paid')}}</h6>
                                <h3 class="summary-card-value" id="sell_of_this_month">
                                    {{get_option('app_currency')}}
                                    {{number_format($customer->sells->sum('paid_amount'),2)}}
                                </h3>
                            </div>
                            <div>
                                <span class="summary-card-icon btn-soft-warning"><i class="bi bi-credit-card"></i></span>
                            </div>
                        </div>
                    </div>

                    <!-- Earnings (Monthly) Card Example -->
                    <div class="col-xl-4 col-md-6">
                        <div class="summary-card">
                            <div class="me-3">
                                <h6 class="summary-card-title">{{__('pages.total_due')}}</h6>
                                <h3 class="summary-card-value" id="sell_of_this_month">
                                    {{get_option('app_currency')}}
                                    {{number_format($customer->sells->sum('due_amount'),2)}}
                                </h3>
                            </div>
                            <div>
                                <span class="summary-card-icon btn-soft-primary"><i class="bi bi-currency-dollar"></i></span>
                            </div>
                        </div>
                    </div>
                </div>


                @if($sells->count() > 0)
                    <div class="wiz-card h-auto">
                        <div class="wiz-card-body">
                            <div class="table-responsive">
                        <table class="table table-sm table-bordered wiz-table mb-0">
                            <thead>
                            <tr class="bg-secondary text-white">
                                <th>{{__('pages.invoice_id')}}</th>

                                <th>{{__('pages.sell_date')}}</th>
                                <th>{{__('pages.sub_total')}}</th>
                                <th>{{__('pages.discount')}}</th>
                                <th>{{__('pages.grand_total')}}</th>
                                <th>{{__('pages.paid_amount')}}</th>
                                <th>{{__('pages.due_amount')}}</th>
                                <th width="5%">{{__('pages.action')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($sells as $key => $sell)
                                <tr>
                                    <td>
                                        <a href="{{route('sell.show', [$sell->id])}}" target="_blank">
                                            {{$sell->invoice_id}}
                                        </a>
                                    </td>
                                    <td>{{$sell->sell_date->format(get_option('app_date_format'))}}</td>
                                    <td> {{get_option('app_currency')}}{{number_format($sell->sub_total, 2)}} </td>
                                    <td> {{get_option('app_currency')}}{{number_format($sell->discount, 2)}} </td>
                                    <td> {{get_option('app_currency')}}{{number_format($sell->grand_total_price, 2)}} </td>
                                    <td> {{get_option('app_currency')}}{{number_format($sell->paid_amount, 2)}} </td>
                                    <td> {{get_option('app_currency')}}{{number_format($sell->due_amount, 2)}} </td>
                                    <td class="font-14">
                                        <div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                                            <a href="{{route('sell.show', [$sell->id])}}" class="mr-2" target="_blank"><i class="fa fa-eye"></i> </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                        {{$sells->links()}}
                    </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>



    </div>
    <!-- /.container-fluid -->
@endsection

@section('js')

@endsection

