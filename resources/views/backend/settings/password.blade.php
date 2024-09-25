@extends('backend.layouts.app')
@section('title') Settings @endsection
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid settings-page">
        <div class="mb-3">
            @include('backend.settings.partial.user-account-nav')
        </div>

        <div class="wiz-card">
            <div class="wiz-card-header">
                <h5 class="wiz-card-title">Change Password</h5>
            </div>
            <div class="wiz-card-body p-lg-4">


                <form action="{{route('update-password')}}" method="post" class="form-horizontal" enctype="multipart/form-data"  data-parsley-validate>
                    @csrf

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="current_password" class="custom-label">{{__('pages.current_password')}} <span class="text-danger">*</span></label>
                                <input required name="current_password" minlength="5" type="password" class="form-control" placeholder="{{__('pages.current_password')}}">
                            </div>


                            <div class="form-group mb-3">
                                <label for="password" class="custom-label">{{__('pages.new_password')}}<span class="text-danger">*</span></label>
                                <input required name="password" id="password" minlength="5" type="password" class="form-control"
                                       placeholder="{{__('pages.new_password')}}">
                            </div>



                            <div class="form-group mb-4">
                                <label for="cnfirm_password" class="custom-label">{{__('pages.re_type_password')}} <span class="text-danger">*</span></label>
                                <input required name="c_password" minlength="5" data-parsley-equalto="#password" type="password" class="form-control"
                                       placeholder="{{__('pages.re_type_password')}}">
                            </div>

                            <div class="form-group text-end">
                                <button type="submit" class="btn btn-brand-primary btn-brand">{{__('pages.save_and_update')}}</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
@endsection

@section('js')

@endsection

