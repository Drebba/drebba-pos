@extends('backend.layouts.app')
@section('title') Purchase Report @endsection
@section('content')
	<!-- Begin Page Content -->
	<div class="container-fluid settings-page">

        <div class="d-flex align-items-center">
            @include('backend.report.purchase.partial.nav')
            <div class="btn-group btn-group-sm filter-pdf-btn ms-auto custom-btn-group mb-4" role="group">
                <form action="{{url('report/purchase/statistics-pdf')}}" method="get">
                    @include('backend.report.purchase.partial.hidden-filter-value-pdf')
                    <input type="hidden" name="action_type" value="download">
                    <button type="submit" class="btn btn-brand-danger rounded-end-0"><i class="fas fa-file-download mr-2"></i> {{__('pages.pdf')}} </button>
                </form>

                <form action="{{url('report/purchase/statistics-pdf')}}" method="get" target="_blank">
                    @include('backend.report.purchase.partial.hidden-filter-value-pdf')
                    <input type="hidden" name="action_type" value="print">
                    <button type="submit" class="btn btn-brand-warning rounded-start-0"><i class="fa fa-print mr-2"></i> {{__('pages.print')}} </button>
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
            @include('backend.report.purchase.statistics.filter-form')
            </div>
        </div>

        <div class="wiz-card h-auto mb-4">
            <div class="wiz-card-header">
                <h5 class="wiz-card-title">Purchase Report</h5>
            </div>
            <div class="wiz-card-body">
                <div class="ratio ratio-21x9">
                    <canvas id="myAreaChart"></canvas>
                </div>
            </div>
        </div>

        <div class="wiz-box">
            @include('backend.report.purchase.statistics.table-data')
        </div>

	</div>
	<!-- /.container-fluid -->
    <input type="hidden" value="{{url('/')}}" id="baseUrl">
    <input type="hidden" value="{{Request::get('month') ? Request::get('month') : 'a'}}" id="selected_month">
    <input type="hidden" value="{{Request::get('year') ? Request::get('year') : 'a'}}" id="selected_year">
    <input type="hidden" value="{{Request::get('business_id') ? Request::get('business_id') : 'a'}}" id="selected_branch">
    <input type="hidden" value="{{Request::get('search_type')}}" id="search_type">
@endsection

@section('css')
    <link rel="stylesheet" type="text/css" href="{{asset('admin/plugin/select2/select2.min.css')}}" />
    <link rel="stylesheet" type="text/css" href="{{asset('admin/plugin/select2/select2-bootstrap/select2-bootstrap-5-theme.css')}}" />


    {{--========== Datepicker ============--}}
    <link rel="stylesheet" type="text/css" href="{{asset('admin/plugin/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css')}}" />
@endsection

@section('js')
    <!-- Page level plugins -->
    <script src="{{asset('backend/vendor/chart.js/Chart.min.js')}}"></script>
    <script src="{{asset('backend/js/demo/chart-area-demo.js')}}"></script>
    <script src="{{asset('/backend/js/partial/purchase-report-statistics-filter.js')}}"></script>

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
