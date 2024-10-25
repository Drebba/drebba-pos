@extends('backend.layouts.app')
@section('title') {{__('pages.product')}} @endsection
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <div class="wiz-box p-4 mb-4">
            <div class="row g-3">
                <div class="col-sm-8 col-lg-9 col-xl-7">
                    <form action="{{route('product.index')}}">
                        <div class="row g-3">
                            <div class="col-lg-6">
                                <div class="input-with-icon">
                                    <span class="input-icon"><i class="bi bi-search"></i></span>
                                    <input type="text" name="search_key" value="{{Request::get('search_key')}}"  class="form-control" placeholder="{{__('pages.product_search_kye')}}">
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="form-group">
                                    <select name="category_id" class="form-select select2-basic">
                                        <option value=""> {{__('pages.all_category')}}</option>
                                        @foreach($categories as $category)
                                            <option value="{{$category->id}}" {{Request::get('category_id') ==  $category->id ? 'selected' : ''}}>{{$category->title}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-2">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-brand btn-brand-primary w-100 w-md-auto">{{__('pages.search')}}</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-sm-4 col-lg-3 col-xl-5 text-sm-end">
                    <a href="{{route('product.create')}}" class="btn btn-brand-secondary btn-brand w-100 w-md-auto"><i class="fa fa-plus me-1"></i> {{__('pages.create_product')}}</a>
                </div>
            </div>
        </div>

        <div class="mb-4">
            <div class="row g-4 products" >
                @forelse($products as $key => $product)
                    <div class="col-sm-6 col-lg-4 col-xl-3">

                        <div class="product-card">
                            <div class="product-card-header">
                                <a href="{{route('product.show', [$product->id])}}" class="product-card-image-group">
                                    <img src="{{asset($product->thumbnail ? $product->thumbnail : 'backend/img/blank-thumbnail.jpg')}}">
                                </a>
                            </div>
                            <div class="product-card-body">
                                <h2 class="product-title text-center">{{ Str::limit($product->title, 50)}}<br></h2>

                                <div class="d-flex flex-column gap-1">
                                    <div class="text-center">{{__('pages.sku')}}: {{$product->sku}}</div>
                                    <div class="d-flex justify-content-center">
                                        <span class="text-brand-muted me-2"> {{__('pages.purchase')}} :</span>
                                        <span>{{get_option('app_currency')}}{{$product->purchase_price}}</span>
                                    </div>
                                    <div class="d-flex justify-content-center">
                                        <span class="text-brand-muted me-2"> {{__('pages.sell')}}:</span>
                                        <span>{{get_option('app_currency')}}{{$product->sell_price}}</span>
                                    </div>

                                    <div class="d-flex justify-content-center">
                                        <span class="text-brand-muted me-2">{{__('pages.stock_quantity')}}:</span>
                                        <span>{{cuurentStock($product)}}  {{$product->unit->title ?? ''}}</span>
                                    </div>
                                </div>

                                <ul class="list-inline text-center mt-3">
                                    <li class="list-inline-item"><a href="{{route('product.edit', [$product->id])}}"><i class="bi bi-pencil-square"></i></a></li>
                                    <li class="list-inline-item"><a href="{{route('product.show', [$product->id])}}" class="text-brand-muted"><i class="bi bi-eye"></i></a></li>
                                    <li class="list-inline-item"><a href="javascript:void(0)" class="download-bar-code" data-id="{{$product->id}}"><i class="fa fa-barcode text-secondary" aria-hidden="true"></i></a></li>
                                    <li class="list-inline-item">
                                        <a href="javascript:void(0);" onclick="$(this).confirmDelete($('#delete-{{$key}}'))" class="text-danger"><i class="bi bi-trash3"></i></a>
                                        <form action="{{ route('product.destroy',$product->id) }}" method="post" id="delete-{{$key}}"> @csrf @method('delete') </form>
                                    </li>

                                </ul>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-md-12 py-5 text-center">
                        <h1 class="text-warning py-5">No product found</h1>
                    </div>
                @endforelse
            </div>
        </div>

        <div class="my-4 d-flex justify-content-center">
            {{$products->appends(Request::all())->links()}}
        </div>
    </div>
    <!-- /.container-fluid -->



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
                            <div class="mb-4">
                                <input type="number" name="quantity" value="1" min="1" max="500" class="form-control form-control-lg" placeholder="Input Barcode Quantity" required>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-brand btn-brand-primary">Download</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-brand btn-soft-danger" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <input type="hidden" id="baseURL" value="{{url('/')}}">
@endsection

@section('js')
    <script src="{{asset('/backend/js/custom.js')}}"></script>
@endsection

