@extends('backend.layouts.app')
@section('title') {{__('pages.product')}}  @endsection
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">
        <form action="{{route('product.update', [$product->id])}}" method="post" enctype="multipart/form-data" data-parsley-validate>
            @csrf
            @method('patch')


            <div class="wiz-card">
                <!-- Card Header - Dropdown -->
                <div class="wiz-card-header">
                    <h6 class="page-title">{{__('pages.update_product')}}</h6>
                </div>
                <!-- Card Body -->
                <div class="wiz-card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="title" class="custom-label">{{__('pages.product_title')}} <span class="text-danger">*</span></label>
                                <input type="text" name="title" id="title" value="{{$product->title}}" placeholder="{{__('pages.product_title')}}" class="form-control" aria-describedby="emailHelp">
                                @if ($errors->has('title'))
                                    <div class="error">{{ $errors->first('title') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="sku" class="custom-label">{{__('pages.sku_or_product_code')}}<span class="text-danger">*</span></label>
                                <input type="text" name="sku" id="sku" value="{{$product->sku}}" maxlength="15" placeholder="{{__('pages.sku_or_product_code')}}" class="form-control" aria-describedby="emailHelp">
                                @if ($errors->has('sku'))
                                    <div class="error">{{ $errors->first('sku') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="category" class="custom-label">{{__('pages.category')}} <span class="text-danger">*</span></label>
                                <select name="category_id" id="category" class="form-control select2">
                                    <option value="">{{__('pages.select_one')}} </option>
                                    @foreach($categories as $category)
                                        <option value="{{$category->id}}" {{$product->category_id == $category->id ? 'selected' : ''}}>{{$category->title}}</option>
                                    @endforeach
                                </select>

                                @if ($errors->has('category_id'))
                                    <div class="error">{{ $errors->first('category_id') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="tax_id" class="custom-label">{{__('pages.tax')}} <span class="text-danger">*</span></label>
                                <select name="tax_id" id="tax_id" class="form-control select2">
                                    <option value="">{{__('pages.select_one')}}</option>
                                    @foreach($taxes as $tax)
                                        <option value="{{$tax->id}}" {{$product->tax_id == $tax->id ? 'selected' : ''}}>{{$tax->title}}</option>
                                    @endforeach
                                </select>

                                @if ($errors->has('tax_id'))
                                    <div class="error">{{ $errors->first('tax_id') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="purchase_price" class="custom-label">{{__('pages.purchase_price')}} <span class="text-danger">*</span></label>
                                <input type="number" step=".01"  min="0" value="{{$product->purchase_price}}" name="purchase_price" id="purchase_price" placeholder="{{__('pages.purchase_price')}}" class="form-control">
                                @if ($errors->has('purchase_price'))
                                    <div class="error">{{ $errors->first('purchase_price') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="sell_price" class="custom-label">{{__('pages.sell_price')}} <span class="text-danger">*</span></label>
                                <input type="number" step=".01"  min="0" name="sell_price" value="{{$product->sell_price}}" id="sell_price" placeholder="{{__('pages.sell_price')}}" class="form-control">
                                @if ($errors->has('sell_price'))
                                    <div class="error">{{ $errors->first('sell_price') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="price_type" class="custom-label">{{__('pages.sell_price_type')}} <span class="text-danger">*</span></label>
                                <select name="price_type" id="price_type" class="form-control select2">
                                    <option value="1" {{$product->price_type == 1 ? 'selected' : ''}}>Fixed</option>
                                    <option value="2" {{$product->price_type == 2 ? 'selected' : ''}}>Negotiable</option>
                                </select>

                                @if ($errors->has('price_type'))
                                    <div class="error">{{ $errors->first('price_type') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="unit_id" class="custom-label">{{__('pages.unit')}} <span class="text-danger">*</span></label>
                                <select name="unit_id" id="unit_id" class="form-control select2">
                                    <option value="">{{__('pages.select_one')}}</option>
                                    @foreach($units as $unit)
                                        <option value="{{$unit->id}}" {{$product->unit_id == $unit->id ? 'selected' : ''}}>{{$unit->title}}</option>
                                    @endforeach
                                </select>

                                @if ($errors->has('unit_id'))
                                    <div class="error">{{ $errors->first('unit_id') }}</div>
                                @endif
                            </div>
                        </div>



                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="short_description" class="custom-label">{{__('pages.short_description')}}</label>
                                <textarea name="short_description" id="short_descriptionress" value="{{old('short_description')}}" class="form-control" placeholder="Short Description">{{$product->short_description}}</textarea>
                                @if ($errors->has('short_description'))
                                    <div class="error">{{ $errors->first('short_description') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="col-7 col-sm-4 col-lg-3 col-xl-2 my-4">
                            <div class="ratio ratio-1x1">
                                <label class="upload-with-preview">
                                    <input type="file" name="thumbnail" id="thumbnail" accept="image/*" class="upload-img-input">
                                    @if($product->thumbnail)
                                        <img src="{{asset($product->thumbnail)}}" alt="File" class="preview-image">
                                    @else
                                        <img src="" alt="File" class="preview-image">
                                    @endif
                                    <div class="preview-label-text">
                                        <span><i class="bi bi-cloud-arrow-up-fill text-brand-primary fa-2x lh-sm"></i></span>
                                        <span>{{__('pages.photo')}}</span>
                                        <small class="extra-small text-light-muted">Drag & Drop Image Here</small>
                                        <span class="upload-btn">Browse</span>
                                    </div>
                                </label>
                            </div>
                        </div>


                        <div class="d-flex justify-content-end py-4">
                            @edit
                        </div>
                    </div>
                </div>
            </div>

        </form>
    </div>
    <!-- /.container-fluid -->
@endsection


@section('js')
    <script src="{{asset('/backend/js/custom.js')}}"></script>
@endsection

