@extends('backend.layouts.app')
@section('title') {{__('pages.language')}}  @endsection
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="wiz-card">
                    <!-- Card Header - Dropdown -->
                    <div class="wiz-card-header">
                        <h6 class="wiz-card-title">{{__('pages.add_language')}}</h6>
                        <div>
                            <a href="{{route('language.index')}}" class="btn btn-brand-secondary btn-sm btn-brand"> <i class="fa fa-list me-1"></i> {{__('pages.all_language')}}</a>
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="wiz-card-body">
                        <form action="{{route('language.update', $language->id)}}" method="post" enctype="multipart/form-data" data-parsley-validate>
                            @csrf
                            @method('patch')

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="name" class="custom-label">{{__('pages.language')}} <span class="text-danger">*</span></label>
                                        <input type="text" name="language" id="language" value="{{$language->language}}" placeholder="{{__('pages.language')}}" class="form-control form-control-lg" aria-describedby="emailHelp" required>
                                        @if ($errors->has('language'))
                                            <div class="error">{{ $errors->first('language') }}</div>
                                        @endif
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="phone" class="custom-label">{{__('pages.iso_code')}} <span class="text-danger">*</span></label>
                                        <input type="text" name="iso_code" id="iso_code" value="{{$language->iso_code}}" placeholder="{{__('pages.iso_code')}}" class="form-control form-control-lg" aria-describedby="emailHelp">
                                        @if ($errors->has('iso_code'))
                                            <div class="error">{{ $errors->first('iso_code') }}</div>
                                        @endif
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="phone" class="custom-label">{{__('pages.rtl_support')}} <span class="text-danger">*</span></label>
                                        <select name="is_rtl_support" class="form-control">
                                            <option value="no" {{$language->is_rtl_support == 'no' ? 'selected' : '' }} >No</option>
                                            <option value="yes" {{$language->is_rtl_support == 'yes' ? 'selected' : '' }}>Yes</option>
                                        </select>
                                    </div>


                                    <div class="col-md-4 my-4">
                                        <div class="ratio ratio-16x9">
                                            <label class="upload-with-preview">
                                                <input type="file" name="flag" id="flag" accept="image/*" class="upload-img-input">
                                                <img src="{{$language->flag ? asset($language->flag): null}}" class="preview-image">
                                                <div class="preview-label-text">
                                                    <span><i class="bi bi-cloud-arrow-up-fill text-brand-primary fa-2x lh-sm"></i></span>
                                                    <span>Chose Flag</span>
                                                </div>
                                            </label>
                                        </div>
                                    </div>


                                    <div class="form-group text-end pb-3">
                                        <button type="submit" class="btn btn-brand-primary btn-brand">{{__('pages.save')}}</button>
                                    </div>
                                </div>


                                <div class="col-md-6">
                                    <div class="text-end mb-4">
                                        <a href="https://en.wikipedia.org/wiki/ISO_3166-1#Current_codes" target="_blank" class="text-brand-primary"><i class="bi bi-globe-americas me-1"></i> ISO Code List</a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
@endsection

@section('js')

@endsection

