@extends('backend.layouts.app')
@section('title') {{__('pages.payment')}} @endsection
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">


        <div class="wiz-box mb-4">
            <div class="d-md-flex justify-content-between">
                <div class="d-flex flex-wrap align-items-center align-items-md-start justify-content-between pt-1 pt-md-0 pb-2 pb-md-0 order-md-last">
                    <a href="javascript:void(0)" data-bs-toggle="collapse" data-bs-target="#mwFilterCollapse" class="btn btn-soft-primary d-md-none">
                        <span class="title-normal m-0">Filter</span>
                        <span class="ps-3"><i class="bi bi-funnel"></i></span>
                    </a>
                    <div class="ps-md-5">
                        <a href="{{route('payment-to-supplier.create')}}" class="btn btn-brand btn-brand-secondary text-nowrap"><i class="fa fa-plus me-2"></i> {{__('pages.new_payment')}}</a>
                    </div>
                </div>

                <div class="flex-grow-1">
                    <div class="collapse d-md-block" id="mwFilterCollapse">
                        @include('backend.payment.supplier.filter-from')
                    </div>
                </div>

            </div>
        </div>


        <div class="wiz-card">
            <div class="wiz-card-body">
                <h5 class="wiz-card-title">Payment to Supplier</h5>
                <div class="table-responsive-md">
                    <table class="table table-bordered wiz-table mw-col-width-skip-first">
                        <thead>
                        <tr class="bg-secondary text-white">
                            <th>{{__('pages.sl')}}</th>
                            <th class="text-center">{{__('pages.supplier')}}</th>
                            <th class="text-center">{{__('pages.date')}}</th>
                            <th class="text-center">{{__('pages.amount')}}</th>
                            <th class="text-center">{{__('pages.action')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($payments as $key => $payment)
                            <tr>
                                <td class="text-center">{{$key+1}}</td>
                                <td class="text-center">
                                    <a href="{{route('supplier.show', [$payment->supplier_id])}}" target="_blank">
                                        {{$payment->supplier ? $payment->supplier->company_name : '--'}}
                                    </a>
                                </td>
                                <td class="text-center">{{$payment->payment_date->format(get_option('app_date_format'))}}</td>
                                <td class="text-center"> {{get_option('app_currency')}}{{number_format($payment->amount, 2)}} </td>

                                <td class="text-center">
                                    <div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                                        <a href="{{route('payment-to-supplier.edit', [$payment->id])}}" class="mx-2 text-brand-primary"><i class="bi bi-pencil"></i></a>
                                        <a href="javascript:void(0)" class="mx-2 show-payment-details" data-bs-toggle="offcanvas" data-bs-target="#paymentDetails{{$payment->id}}" data-payment-id="{{$payment->id}}"><i class="bi bi-eye"></i></a>
                                        <div class="offcanvas offcanvas-end" tabindex="-1" id="paymentDetails{{$payment->id}}">
                                            <div class="offcanvas-header align-items-center border-bottom">
                                                <h5 class="wiz-card-title mb-0">{{__('pages.payment')}}</h5>
                                                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                                            </div>
                                            <div class="offcanvas-body">
                                                <table class="table table-borderless wiz-table">


                                                    <tr>
                                                        <td class="text-start ps-0">{{__('pages.supplier')}}:</td>
                                                        <td class="text-end">{{$payment->supplier ? $payment->supplier->company_name : '--'}}</td>
                                                    </tr>

                                                    <tr>
                                                        <td class="text-start ps-0">{{__('pages.amount')}}:</td>
                                                        <td class="text-end"><b>{{get_option('app_currency')}}{{number_format($payment->amount, 2)}}</b></td>
                                                    </tr>

                                                    <tr>
                                                        <td class="text-start ps-0">{{__('pages.payment_date')}}:</td>
                                                        <td class="text-end">{{$payment->payment_date->format(get_option('app_date_format'))}}</td>
                                                    </tr>

                                                    <tr>
                                                        <td class="text-start ps-0">{{__('pages.note')}}:</td>
                                                        <td class="text-end">{{$payment->note}}</td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                        <a href="javascript:void(0);" onclick="$(this).confirmDelete($('#delete-{{$key}}')) " class="mx-2 text-danger"><i class="bi bi-trash3"></i></a>
                                        <form action="{{ route('payment-to-supplier.destroy',$payment->id) }}" method="post" id="delete-{{$key}}"> @csrf @method('delete') </form>
                                    </div>
                                </td>
                            </tr>

                        @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="py-3">
                    {{$payments->links()}}
                </div>
            </div>
        </div>


    </div>
    <!-- /.container-fluid -->
@endsection


@section('css')
    <link rel="stylesheet" type="text/css" href="{{asset('admin/plugin/select2/select2.min.css')}}" />
    <link rel="stylesheet" type="text/css" href="{{asset('admin/plugin/select2/select2-bootstrap/select2-bootstrap-5-theme.css')}}" />


    {{--========== Datepicker ============--}}
    <link rel="stylesheet" type="text/css" href="{{asset('admin/plugin/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css')}}" />
@endsection
@section('js')
    <script src="{{asset('/backend/js/custom.js')}}"></script>

    <script src="{{asset('admin/plugin/select2/select2.min.js')}}"></script>
    <script>
        $('.select2-basic').select2({
            theme: "bootstrap-5",
            width: $( this ).data( 'width' ) ? $( this ).data( 'width' ) : $( this ).hasClass( 'w-100' ) ? '100%' : 'style',
            placeholder: $( this ).data( 'placeholder' ),
        });
    </script>

    {{--============== Datepicker ================--}}
    <script src="{{asset('admin/plugin/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js')}}"></script>
    <script>
        $('.datepicker').datepicker({
            format:'yyyy-mm-dd',
            zIndexOffset: 1198,
        }).on('changeDate', function(e) {
            // when the date is changed
            $(this).datepicker('hide');
        });
    </script>
@endsection

