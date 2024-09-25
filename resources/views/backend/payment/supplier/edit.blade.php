@extends('backend.layouts.app')
@section('title') {{__('pages.payment')}}  @endsection
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <div class="wiz-card wiz-card-single">
            <div class="wiz-card-header">
                <h5 class="wiz-card-title mb-0">{{__('pages.update_payment')}}</h5>
                <div>
                    <a href="{{route('payment-to-supplier.index')}}" class="btn btn-brand btn-brand-secondary btn-sm"><i class="fa fa-list me-2"></i> {{__('pages.all_payment')}}</a>
                </div>
            </div>
            <div class="wiz-card-body p-4">
                <form action="{{route('payment-to-supplier.update', [$payment->id])}}" method="post" data-parsley-validate>
                    @csrf
                    @method('patch')

                    <div class="row justify-content-center">

                        <div>
                            <div class="custom-form-group">
                                <label for="payment_date" class="custom-label">{{__('pages.payment_date')}} <span class="text-danger">*</span></label>
                                <input name="payment_date" value="{{$payment->payment_date->toDateString()}}" id="payment_date" type="text" data-date-format="yyyy-mm-dd" class="datepicker form-control" placeholder="{{__('pages.date')}}" required autocomplete="off">
                                @if ($errors->has('payment_date'))
                                    <div class="error">{{ $errors->first('payment_date') }}</div>
                                @endif
                            </div>

                            <div class="custom-form-group">
                                <label for="supplier_id" class="custom-label">{{__('pages.supplier')}} <span class="text-danger">*</span></label>
                                <select name="supplier_id" id="supplier_id" class="form-select select2-basic">
                                    <option value="">{{__('pages.select_supplier')}}</option>
                                    @foreach($suppliers as $supplier)
                                        <option value="{{$supplier->id}}" {{$payment->supplier_id == $supplier->id ? 'selected' : ''}}>{{$supplier->company_name}}</option>
                                    @endforeach
                                </select>

                                @if ($errors->has('supplier_id'))
                                    <div class="error mt-1">{{ $errors->first('supplier_id') }}</div>
                                @endif
                            </div>

                            <div class="custom-form-group">
                                <label for="amount" class="custom-label">{{__('pages.amount')}} <span class="text-danger">*</span></label>
                                <input type="number" name="amount" step=".1" min="0" id="amount" value="{{$payment->amount}}" placeholder="{{__('pages.amount')}}" class="form-control" aria-describedby="emailHelp" required>
                                @if ($errors->has('amount'))
                                    <div class="error">{{ $errors->first('amount') }}</div>
                                @endif
                            </div>

                            <div class="custom-form-group mb-4">
                                <label for="note" class="custom-label">{{__('pages.note')}}</label>
                                <textarea name="note" class="form-control">{{$payment->note}}</textarea>
                            </div>

                            <div class="text-end">
                                <button type="submit" class="btn btn-brand btn-brand-primary">{{__('pages.update')}}</button>
                            </div>
                        </div>

                    </div>
                </form>
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
