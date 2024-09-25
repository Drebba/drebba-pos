@extends('backend.layouts.app')
@section('title') Sells Report @endsection
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid settings-page">
        <div>
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
            @include('backend.report.sell.statistics.filter-from')
            </div>
        </div>


        <div class="wiz-card h-auto mb-4">
            <div class="wiz-card-header">
                <h5 class="wiz-card-title">Sells Report</h5>
            </div>
            <div class="wiz-card-body">
                <div class="ratio ratio-21x9">
                    <canvas id="myAreaChart"></canvas>
                </div>
            </div>
        </div>

        <div class="wiz-box">
            @include('backend.report.sell.statistics.table-data')
        </div>


    </div>
    <!-- /.container-fluid -->
    <input type="hidden" value="{{url('/')}}" id="baseUrl">
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
    <script src="{{asset('/backend/js/partial/sale-report-statistic.js')}}"></script>

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

