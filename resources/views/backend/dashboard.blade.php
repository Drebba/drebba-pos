@extends('backend.layouts.app')
@section('title') {{__('pages.dashboard')}} @endsection
@section('content')
    <div class="container-fluid">
        {{--========= Summary =============--}}
        <div class="mb-4">
            <div class="row g-4">
                <div class="col-xl-3 col-md-6">
                    <div class="summary-card">
                        <div class="me-3">
                            <h6 class="summary-card-title">{{__('pages.sales_of_month')}}</h6>
                            <h3 class="summary-card-value" id="sell_of_this_month"><img src="{{asset('backend/img/loading.gif')}}" class="img-responsive" height="20"></h3>
                        </div>
                        <div>
                            <span class="summary-card-icon btn-soft-primary"><i class="bi bi-currency-dollar"></i></span>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="summary-card">
                        <div class="me-3">
                            <h6 class="summary-card-title">{{__('pages.total_sales_amount')}}</h6>
                            <h3 class="summary-card-value" id="total_sell"><img src="{{asset('backend/img/loading.gif')}}" class="img-responsive" height="20"></h3>
                        </div>
                        <div>
                            <span class="summary-card-icon btn-soft-success"><i class="fa-solid fa-hand-holding-dollar"></i></span>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="summary-card">
                        <div class="me-3">
                            <h6 class="summary-card-title">{{__('pages.purchase_of_month')}}</h6>
                            <h3 class="summary-card-value" id="purchase_of_this_month"><img src="{{asset('backend/img/loading.gif')}}" class="img-responsive" height="20"></h3>
                        </div>
                        <div>
                            <span class="summary-card-icon btn-soft-warning"><i class="bi bi-credit-card"></i></span>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="summary-card">
                        <div class="me-3">
                            <h6 class="summary-card-title">{{__('pages.total_purchase_amount')}}</h6>
                            <h3 class="summary-card-value" id="total_purchase"><img src="{{asset('backend/img/loading.gif')}}" class="img-responsive" height="20"></h3>
                        </div>
                        <div>
                            <span class="summary-card-icon btn-soft-danger"><i class="bi bi-basket"></i></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Row -->
        <div class="row g-4">
            <!-- Area Chart -->
            <div class="col-md-6 col-12">
                <div class="wiz-card">
                    <div class="wiz-card-header align-items-center">
                        <h5 class="wiz-card-title">{{__('pages.sales_summary_last_30_days')}}</h5>
                        <div>
                            <select class="form-select form-select-sm small text-brand-dark">
                                <option>This Month</option>
                                <option>This Year</option>
                            </select>
                        </div>
                    </div>
                    <div class="wiz-card-body">
                        <div class="ratio ratio-16x9">
                            <div>
                                <canvas id="myAreaChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-12">
                <div class="wiz-card">
                    <div class="wiz-card-body">
                        <h5 class="wiz-card-title">{{__('pages.last_10_days_profit_loss')}}</h5>

                        <div class="table-responsive-md">
                            <table class="table table-sm table-bordered w-100 text-center wiz-table mw-col-width-skip-first">
                                <thead>
                                <tr class="bg-secondary text-white">
                                    <th scope="col">{{__('pages.sl')}}</th>
                                    <th scope="col">{{__('pages.date')}}</th>
                                    <th scope="col">{{__('pages.income')}}</th>
                                    <th scope="col">{{__('pages.expense')}}</th>
                                    <th scope="col">{{__('pages.profit_loss')}}</th>
                                </tr>
                                </thead>
                                <tbody id="last_10_days_profit_loss"></tbody>
                            </table>
                        </div>

                        <div class="mt-4 text-end">
                            <a href="{{route('profitLoss')}}" class="text-brand-primary small">View All</a>
                        </div>
                    </div>
                </div>
            </div>


            <div class="col-md-6 col-12 pr-1">
                <div class="wiz-card">
                    <div class="wiz-card-body">
                        <h5 class="wiz-card-title">{{__('pages.trending_products')}}</h5>

                        <div class="table-responsive">
                            <table class="table table-sm table-bordered text-center wiz-table">
                                <thead>
                                <tr>
                                    <th>{{__('pages.sl')}}</th>
                                    <th>{{__('pages.title')}}</th>
                                    <th>{{__('pages.sku')}}</th>
                                    <th>{{__('pages.sell_qty')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($trending_products as $key => $product)
                                    <tr>
                                        <td>{{$key+1}}</td>
                                        <td>
                                            <a href="{{route('product.show', $product->id)}}">
                                                {{\Illuminate\Support\Str::limit($product->title, 20)}}
                                            </a>
                                        </td>
                                        <td>
                                            <a href="{{route('product.show', $product->id)}}">
                                                {{$product->sku}}
                                            </a>
                                        </td>
                                        <td>{{$product->total_sell_qty}}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-12 pl-1">
                <div class="wiz-card">
                    <div class="wiz-card-body">
                        <h5 class="wiz-card-title">{{__('pages.low_stock_products')}}</h5>

                        <div class="table-responsive">
                            <table class="table table-sm table-bordered text-center wiz-table">
                                <thead>
                                <tr>
                                    <th>{{__('pages.sl')}}</th>
                                    <th>{{__('pages.title')}}</th>
                                    <th>{{__('pages.sku')}}</th>
                                    <th>{{__('pages.current_stock_qty')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($low_stock_products as $key => $product)
                                    <tr>
                                        <td>{{$key+1}}</td>
                                        <td>
                                            <a href="{{route('product.show', $product->id)}}">
                                                {{\Illuminate\Support\Str::limit($product->title, 20)}}
                                            </a>
                                        </td>
                                        <td>
                                            <a href="{{route('product.show', $product->id)}}">
                                                {{$product->sku}}
                                            </a>
                                        </td>
                                        <td>{{$product->current_stock_quantity}}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <!-- /.container-fluid -->
@endsection
@section('js')
    <!-- Page level plugins -->
    <script src="{{asset('/backend/vendor/chart.js/Chart.min.js')}}"></script>
    <script src="{{asset('/backend/js/demo/chart-area-demo.js')}}"></script>
    <script src="{{asset('/backend/js/partial/dashboard.js')}}"></script>
@endsection

