@extends('backend.layouts.app')
@section('title') {{__('pages.purchase')}} @endsection
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <div class="wiz-box d-flex justify-content-between align-items-center mb-3">
            <h6 class="page-title mb-0">{{__('pages.purchase_details')}}</h6>
            <div class="btn-group btn-group-sm custom-btn-group" role="group">
                <a href="{{route('purchase.edit', [$purchase->id])}}" class="btn btn-brand-primary rounded-0"><i class="bi bi-pencil me-1"></i> {{__('pages.edit')}} </a>
                <a href="{{url('export/purchase/print-invoice/id='.$purchase->id.'/type=pdf')}}" class="btn btn-danger"><i class="bi bi-file-earmark-pdf me-1"></i> {{__('pages.pdf')}} </a>
                <a href="{{url('export/purchase/print-invoice/id='.$purchase->id.'/type=print')}}" target="_blank" class="btn btn-brand-warning"><i class="fa fa-print me-1"></i> {{__('pages.print')}} </a>
            </div>
        </div>

        <div class="row g-3 mb-3">
            <div class="col-md-6">
                <div class="wiz-card">
                    <div class="wiz-card-body">
                        <table class="table table-bordered table-sm wiz-table mb-0 text-center">
                            <thead>
                            <tr class="bg-primary text-white text-center">
                                <th colspan="2">{{__('pages.summary')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>{{__('pages.invoice_id')}}</td>
                                <td>{{$purchase->invoice_id}}</td>
                            </tr>

                            <tr>
                                <td>{{__('pages.branch')}}</td>
                                <td>{{$purchase->branch->title}}</td>
                            </tr>

                            <tr>
                                <td>{{__('pages.total_amount')}}</td>
                                <td> {{get_option('app_currency')}}{{number_format($purchase->total_amount, 2)}} </td>
                            </tr>

                            <tr>
                                <td>{{__('pages.paid_amount')}}</td>
                                <td> {{get_option('app_currency')}}{{number_format($purchase->paid_amount, 2)}} </td>
                            </tr>

                            <tr>
                                <td>{{__('pages.due_amount')}}</td>
                                <td> {{get_option('app_currency')}}{{number_format($purchase->due_amount, 2)}} </td>
                            </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="wiz-card">
                    <div class="wiz-card-body">
                        <table class="table table-bordered table-sm wiz-table mb-0 text-center">
                            <thead>
                            <tr class="bg-warning text-white text-center">
                                <th colspan="2">{{__('pages.supplier')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>{{__('pages.company_name')}}</td>
                                <td>{{$purchase->supplier->company_name}}</td>
                            </tr>

                            <tr>
                                <td>{{__('pages.contact_person')}}</td>
                                <td>{{$purchase->supplier->contact_person}}</td>
                            </tr>

                            <tr>
                                <td>{{__('pages.phone_number')}}</td>
                                <td>{{$purchase->supplier->phone}}</td>
                            </tr>

                            <tr>
                                <td>{{__('pages.email')}}</td>
                                <td>{{$purchase->supplier->email}}</td>
                            </tr>

                            <tr>
                                <td>{{__('pages.address')}}</td>
                                <td>{{$purchase->supplier->address}}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>



        <div class="wiz-card">
            <div class="wiz-card-body">
                <div class="table-responsive">
                    <table class="table table-bordered wiz-table mb-0">
                        <thead>
                        <tr>
                            <th width="5%" class="text-center">{{__('pages.sl')}}</th>
                            <th class="text-center">{{__('pages.product')}}</th>
                            <th class="text-center">{{__('pages.unit_price')}}</th>
                            <th class="text-center col-md-3 col-xl-2">{{__('pages.quantity')}}</th>
                            <th class="text-center col-md-3 col-xl-2">{{__('pages.total_price')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($purchase->purchaseProducts as $key => $purchase_product)
                            <tr>
                                <td class="text-center">{{$key+1}}</td>
                                <td class="text-center">{{$purchase_product->product->title}}</td>
                                <td class="text-center"> {{get_option('app_currency')}}{{number_format($purchase_product->purchase_price, 2)}} </td>
                                <td class="text-center"> {{$purchase_product->quantity}} {{$purchase_product->product->unit->title ?? ''}} </td>
                                <td class="text-center"> {{get_option('app_currency')}}{{number_format($purchase_product->total_price, 2)}} </td>
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
                                <strong>{{__('pages.total_amount')}}: </strong>
                            </td>
                            <td class="text-end text-md-center col-6">
                                <strong>
                                    {{get_option('app_currency')}}{{number_format($purchase->total_amount, 2)}}
                                </strong>
                            </td>
                        </tr>

                        <tr>
                            <td class="text-md-center">
                                <strong>{{__('pages.paid_amount')}}: </strong>
                            </td>
                            <td class="text-end text-md-center">
                                <strong>
                                    {{get_option('app_currency')}}{{number_format($purchase->paid_amount, 2)}}
                                </strong>
                            </td>
                        </tr>

                        <tr>
                            <td class="text-md-center">
                                <strong>{{__('pages.due_amount')}}: </strong>
                            </td>
                            <td class="text-end text-md-center">
                                <strong>
                                    {{get_option('app_currency')}}{{number_format($purchase->due_amount, 2)}}
                                </strong>
                            </td>
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

