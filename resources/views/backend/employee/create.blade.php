@extends('backend.layouts.app')
@section('title') {{__('pages.employee')}}  @endsection
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">
        <form action="{{route('employee.store')}}" method="post" enctype="multipart/form-data" data-parsley-validate>
            @csrf
            <div class="wiz-card">
                <!-- Card Header - Dropdown -->
                <div class="wiz-card-header">
                    <h6 class="page-title">{{__('pages.create_employees')}}</h6>
                    <a href="{{route('employee.index')}}" class="btn btn-sm btn-brand btn-soft-primary"><i class="fa-regular fa-rectangle-list me-1"></i> {{__('pages.employees')}}</a>
                </div>

                <!-- Card Body -->
                <div class="wiz-card-body flex-grow-0">
                    <div class="form-section">
                        <h5 class="form-section-title">{{__('pages.personal_information')}}</h5>
                        <div class="row g-3">
                            <div class="col-md-4 col-lg-3">
                                <div class="form-group">
                                    <label for="name" class="custom-label">{{__('pages.name')}} <span class="text-danger">*</span></label>
                                    <input type="text" name="name" id="name" value="{{old('name')}}" placeholder="{{__('pages.full_name')}}" class="form-control" aria-describedby="emailHelp">
                                    @if ($errors->has('name'))
                                        <div class="error">{{ $errors->first('name') }}</div>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-4 col-lg-3">
                                <div class="form-group">
                                    <label for="gender" class="custom-label">{{__('pages.gender')}}</label>
                                    <select name="gender" id="gender" class="form-control select2">
                                        <option value="">{{__('pages.select_gender')}}</option>
                                        <option value="Male" {{old('gender') == 'Male' ? 'selected' : ''}}>Male</option>
                                        <option value="Female" {{old('gender') == 'Female' ? 'selected' : ''}} >Female</option>
                                    </select>

                                    @if ($errors->has('gender'))
                                        <div class="error">{{ $errors->first('gender') }}</div>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-4 col-lg-3">
                                <div class="form-group">
                                    <label for="date_of_birth" class="custom-label">{{__('pages.date_of_birth')}} <span class="text-danger">*</span></label>
                                    <input name="date_of_birth" id="date_of_birth" type="text" value="{{old('date_of_birth')}}" data-date-format="yyyy-mm-dd" class="datepicker form-control" placeholder="{{__('pages.date_of_birth')}}" autocomplete="off">
                                    @if ($errors->has('date_of_birth'))
                                        <div class="error">{{ $errors->first('date_of_birth') }}</div>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-4 col-lg-3">
                                <div class="form-group">
                                    <label for="blood_group" class="custom-label">{{__('pages.blood_group')}} <span class="text-danger">*</span></label>
                                    <select name="blood_group" id="blood_group" class="form-control select2">
                                        <option value="">{{__('pages.blood_group')}}</option>
                                        <option value="A+" {{old('blood_group') == 'A+' ? 'selected' : ''}} >A+</option>
                                        <option value="A-" {{old('blood_group') == 'A-' ? 'selected' : ''}} >A-</option>
                                        <option value="B+" {{old('blood_group') == 'B+' ? 'selected' : ''}} >B+</option>
                                        <option value="B-" {{old('blood_group') == 'B-' ? 'selected' : ''}} >B-</option>
                                        <option value="AB+" {{old('blood_group') == 'AB+' ? 'selected' : ''}} >AB+</option>
                                        <option value="AB-" {{old('blood_group') == 'AB-' ? 'selected' : ''}} >AB-</option>
                                        <option value="O+" {{old('blood_group') == 'O+' ? 'selected' : ''}} >O+</option>
                                        <option value="O-" {{old('blood_group') == 'O-' ? 'selected' : ''}} >O-</option>
                                    </select>

                                    @if ($errors->has('date_of_birth'))
                                        <div class="error">{{ $errors->first('date_of_birth') }}</div>
                                    @endif

                                </div>
                            </div>

                            <div class="col-md-4 col-lg-3">
                                <div class="form-group">
                                    <label for="phone_number" class="custom-label">{{__('pages.phone_number')}} <span class="text-danger">*</span></label>
                                    <input type="text" name="phone_number" id="phone_number" value="{{old('phone_number')}}" placeholder="{{__('pages.phone_number')}}" class="form-control" aria-describedby="emailHelp">
                                    @if ($errors->has('phone_number'))
                                        <div class="error">{{ $errors->first('phone_number') }}</div>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-4 col-lg-3">
                                <div class="form-group">
                                    <label for="address" class="custom-label">{{__('pages.address')}} <span class="text-danger">*</span> </label>
                                    <input type="text" name="address" id="address"  value="{{old('address')}}" placeholder="{{__('pages.address')}}" class="form-control" aria-describedby="emailHelp">
                                    @if ($errors->has('address'))
                                        <div class="error">{{ $errors->first('address') }}</div>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-4 col-lg-3">
                                <div class="form-group">
                                    <label for="educational_background" class="custom-label"> {{__('pages.educational_background')}}</label>
                                    <input type="text" name="educational_background" value="{{old('educational_background')}}" class="form-control" placeholder="{{__('pages.educational_background')}}">
                                </div>
                            </div>
                        </div>

                        <div class="col-7 col-sm-4 col-lg-3 col-xl-2 my-3">
                            <div class="ratio ratio-1x1">
                                <label class="upload-with-preview">
                                    <input type="file" name="profile_picture" id="profile_picture" accept="image/*" class="upload-img-input">
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
                </div>

                <div class="wiz-card-body border-top pt-4 flex-grow-0">
                    <div class="form-section">
                        <h5 class="form-section-title">{{__('pages.employment_info')}}</h5>
                        <div class="row g-3">
                            {{-- <div class="col-md-4 col-lg-3">
                                <div class="form-group">
                                    <label for="department_id" class="custom-label">{{__('pages.department')}} <span class="text-danger">*</span></label>
                                    <select name="department_id" id="department_id" class="form-control select2-basic">
                                        <option value="">{{__('pages.select_department')}}</option>
                                        @foreach($departments as $department)
                                            <option value="{{$department->id}}" {{old('department_id') == $department->id ? 'selected' : ''}}>{{$department->title}}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('department_id'))
                                        <div class="error">{{ $errors->first('department_id') }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-4 col-lg-3">
                                <div class="form-group">
                                    <label for="designation_id" class="custom-label">{{__('pages.designation')}} <span class="text-danger">*</span></label>
                                    <select name="designation_id" id="designation_id" class="form-control select2-basic">
                                        <option value="">{{__('pages.select_designation')}}</option>
                                        @foreach($designations as $designation)
                                            <option value="{{$designation->id}}" {{old('designation_id') == $designation->id ? 'selected' : ''}}>{{$designation->title}}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('designation_id'))
                                        <div class="error">{{ $errors->first('designation_id') }}</div>
                                    @endif
                                </div>
                            </div> --}}


                            <div class="col-md-4 col-lg-4">
                                <div class="form-group">
                                    <label for="role" class="custom-label">{{__('pages.role')}} <span class="text-danger">*</span></label>
                                    <select name="role" id="role" class="form-select select2-basic">
                                        <option value="">{{__('pages.select_role')}}</option>
                                        @foreach($roles as $role)
                                            <option value="{{$role->name}}" {{old('role') == $role->name ? 'selected' : ''}}>{{$role->name}}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('role'))
                                        <div class="error">{{ $errors->first('role') }}</div>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-4 col-lg-4">
                                <div class="form-group">
                                    <label for="id_number" class="custom-label"> {{__('pages.employee_id')}} </label>
                                    <input type="text" name="id_number" value="{{old('id_number')}}" class="form-control" placeholder="{{__('pages.employee_id')}}">
                                </div>
                            </div>

                            <div class="col-md-4 col-lg-4">
                                <div class="form-group">
                                    <label for="joining_date" class="custom-label"> {{__('pages.joining_date')}} <span class="text-danger">*</span> </label>
                                    <input type="text" name="joining_date" value="{{old('joining_date')}}"  data-date-format="yyyy-mm-dd" class="datepicker form-control" placeholder="{{__('pages.joining_date')}}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="wiz-card-body border-top pt-4 flex-grow-0">
                    <div class="form-section">
                        <h5 class="form-section-title">{{__('pages.login_info')}}</h5>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email" class="custom-label">{{__('pages.email')}} <span class="text-danger">*</span></label>
                                    <input type="email" name="email"  value="{{old('email')}}" id="email" class="form-control" placeholder="{{__('pages.email')}}">
                                    @if ($errors->has('email'))
                                        <div class="error">{{ $errors->first('email') }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="password" class="custom-label">{{__('pages.password')}} <span class="text-danger">*</span></label>
                                    <input type="password" name="password" id="password" class="form-control" required placeholder="{{__('pages.password')}}">
                                    @if ($errors->has('password'))
                                        <div class="error">{{ $errors->first('password') }}</div>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="c_password" class="custom-label">{{__('pages.re_type_password')}} <span class="text-danger">*</span></label>
                                    <input type="password" name="c_password" data-parsley-equalto="#password" required  class="form-control" placeholder="{{__('pages.re_type_password')}}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end my-4">
                        @create
                    </div>
                </div>
            </div>
        </form>
    </div>
    <!-- /.container-fluid -->
@endsection

@section('css')
    <link rel="stylesheet" type="text/css" href="{{asset('admin/plugin/select2/select2.min.css')}}" />
    <link rel="stylesheet" type="text/css" href="{{asset('admin/plugin/select2/select2-bootstrap/select2-bootstrap-5-theme.css')}}" />


    {{--========== Datepicker ============--}}
    <link rel="stylesheet" type="text/css" href="{{asset('admin/plugin/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css')}}" />
@endsection
@section('js')
    <script src="{{asset('/backend/js/custom.js')}}"></script>

    <script src="{{asset('admin/plugin/select2/select2.min.js')}}"></script>
    <script>
        $('.select2-basic').select2({
            theme: "bootstrap-5",
            width: $( this ).data( 'width' ) ? $( this ).data( 'width' ) : $( this ).hasClass( 'w-100' ) ? '100%' : 'style',
            placeholder: $( this ).data( 'placeholder' ),
        });
    </script>

    {{--============== Datepicker ================--}}
    <script src="{{asset('admin/plugin/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js')}}"></script>
    <script>
        $('.datepicker').datepicker({
            format:'yyyy-mm-dd',
            zIndexOffset: 1198,
        }).on('changeDate', function(e) {
            // when the date is changed
            $(this).datepicker('hide');
        });
    </script>
@endsection
