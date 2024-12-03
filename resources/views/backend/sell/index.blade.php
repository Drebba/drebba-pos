@extends('backend.layouts.app')
@section('title') {{__('pages.sells')}} @endsection
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">
        <div class="wiz-box mb-4">
            <div class="d-md-none my-2">
                <a href="javascript:void(0)" data-bs-toggle="collapse" data-bs-target="#mwFilterCollapse" class="btn btn-soft-primary">
                    <span class="title-normal m-0">Filter</span>
                    <span><i class="bi bi-funnel"></i></span>
                </a>
            </div>

            <div class="collapse d-md-block" id="mwFilterCollapse">
                <form action="{{route('sell.index')}}" method="get" id="form">
                <div class="row g-3">
                    <div class="col-sm-6 col-md-4 col-lg">
                        <div class="form-group">
                            <input type="text" name="invoice_id" value="{{Request::get('invoice_id')}}" class="form-control" placeholder="{{__('pages.invoice_id')}}">
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-4 col-lg">
                        <div class="form-group">
                            <select name="table_id" class="form-select select2-basic">
                                <option value="">All Table</option>
                                @foreach(Auth::user()->business->table as $table)
                                    <option value="{{$table->id}}" {{Request::get('table_id') == $table->id ? 'selected' : ''}}>{{$table->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-sm-6 col-md-4 col-lg">
                        <div class="form-group">
                            <select name="order_mode" class="form-select select2-basic">
                                <option value="">All Mode</option>
                                @foreach(App\Models\OrderType::all() as $type)
                                    <option value="{{$type->id}}" {{Request::get('order_mode') == $type->id ? 'selected' : ''}}>{{$type->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-sm-6 col-md-4 col-lg">
                        <div class="form-group">
                            <select name="user_id" class="form-select select2-basic">
                                <option value="">All User</option>
                                @foreach(Auth::user()->business->user as $user)
                                    <option value="{{$user->id}}" {{Request::get('user_id') == $user->id ? 'selected' : ''}}>{{$user->name}}, {{$user->phone}} </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-sm-6 col-md-4 col-lg">
                        <div class="form-group">
                            <select name="customer_id" class="form-select select2-basic">
                                <option value="">{{__('pages.all_customer')}}</option>
                                @foreach($customers as $customer)
                                    <option value="{{$customer->id}}" {{Request::get('customer_id') == $customer->id ? 'selected' : ''}}>{{$customer->name}}, {{$customer->phone}} </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-4 col-lg">
                        <div class="form-group">
                            <input type="text" name="start_date" value="{{Request::get('start_date')}}" data-date-format="yyyy-mm-dd" class="datepicker form-control" placeholder="{{__('pages.start_date')}}" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-4 col-lg">
                        <div class="form-group text-left">
                            <input type="text" name="end_date" value="{{Request::get('end_date')}}" data-date-format="yyyy-mm-dd" class="datepicker form-control" placeholder="{{__('pages.end_date')}}" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-4 col-lg">
                        <div class="form-group">
                            <button class="btn btn-primary btn-sm"><i class="fas fa-search" style="font-size: 20px"></i></button>
                            <button name="export" class="text-light btn btn-success" value="1"><i class="fas fa-file-excel" style="font-size: 20px"></i></button>
                        </div>
                    </div>
                </div>
            </form>
            </div>
        </div>

        <div class="wiz-card">
            <div class="wiz-card-body">
                <div class="d-flex justify-content-between">
                    <h5 class="wiz-card-title">Sell</h5>
                </div>
                <div class="table-responsive-xl">
                    <table class="table table-bordered text-center wiz-table mw-col-width-skip-first">
                        <thead class="sticky-top">
                        <tr class="bg-secondary text-white">
                            <th>{{__('pages.sl')}}</th>
                            <th>{{__('pages.invoice_id')}}</th>
                            <th>Serve BY</th>
                            <th>{{__('pages.sell_date')}}</th>
                            <th>{{__('pages.grand_total')}}</th>
                            <th>{{__('pages.paid_amount')}}</th>
                            <th>{{__('pages.due_amount')}}</th>
                            <th width="8%">{{__('pages.action')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                            @php
                                $grandtotal=0;
                                $paid=0;
                                $due=0;
                            @endphp
                        @foreach($sells as $key => $sell)
                        @php
                            $grandtotal+=$sell->grand_total_price;
                            $paid+=$sell->paid_amount;
                            $due+=$sell->due_amount;
                        @endphp
                            <tr>
                                <td>{{$key+1}}</td>
                                <td>{{$sell->invoice_id}}</td>
                                <td>{{$sell->user?->name}}</td>
                                <td> @formatdate($sell->sell_date) </td>
                                <td> {{get_option('app_currency')}}{{number_format($sell->grand_total_price, 2)}} </td>
                                <td> {{get_option('app_currency')}}{{number_format($sell->paid_amount, 2)}} </td>
                                <td> {{get_option('app_currency')}}{{number_format($sell->due_amount, 2)}} </td>
                                <td class="font-14">
                                    <div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                                        {{-- <a href="{{route('sell.edit', [$sell->id])}}" class="mx-2 text-brand-primary"><i class="bi bi-pencil"></i></a> --}}
                                        <a href="{{route('sell.show', [$sell->id])}}" class="mx-2"><i class="bi bi-eye"></i></a>
                                        <a href="javascript:void(0);" onclick="$(this).confirmDelete($('#delete-{{$key}}')) " class="mx-2 text-danger"><i class="bi bi-trash3"></i></a>
                                        <form action="{{ route('sell.destroy',$sell->id) }}" method="post" id="delete-{{$key}}"> @csrf @method('delete') </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        <tr>
                            <th colspan="4">Total</th>
                            <th>{{$grandtotal}}</th>
                            <th>{{$paid}}</th>
                            <th>{{$due}}</th>

                        </tr>
                        </tbody>
                    </table>
                </div>

                <div class="py-3">
                    {{$sells->appends(Request::all())->links()}}
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

