@extends('backend.layouts.app')
@section('title') {{__('pages.product')}} @endsection
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid profile">

        <div class="row g-3">
            <div class="col-md-3">
                <div class="wiz-card h-auto">
                    <div class="wiz-card-header">
                        <h5 class="wiz-card-title">Product</h5>
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
                                <tr>
                                    <td>{{__('pages.purchase_price')}}:</td>
                                    <td>{{get_option('app_currency')}}{{$product->purchase_price}}</td>
                                </tr>
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
                                <div class="flex-fill">
                                    <a href="javascript:void(0)" data-id="{{$product->id}}" class="btn btn-brand btn-brand-secondary btn-sm download-bar-code w-100">
                                        {{__('pages.barcode')}}
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
                                    <h6 class="summary-card-title">{{__('pages.purchase_quantity')}}</h6>
                                    <h3 class="summary-card-value">
                                        {{$product->total_purchase_qty ?? 0}}
                                        <small>{{$product->unit ? $product->unit->title : ''}}</small>
                                    </h3>
                                </div>
                                <div>
                                    <span class="summary-card-icon btn-soft-secondary"><i class="bi bi-cart-check"></i></span>
                                </div>
                            </div>
                        </div>


                        <div class="col-xl-4 col-md-6">
                            <div class="summary-card">
                                <div class="me-3">
                                    <h6 class="summary-card-title">{{__('pages.stock_quantity')}}</h6>
                                    <h3 class="summary-card-value">
                                        {{$product->current_stock_quantity ?? 0}}
                                        <small>{{$product->unit ? $product->unit->title : ''}}</small>
                                    </h3>
                                </div>
                                <div>
                                    <span class="summary-card-icon btn-soft-success"><i class="bi bi-boxes"></i></span>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-4 col-md-6">
                            <div class="summary-card">
                                <div class="me-3">
                                    <h6 class="summary-card-title">{{__('pages.current_stock_value')}} <sub>(Apx)</sub></h6>
                                    <h3 class="summary-card-value">
                                        {{get_option('app_currency')}} {{$product->current_stock_quantity * $product->sell_price}}
                                    </h3>
                                </div>
                                <div>
                                    <span class="summary-card-icon btn-soft-success"><i class="fa-solid fa-hand-holding-dollar"></i></span>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-4 col-md-6">
                            <div class="summary-card">
                                <div class="me-3">
                                    <h6 class="summary-card-title">{{__('pages.total_purchase_amount')}}</h6>
                                    <h3 class="summary-card-value">
                                        {{get_option('app_currency')}} {{number_format($product->purchaseProducts->sum('total_price'),2)}}
                                    </h3>
                                </div>
                                <div>
                                    <span class="summary-card-icon btn-soft-danger"><i class="bi bi-credit-card"></i></span>
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

                    <div class="wiz-box mb-3">
                        <div class="table-responsive">
                                <table class="table table-bordered table-hover wiz-table mb-0">
                                    <thead>
                                    <tr class="bg-secondary text-white">
                                        <th>{{__('pages.sl')}}</th>
                                        <th class="text-center">Date</th>
                                        <th class="text-center">{{__('pages.stock_quantity')}}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($product->purchaseProducts as $key => $prod)
                                        <tr class="@if($prod->stock_quantity < 1 ) bg-danger text-white @elseif($prod->stock_quantity < 20) bg-warning text-white @else  @endif">
                                            <th>{{$key + 1}}</th>
                                            <td class="text-center">@formatdate($prod->created_at)</td>
                                            <td class="text-center">{{$prod->quantity}}</td>
                                        </tr>
                                    @endforeach

                                    <tr>
                                        <td class="border-end-0"></td>
                                        <td class="text-center border-start-0"><b>{{__('pages.total')}}:</b></td>
                                        <td class="text-center">
                                            <b>{{$product->purchaseProducts->sum('quantity') }}</b>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
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

    <!-- Modal -->
    <div class="modal fade" id="barCodeQty" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Input Barcode Quantity</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="" id="productBarCode" method="get">
                        <div>
                            <div class="mb-3">
                                <input type="number" name="quantity" value="1" min="1" max="500" class="form-control form-control-lg" placeholder="Input Barcode Quantity" required>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-brand btn-brand-secondary">Download</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-brand btn-sm btn-soft-danger" data-bs-dismiss="modal">Close</button>
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

