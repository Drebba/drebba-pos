@extends('backend.layouts.app')
@section('title') Sells Report @endsection
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid settings-page">


        <div class="d-flex align-items-center">
            @include('backend.report.sell.partial.nav')

            <div class="btn-group btn-group-sm filter-pdf-btn ms-auto custom-btn-group mb-4" role="group">
                <a href="{{url('report/sell/statistics/last/'.$days.'/days-pdf/download')}}" class="btn btn-brand-danger"><i class="fas fa-file-download me-2"></i> {{__('pages.pdf')}} </a>
                <a href="{{url('report/sell/statistics/last/'.$days.'/days-pdf/print')}}" target="_blank" type="submit" class="btn btn-brand-warning"><i class="fa fa-print me-2"></i> {{__('pages.print')}} </a>
            </div>
        </div>

        <div class="wiz-box mb-4">
            @include('backend.report.sell.statistics.filter-from')
        </div>


        <div class="wiz-card h-auto mb-4">
            <div class="wiz-card-header">
                <h5 class="wiz-card-title">Sells Report</h5>
            </div>
            <div class="wiz-card-body">
                <div class="ratio ratio-21x9">
                    <div>
                        <canvas id="myAreaChart"></canvas>
                    </div>
                </div>
            </div>
        </div>


        <div class="wiz-box">
            @include('backend.report.sell.statistics.table-data')
        </div>
    </div>
    <!-- /.container-fluid -->
    <input type="hidden" value="{{url('/')}}" id="baseUrl">
    <input type="hidden" value="{{$days}}" id="days">
@endsection

@section('js')
    <!-- Page level plugins -->
    <script src="{{asset('backend/vendor/chart.js/Chart.min.js')}}"></script>
    <script src="{{asset('backend/js/demo/chart-area-demo.js')}}"></script>
    <script src="{{asset('/backend/js/partial/sale-report-dynamic-days.js')}}"></script>
@endsection

