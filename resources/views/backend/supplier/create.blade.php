@extends('backend.layouts.app')
@section('title') {{__('pages.supplier')}}  @endsection
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">
        <div class="wiz-card wiz-card-single">
            <!-- Card Header - Dropdown -->
            <div class="wiz-card-header">
                <h6 class="wiz-card-title">{{__('pages.create_supplier')}}</h6>
                <div>
                    <a href="{{route('supplier.index')}}" class="btn btn-brand btn-brand-secondary btn-sm"><i class="fa fa-list me-1"></i> {{__('pages.all_supplier')}}</a>
                </div>
            </div>
            <!-- Card Body -->
            <div class="wiz-card-body pt-3">
                <form action="{{route('supplier.store')}}" method="post" enctype="multipart/form-data" data-parsley-validate>
                    @csrf

                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="company_name" class="custom-label">{{__('pages.company_name')}} <span class="text-danger">*</span></label>
                                <input type="text" name="company_name" id="company_name" value="{{old('company_name')}}" placeholder="{{__('pages.company_name')}}" class="form-control form-control-lg" aria-describedby="emailHelp" required>
                                @if ($errors->has('company_name'))
                                    <div class="error">{{ $errors->first('company_name') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="contact_person" class="custom-label">{{__('pages.contact_person')}}</label>
                                <input type="text" name="contact_person" id="contact_person" value="{{old('contact_person')}}" placeholder="{{__('pages.contact_person')}}" class="form-control form-control-lg" aria-describedby="emailHelp">
                                @if ($errors->has('contact_person'))
                                    <div class="error">{{ $errors->first('contact_person') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="phone" class="custom-label">{{__('pages.phone_number')}} <span class="text-danger">*</span></label>
                                <input type="text" name="phone" id="phone" value="{{old('phone')}}" placeholder="{{__('pages.phone_number')}}" class="form-control form-control-lg" aria-describedby="emailHelp" required>
                                @if ($errors->has('phone'))
                                    <div class="error">{{ $errors->first('phone') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email" class="custom-label">{{__('pages.email')}}</label>
                                <input type="email" name="email" id="email" value="{{old('email')}}" placeholder="{{__('pages.email')}}" class="form-control form-control-lg" aria-describedby="emailHelp">
                                @if ($errors->has('email'))
                                    <div class="error">{{ $errors->first('email') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="address" class="custom-label">{{__('pages.address')}}</label>
                                <textarea name="address" id="address" class="form-control form-control-lg" placeholder="{{__('pages.address')}}">{{old('address')}}</textarea>
                                @if ($errors->has('address'))
                                    <div class="error">{{ $errors->first('address') }}</div>
                                @endif
                            </div>
                        </div>


                        <div class="col-7 col-sm-4 col-lg-3 col-xl-2 my-4">
                            <div class="ratio ratio-1x1">
                                <label class="upload-with-preview">
                                    <input type="file" name="logo" id="logo" accept="image/*" class="upload-img-input">
                                    <img src="" alt="File" class="preview-image">
                                    <div class="preview-label-text">
                                        <span><i class="bi bi-cloud-arrow-up-fill text-brand-primary fa-2x lh-sm"></i></span>
                                        <span>Profile Picture</span>
                                        <small class="extra-small text-light-muted">Drag & Drop Image Here</small>
                                        <span class="upload-btn">Browse</span>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group d-flex justify-content-end py-2">
                        <button type="submit" class="btn btn-brand btn-brand-primary">{{__('pages.save')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
@endsection


@section('js')
    <script src="{{asset('/backend/js/custom.js')}}"></script>
@endsection

