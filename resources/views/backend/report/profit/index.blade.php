@extends('backend.layouts.app')
@section('title') {{__('pages.profit_loss_report')}} @endsection
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid settings-page">

        <div class="wiz-box mb-3">
            <div class="d-md-none my-2">
                <a href="javascript:void(0)" data-bs-toggle="collapse" data-bs-target="#mwFilterCollapse" class="btn btn-soft-primary">
                    <span class="title-normal m-0">Filter</span>
                    <span><i class="bi bi-funnel"></i></span>
                </a>
            </div>

            <div class="collapse d-md-block" id="mwFilterCollapse">
            <div class="row g-3 gx-xl-5">
                <div class="col-md-6">
                    <form action="{{url('report/profit/loss/filter')}}" method="get">
                        <div class="d-md-flex gap-2 align-items-center">
                            <div class="row g-2 flex-grow-1">
                                <input type="hidden" name="search_type" value="month">
                                <div class="col-md-6">
                                    <div class="form-group text-left">
                                        <input type="text" name="month" data-date-format="yyyy-M"  value="{{Request::get('month')}}"  placeholder="{{__('pages.select_month')}}" id="datepicker" class="form-control" autocomplete="off">
                                    </div>
                                </div>

                            </div>
                            <div class="form-group pt-2 pt-md-0 text-end">
                                <button class="btn btn-brand btn-brand-primary">{{__('pages.search')}}</button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="col-md-6">
                    <form action="{{url('report/profit/loss/filter')}}" method="get">
                        <div class="d-md-flex gap-2 align-items-center">
                            <div class="row g-2 flex-grow-1">
                                <input type="hidden" name="search_type" value="year">
                                <div class="col-md-6">
                                    <div class="form-group text-left">
                                        <input type="text" name="year" data-date-format="yyyy"  value="{{Request::get('year')}}"  placeholder="{{__('pages.select_year')}}" id="yearPicker" class="form-control" autocomplete="off">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group pt-2 pt-md-0 text-end">
                                <button class="btn btn-brand btn-brand-primary">{{__('pages.search')}}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="wiz-card">
                    <div class="wiz-card-header">
                        <h5 class="wiz-card-title">{{__('pages.profit_loss_report')}}</h5>
                    </div>
                    <!-- Card Body -->
                    <div class="wiz-card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered text-center wiz-table mw-col-width-skip-first"  >
                                <thead>
                                <tr>
                                    <th style="width: 60px">{{__('pages.sl')}}</th>
                                    {{-- @can('access_to_all_branch')
                                        <th>{{__('pages.branch')}}</th>
                                    @endcan --}}
                                    <th scope="col">{{__('pages.date')}}</th>
                                    <th scope="col">{{__('pages.income')}}</th>
                                    <th scope="col">{{__('pages.expense')}}</th>
                                    <th scope="col">{{__('pages.profit_loss')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php
                                    $total_income = 0;
                                    $total_expense = 0;
                                @endphp
                                @foreach($profit_info as $key => $profit)
                                    @php
                                        $total_income += $profit['income'];
                                        $total_expense += $profit['expense'];
                                    @endphp

                                    <tr class="{{$profit['profit_loss'] >= 0 ? '' : 'text-danger'}}">
                                        <td>{{$key+1}}</td>
                                        {{-- @can('access_to_all_branch')
                                            <td>All Branch</td>
                                        @endcan --}}
                                        <td>{{\Carbon\Carbon::parse($profit['date'])->format(get_option('app_date_format'))}}</td>
                                        <td>{{number_format($profit['income'], 2)}}</td>
                                        <td>{{number_format($profit['expense'], 2)}}</td>
                                        <td>{{number_format($profit['profit_loss'], 2)}}</td>
                                    </tr>
                                @endforeach

                                <tr>
                                    @can('access_to_all_branch')
                                        <td colspan="3"><b>Total:</b></td>
                                    @else
                                        <td colspan="2"><b>Total:</b></td>
                                    @endcan

                                    <td>{{get_option('app_currency')}} <b>{{number_format($total_income, 2)}}</b></td>
                                    <td>{{get_option('app_currency')}} <b>{{number_format($total_expense, 2)}}</b></td>
                                    <td>{{get_option('app_currency')}}  <b>{{number_format($total_income - $total_expense, 2)}}</b></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
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
