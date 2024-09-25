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
                </div>

                <div class="flex-grow-1">
                    <div class="collapse d-md-block" id="mwFilterCollapse">
                        @include('backend.payment.customer.filter-from')
                    </div>
                </div>

            </div>
        </div>


        <div class="wiz-card">
            <div class="wiz-card-body">
                <h5 class="wiz-card-title">Payment from Customer</h5>

                @if ($errors->any())
                    <div class="bg-soft-danger rounded p-2 mb-3">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li class="py-1">{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif


                <div class="table-responsive">
                    <table class="table table-bordered wiz-table mw-col-width-skip-first">
                        <thead>
                        <tr class="bg-secondary text-white">
                            <th>{{__('pages.sl')}}</th>
                            <th class="text-center">{{__('pages.invoice_id')}}</th>
                            <th class="text-center">{{__('pages.customer')}}</th>
                            <th class="text-center">{{__('pages.sell_date')}}</th>
                            <th class="text-center">{{__('pages.sub_total')}}</th>
                            <th class="text-center">{{__('pages.discount')}}</th>
                            <th class="text-center">{{__('pages.grand_total')}}</th>
                            <th class="text-center">{{__('pages.paid_amount')}}</th>
                            <th class="text-center">{{__('pages.due_amount')}}</th>
                            <th class="text-center">{{__('pages.action')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($due_sells as $key => $sell)
                            <tr>
                                <td>{{$key+1}}</td>
                                <td class="text-center">{{$sell->invoice_id}}</td>
                                <td class="text-center">{{$sell->customer->name}}</td>
                                <td class="text-center">{{$sell->sell_date->format(get_option('app_date_format'))}}</td>
                                <td class="text-center">{{get_option('app_currency')}}{{number_format($sell->sub_total, 2)}} </td>
                                <td class="text-center">{{get_option('app_currency')}}{{number_format($sell->discount, 2)}} </td>
                                <td class="text-center">{{get_option('app_currency')}}{{number_format($sell->grand_total_price, 2)}} </td>
                                <td class="text-center">{{get_option('app_currency')}}{{number_format($sell->paid_amount, 2)}} </td>
                                <td class="text-center">{{get_option('app_currency')}}{{number_format($sell->due_amount, 2)}} </td>

                                <td class="text-center">
                                    <div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                                        <a href="javascript:void(0)" data-bs-toggle="offcanvas" data-bs-target="#paymentDrawer" class="btn btn-brand text-nowrap btn-brand-primary btn-sm py-1 show-payment-details-for-customer" data-sell-id="{{$sell->id}}" data-due-amount="{{$sell->due_amount}}">Pay Now </a>
                                    </div>
                                </td>
                            </tr>


                        @endforeach
                        </tbody>
                    </table>
                </div>


                <div class="py-3">
                    {{$due_sells->links()}}
                </div>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->



    <div class="offcanvas offcanvas-end" tabindex="-1" id="paymentDrawer">
        <div class="offcanvas-header align-items-center border-bottom">
            <h5 class="wiz-card-title mb-0">{{__('pages.payment')}}</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <form action="{{route('payment-from-customer.store')}}" method="post" data-parsley-validate>
                @csrf

                <div class="row justify-content-center">
                    <div class="col-md-12">
                        <div class="row">
                            <input type="hidden" name="sell_id" id="sell_id">

                            <div class="col-md-12">
                                <div class="custom-form-group">
                                    <label for="payment_date" class="custom-label">{{__('pages.payment_date')}} <span class="text-danger">*</span></label>
                                    <input name="payment_date" value="{{old('payment_date') ? old('payment_date') : \Carbon\Carbon::now()->format('Y-m-d')}}" id="payment_date" type="text" data-date-format="yyyy-mm-dd" class="datepicker form-control" placeholder="{{__('pages.date')}}" required autocomplete="off">
                                    @if ($errors->has('payment_date'))
                                        <div class="error">{{ $errors->first('payment_date') }}</div>
                                    @endif
                                </div>
                            </div>


                            <div class="col-md-12">
                                <div class="custom-form-group">
                                    <label for="amount" class="custom-label">{{__('pages.amount')}} <span class="text-danger">*</span></label>
                                    <input type="number" name="amount" id="amount" step=".1" min="0"  placeholder="{{__('pages.amount')}}" class="form-control" aria-describedby="emailHelp" required>
                                    @if ($errors->has('amount'))
                                        <div class="error">{{ $errors->first('amount') }}</div>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="custom-form-group mb-4">
                                    <label for="note" class="custom-label">{{__('pages.note')}}</label>
                                    <textarea name="note" class="form-control form-control-lg">{{old('note')}}</textarea>
                                </div>
                            </div>


                            <div class="custom-form-group text-end">
                                <button type="submit" class="btn btn-brand btn-brand-primary">{{__('pages.save')}}</button>
                            </div>
                        </div>
                    </div>

                </div>
            </form>
        </div>
    </div>

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
