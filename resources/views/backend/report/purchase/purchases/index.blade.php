@extends('backend.layouts.app')
@section('title') Purchases Report @endsection
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid settings-page">
        <div class="d-flex align-items-center">
            @include('backend.report.purchase.partial.nav')
            <div class="btn-group btn-group-sm filter-pdf-btn ms-auto custom-btn-group mb-4" role="group">
                <form action="{{url('report/purchase/purchases')}}" method="get">
                    @include('backend.report.purchase.partial.hidden-filter-value-pdf')
                    <input type="hidden" name="action_type" value="download">
                    <button type="submit" class="btn btn-brand-danger rounded-end-0"><i class="fas fa-file-download mr-2"></i> PDF </button>
                </form>

                <form action="{{url('report/purchase/purchases')}}" method="get" target="_blank">
                    @include('backend.report.purchase.partial.hidden-filter-value-pdf')
                    <input type="hidden" name="action_type" value="print">
                    <button type="submit" class="btn btn-brand-warning rounded-start-0"><i class="fa fa-print mr-2"></i> PRINT </button>
                </form>
            </div>
        </div>


        <div class="wiz-box mb-4">
            <div class="d-md-none my-2">
                <a href="javascript:void(0)" data-bs-toggle="collapse" data-bs-target="#mwFilterCollapse" class="btn btn-soft-primary">
                    <span class="title-normal m-0">Filter</span>
                    <span><i class="bi bi-funnel"></i></span>
                </a>
            </div>

            <div class="collapse d-md-block" id="mwFilterCollapse">
            <form action="{{url('report/purchase/purchases')}}" method="get">
                @include('backend.report.purchase.purchases.filter-form')
            </form>
            </div>
        </div>



        <!-- Card Body -->
        <div class="wiz-card h-auto">
            <div class="wiz-card-header">
                <h5 class="wiz-card-title">Purchases Report</h5>
            </div>
            <div class="wiz-card-body">
                <div class="table-responsive">
                    <table class="table table-bordered text-center wiz-table mw-col-width-skip-first">
                        <thead>
                        <tr>
                            <th style="width: 60px">{{__('pages.sl')}}</th>
                            <th>{{__('pages.invoice_id')}}</th>
                            <th>{{__('pages.supplier')}}</th>
                            <th>{{__('pages.purchase_date')}}</th>
                            <th>{{__('pages.total_amount')}}</th>
                            <th>{{__('pages.paid_amount')}}</th>
                            <th>{{__('pages.due_amount')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($purchases as $key => $purchase)
                            <tr>
                                <td>{{$key + 1}}</td>
                                <td>
                                    <a href="{{route('purchase.show', [$purchase->id])}}" target="_blank">{{$purchase->invoice_id}}</a>
                                </td>
                                <td>{{$purchase->supplier->company_name??null}}</td>
                                <td>@formatdate($purchase->purchase_date)</td>
                                <td> {{get_option('app_currency')}}{{number_format($purchase->total_amount, 2)}} </td>
                                <td> {{get_option('app_currency')}}{{number_format($purchase->paid_amount, 2)}} </td>
                                <td> {{get_option('app_currency')}}{{number_format($purchase->due_amount, 2)}} </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    <div class="text-center pl-2">
                        {{$purchases->appends(Request::all())->links()}}
                    </div>
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

