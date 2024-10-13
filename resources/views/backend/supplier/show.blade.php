@extends('backend.layouts.app')
@section('title') {{__('pages.supplier')}} @endsection
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid profile">

        <div class="row g-3">
            <div class="col-md-3">
                <div class="wiz-card h-auto">
                    <div class="wiz-card-header">
                        <h5 class="wiz-card-title">Supplier</h5>
                    </div>
                    <div class="wiz-card-body">
                        <div class="col-7 col-md-9 mx-auto p-0">
                            <div class="ratio ratio-1x1 mb-3">
                                <div class="avatar-box">
                                    <img src="{{asset($supplier->logo ? $supplier->logo : 'backend/img/blank-thumbnail.jpg')}}" class="img-fit-cover">
                                </div>
                            </div>
                        </div>

                        <div>
                            <h5 class="company-name text-center">{{$supplier->company_name}}</h5>
                            <div class="small text-muted text-center mb-3">Member Since in {{$supplier->created_at->diffForHumans()}}</div>

                            <table class="table table-bordered wiz-table">
                                <thead>
                                <tr class="text-center">
                                    <th colspan="2"><b>{{__('pages.contact_information')}}</b></th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>{{__('pages.name')}}:</td>
                                    <td>{{$supplier->contact_person}}</td>
                                </tr>
                                <tr>
                                    <td>{{__('pages.email')}}:</td>
                                    <td class="text-break">{{$supplier->email}}</td>
                                </tr>
                                <tr>
                                    <td>{{__('pages.phone_number')}}:</td>
                                    <td>{{$supplier->phone}}</td>
                                </tr>
                                <tr>
                                    <td>{{__('pages.address')}}:</td>
                                    <td>{{$supplier->address}}</td>
                                </tr>
                                <tr>
                                    <td>{{__('pages.created_at')}}:</td>
                                    <td>@formatdate($supplier->created_at)</td>
                                </tr>

                                <tr>
                                    <td colspan="2" class="text-center">
                                        @if($supplier->status == 1)
                                            <a href="javascript:void(0)" onclick="$(this).confirm('{{route('change-supplier-status', $supplier->id)}}');" class="btn btn-brand btn-brand-success btn-sm px-4">
                                                {{__('pages.active')}}
                                            </a>
                                        @else
                                            <a href="javascript:void(0)" onclick="$(this).confirm('{{route('change-supplier-status', $supplier->id)}}');" class="btn btn-brand btn-brand-danger btn-sm px-4">
                                                {{__('pages.not_active')}}
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="row g-3 mb-3">
                    <div class="col-xl-4 col-md-6">
                        <div class="summary-card">
                            <div class="me-3">
                                <h6 class="summary-card-title">{{__('pages.total_purchase_amount')}}</h6>
                                <h3 class="summary-card-value" id="sell_of_this_month">
                                    {{get_option('app_currency')}}
                                    {{number_format($supplier->purchase->sum('total_amount'),2)}}
                                </h3>
                            </div>
                            <div>
                                <span class="summary-card-icon btn-soft-danger"><i class="bi bi-basket"></i></span>
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
                                    {{number_format($supplier->payments->sum('amount'),2)}}
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
                                    {{number_format($supplier->purchase->sum('total_amount') - $supplier->payments->sum('amount'),2)}}
                                </h3>
                            </div>
                            <div>
                                <span class="summary-card-icon btn-soft-primary"><i class="bi bi-currency-dollar"></i></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="wiz-box">
                    <div class="table-responsive">
                        <table class="table table-bordered text-center table-sm wiz-table mw-col-width-skip-first">
                            <thead>
                            <tr class="bg-secondary text-white">
                                <th>{{__('pages.sl')}}</th>
                                <th>{{__('pages.invoice_id')}}</th>
                                <th>{{__('pages.date')}}</th>

                                <th>{{__('pages.total_amount')}}</th>
                                <th>{{__('pages.paid_amount')}}</th>
                                <th>{{__('pages.due_amount')}}</th>
                                <th width="4%">{{__('pages.action')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($purchases as $key => $purchase)
                                <tr>
                                    <td>{{$key+1}}</td>
                                    <td>
                                        <a href="{{route('purchase.show', [$purchase->id])}}" target="_blank">
                                            {{$purchase->invoice_id}}
                                        </a>
                                    </td>
                                    <td>@formatdate($purchase->purchase_date)</td>

                                    <td> {{get_option('app_currency')}}{{number_format($purchase->total_amount, 2)}} </td>
                                    <td> {{get_option('app_currency')}}{{number_format($purchase->paid_amount, 2)}} </td>
                                    <td> {{get_option('app_currency')}}{{number_format($purchase->due_amount, 2)}} </td>
                                    <td class="font-14">
                                        <div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                                            <a href="{{route('purchase.show', [$purchase->id])}}" class="mr-2"><i class="fa fa-eye text-primary"></i> </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="py-3">
                    {{$purchases->links()}}
                </div>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
@endsection

@section('js')

@endsection

