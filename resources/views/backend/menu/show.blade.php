@extends('backend.layouts.app')
@section('title') {{__('pages.product')}} @endsection
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid profile">

        <div class="row g-3">
            <div class="col-md-3">
                <div class="wiz-card h-auto">
                    <div class="wiz-card-header">
                        <h5 class="wiz-card-title">Menu</h5>
                    </div>
                    <div class="wiz-card-body">
                        <div class="col-7 col-md-9 mx-auto p-0">
                            <div class="ratio ratio-1x1 mb-3">
                                <div class="avatar-box rounded">
                                    <img src="{{asset($product->thumbnail ? $product->thumbnail : 'backend/img/blank-thumbnail.jpg')}}" class="rounded-0 img-fit-center">
                                </div>
                            </div>
                        </div>

                        <div>
                            <h5 class="text-center company-name">{{$product->title}}</h5>
                            <div class="text-center text-muted small mb-4">{{__('pages.created_at')}}: {{$product->created_at->diffForHumans()}}</div>

                            <table class="table table-sm table-bordered wiz-table">
                                <tr>
                                    <td>{{__('pages.sku')}}:</td>
                                    <td>{{$product->sku}}</td>
                                </tr>
                                <tr>
                                    <td>{{__('pages.category')}}:</td>
                                    <td>{{$product->category ? $product->category->title : '--'}}</td>
                                </tr>
                                <tr>
                                    <td>{{__('pages.unit')}}:</td>
                                    <td>{{$product->unit ? $product->unit->title : '--'}}</td>
                                </tr>
                                {{-- <tr>
                                    <td>{{__('pages.purchase_price')}}:</td>
                                    <td>{{get_option('app_currency')}}{{$product->purchase_price}}</td>
                                </tr> --}}
                                <tr>
                                    <td>{{__('pages.sell_price')}}:</td>
                                    <td>{{get_option('app_currency')}}{{$product->sell_price}}</td>
                                </tr>
                                <tr>
                                    <td>{{__('pages.sell_price_type')}}:</td>
                                    <td>{{$product->price_type == 1 ? 'Fixed' : 'Negotiable'}}</td>
                                </tr>
                                <tr>
                                    <td>{{__('pages.tax')}}:</td>
                                    <td>{{$product->tax->title}}</td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="p-2 text-center">
                                        {{$product->short_description}}
                                    </td>
                                </tr>
                            </table>


                            <div class="d-flex gap-1 flex-wrap justify-content-between">
                                <div class="flex-fill">
                                    @if($product->status == 1)
                                        <a href="javascript:void(0)" onclick="$(this).confirm('{{url('change-product-status/'.$product->id)}}');" class="btn btn-brand-success btn-brand btn-sm w-100">
                                            {{__('pages.active')}}
                                        </a>
                                    @else
                                        <a href="javascript:void(0)" onclick="$(this).confirm('{{url('change-product-status/'.$product->id)}}');" class="btn btn-brand btn-brand-danger btn-sm w-100">
                                            {{__('pages.inactive')}}
                                        </a>
                                    @endif
                                </div>
                                <div class="flex-fill">
                                    <a href="{{route('product.edit', $product->id)}}"  class="btn btn-brand btn-brand-warning btn-sm w-100" target="_blank">
                                        {{__('pages.edit')}}
                                    </a>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-9">
                <div>
                    <div class="row g-3 mb-3">
                        <div class="col-xl-4 col-md-6">
                            <div class="summary-card">
                                <div class="me-3">
                                    <h6 class="summary-card-title">{{__('pages.sell_quantity')}}</h6>
                                    <h3 class="summary-card-value">
                                        {{$product->total_sell_qty ?? 0}}
                                        <small>{{$product->unit ? $product->unit->title : ''}}</small>
                                    </h3>
                                </div>
                                <div>
                                    <span class="summary-card-icon btn-soft-primary"><i class="fa-solid fa-hand-holding-dollar"></i></span>
                                </div>
                            </div>
                        </div>



                        <div class="col-xl-4 col-md-6">
                            <div class="summary-card">
                                <div class="me-3">
                                    <h6 class="summary-card-title">{{__('pages.total_sell_amount')}}</h6>
                                    <h3 class="summary-card-value">
                                        {{get_option('app_currency')}} {{number_format($product->sellProducts->sum('total_price'),2)}}
                                    </h3>
                                </div>
                                <div>
                                    <span class="summary-card-icon btn-soft-primary"><i class="bi bi-currency-dollar"></i></span>
                                </div>
                            </div>
                        </div>


                    </div>

                    <!-- Content Row -->

                    <div class="wiz-card h-auto">
                        <!-- Card Header - Dropdown -->
                        <div class="wiz-card-header">
                            <h6 class="wiz-card-title">{{__('pages.sales_summary_last_30_days')}}</h6>
                        </div>
                        <!-- Card Body -->
                        <div class="wiz-card-body">
                            <div class="ratio ratio-16x9">
                                <div>
                                    <canvas id="myAreaChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <input type="hidden"  id="productID" value="{{$product->id}}">
    <input type="hidden"  id="baseURL" value="{{url('/')}}">
    <!-- /.container-fluid -->
@endsection

@section('js')
    <!-- Page level plugins -->
    <script src="{{asset('backend/vendor/chart.js/Chart.min.js')}}"></script>
    <script src="{{asset('backend/js/demo/chart-area-demo.js')}}"></script>
    <script src="{{asset('backend/js/demo/chart-pie-demo.js')}}"></script>
    <script src="{{asset('/backend/js/partial/product.js')}}"></script>
    <script src="{{asset('/backend/js/custom.js')}}"></script>
@endsection
