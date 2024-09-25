@extends('backend.layouts.app')
@section('title') {{__('pages.sell_report')}} @endsection
@section('content')
	<!-- Begin Page Content -->
	<div class="container-fluid settings-page">
        <div class="mb-4">
            @include('backend.report.sell.partial.nav')
        </div>


        <div class="wiz-box mb-4">
            <div class="d-md-none my-2">
                <a href="javascript:void(0)" data-bs-toggle="collapse" data-bs-target="#mwFilterCollapse" class="btn btn-soft-primary">
                    <span class="title-normal m-0">Filter</span>
                    <span><i class="bi bi-funnel"></i></span>
                </a>
            </div>

            <div class="collapse d-md-block" id="mwFilterCollapse">
                <form action="{{url('report/sell/summary')}}" method="get">
                    @include('backend.report.sell.summary.filter-from')
                </form>
            </div>
        </div>


        <div class="wiz-card">
            <!-- Card Header - Dropdown -->
            <div class="wiz-card-header">
                <h5 class="wiz-card-title">{{__('pages.sell_report')}}</h5>

                <div class="btn-group btn-group-sm filter-pdf-btn" role="group">
                    <form action="{{url('report/sell/summary')}}" method="get" target="_blank">
                        @include('backend.report.sell.partial.hidden-filter-value-pdf')
                        <input type="hidden" name="action_type" value="download">
                        <button type="submit" class="btn btn-brand btn-brand-danger btn-sm px-3"><i class="fas fa-file-pdf me-2"></i> {{__('pages.pdf')}} </button>
                    </form>
                </div>
            </div>
            <!-- Card Body -->
            <div class="card-body p-0">


                <div class="table-responsive">
                    @include('backend.report.sell.summary.table-data')
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

