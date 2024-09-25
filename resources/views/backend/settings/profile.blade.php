@extends('backend.layouts.app')
@section('title') {{__('pages.settings')}} @endsection
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid settings-page">
        <div class="mb-3">
            @include('backend.settings.partial.user-account-nav')
        </div>
        <div class="wiz-card">
            <div class="wiz-card-header">
                <h5 class="wiz-card-title">Update Profile</h5>
            </div>
            <div class="wiz-card-body p-lg-4">
            <form action="{{route('update-profile')}}" method="post" enctype="multipart/form-data" data-parsley-validate>
                @csrf

                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name" class="custom-label">{{__('pages.name')}} <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" value="{{$employee->user->name}}" placeholder="{{__('pages.name')}}" class="form-control" aria-describedby="emailHelp" required>
                            @if ($errors->has('name'))
                                <div class="error">{{ $errors->first('name') }}</div>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="gender" class="custom-label">{{__('pages.gender')}} <span class="text-danger">*</span></label>
                            <select name="gender" id="gender" class="form-control" required>
                                <option value="">{{__('pages.select_gender')}}</option>
                                <option value="Male" {{$employee->gender == 'Male' ? 'selected' : ''}}>Male</option>
                                <option value="Female" {{$employee->gender == 'Male' ? 'Female' : ''}}>Female</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="date_of_birth" class="custom-label">{{__('pages.date_of_birth')}} <span class="text-danger">*</span></label>
                            <input name="date_of_birth" value="{{\Carbon\Carbon::parse($employee->date_of_birth)->toDateString()}}" id="date_of_birth" type="text" data-date-format="yyyy-mm-dd" class="datepicker form-control" placeholder="{{__('pages.date_of_birth')}}" required autocomplete="off">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="blood_group" class="custom-label">{{__('pages.blood_group')}}</label>
                            <select name="blood_group" id="blood_group" class="form-select">
                                <option value="">{{__('pages.select_blood_group')}}</option>
                                <option value="A+" {{$employee->blood_group == 'A+' ? 'selected' : ''}}>A+</option>
                                <option value="A-" {{$employee->blood_group == 'A-' ? 'selected' : ''}}>A-</option>
                                <option value="B+" {{$employee->blood_group == 'B+' ? 'selected' : ''}}>B+</option>
                                <option value="B-" {{$employee->blood_group == 'B-' ? 'selected' : ''}}>B-</option>
                                <option value="AB+" {{$employee->blood_group == 'AB+' ? 'selected' : ''}}>AB+</option>
                                <option value="AB-" {{$employee->blood_group == 'AB-' ? 'selected' : ''}}>AB-</option>
                                <option value="O+" {{$employee->blood_group == 'O+' ? 'selected' : ''}}>O+</option>
                                <option value="O-" {{$employee->blood_group == 'O+' ? 'selected' : ''}}>O-</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="phone_number" class="custom-label">{{__('pages.phone_number')}}</label>
                            <input type="text" name="phone_number" id="phone_number"value="{{$employee->phone_number}}" placeholder="{{__('pages.phone_number')}}" class="form-control" aria-describedby="emailHelp">
                            @if ($errors->has('phone_number'))
                                <div class="error">{{ $errors->first('phone_number') }}</div>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="address" class="custom-label">{{__('pages.address')}} <span class="text-danger">*</span> </label>
                            <input type="text" name="address" id="address" value="{{$employee->address}}" placeholder="{{__('pages.address')}}" required class="form-control" aria-describedby="emailHelp">
                            @if ($errors->has('address'))
                                <div class="error">{{ $errors->first('address') }}</div>
                            @endif
                        </div>
                    </div>


                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="educational_background" class="custom-label"> {{__('pages.educational_background')}}</label>
                            <input type="text" name="educational_background" class="form-control" placeholder="{{__('pages.educational_background')}}">
                        </div>
                    </div>

                    <div class="col-md-3 mt-3">
                        <label for="profile_picture" class="custom-label">{{__('pages.profile_picture')}} <span class="text-danger">*</span></label>
                        <div class="ratio ratio-1x1">
                            <label class="upload-with-preview">
                                <input type="file" name="profile_picture" id="profile_picture" accept="image/*" class="upload-img-input">
                                @if($employee->profile_picture)
                                    <img src="{{asset($employee->profile_picture)}}" class="preview-image">
                                @else
                                    <img src="" class="preview-image">
                                @endif
                                <div class="preview-label-text">
                                    <span><i class="bi bi-cloud-arrow-up-fill text-brand-primary fa-2x lh-sm"></i></span>
                                    <span>{{__('pages.profile_picture')}}</span>
                                    <small class="extra-small text-light-muted">Drag & Drop Image Here</small>
                                    <span class="upload-btn">Browse</span>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group text-end">
                            <button type="submit" class="btn btn-brand-primary btn-brand">{{__('pages.save')}}</button>
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
    <script src="{{asset('/backend/js/custom.js')}}"></script>
@endsection

