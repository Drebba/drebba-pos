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
                        <h6 class="wiz-card-title">{{__('pages.translate_your_language')}} ( English => {{$language->language}} ) </h6>
                        <div>
                            <a href="{{route('language.index')}}" class="btn btn-brand-secondary btn-sm btn-brand"> <i class="fa fa-list me-1"></i> {{__('pages.all_language')}}</a>
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="wiz-card-body p-4">
                        <form action="{{route('update-translate')}}" method="post" enctype="multipart/form-data" data-parsley-validate>
                            @csrf

                            <input type="hidden" name="language_id" value="{{$language->id}}">
                            <div class="row gx-3 gy-2 gx-lg-5">
                                @foreach($language_array as $key => $value)
                                    <div class="col-md-6">
                                        <div class="form-group row justify-content-center">
                                            <label class="col-sm-5 col-form-label text-brand-dark">{{ucwords(str_replace("_", " ", $key))}}  => </label>
                                            <div class="col-sm-7">
                                                <input type="text" name="{{$key}}" value="{{$value}}" placeholder="{{__('pages.title')}}" class="form-control text-brand-dark" aria-describedby="emailHelp" required>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                            </div>

                            <div class="form-group my-4 text-end">
                                <button type="submit" class="btn btn-brand-primary btn-brand">{{__('pages.update')}}</button>
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

