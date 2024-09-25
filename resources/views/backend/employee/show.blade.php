@extends('backend.layouts.app')
@section('title')
    {{__('pages.employee')}}
@endsection
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">


        <div class="row g-3">
            <div class="col-md-3">
                <div class="wiz-card h-auto">
                    <div class="wiz-card-header">
                        <h5 class="wiz-card-title">{{__('pages.employee')}}</h5>
                    </div>
                    <div class="wiz-card-body">
                        <div class="col-7 col-md-9 mx-auto p-0">
                            <div class="ratio ratio-1x1 mb-3">
                                <div class="avatar-box">
                                    <img src="{{asset($employee->profile_picture ? $employee->profile_picture : 'backend/img/user-placeholder.png')}}" class="img-fit-cover">
                                </div>
                            </div>

                            <div class="text-center">
                                @if($employee->user->active_status == 1)
                                    <a  href="javascript:void(0)" onclick="$(this).confirm('{{url('change-user-status/'.$employee->user->id)}}');" class="btn btn-brand btn-soft-success">{{__('pages.active')}}</a>
                                @else
                                    <a  href="javascript:void(0)" onclick="$(this).confirm('{{url('change-user-status/'.$employee->user->id)}}');" class="btn btn-brand btn-soft-danger">{{__('pages.not_active')}}</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="wiz-card h-auto mb-3">
                    <div class="wiz-card-header">
                        <h5 class="wiz-card-title">{{__('pages.personal_information')}}</h5>
                    </div>
                    <div class="wiz-card-body p-0 overflow-hidden">
                        <table class="table block-table table-striped table-sm mb-0">
                            <tbody>
                                <tr>
                                    <td class="fw-medium w-50">{{__('pages.name')}}</td>
                                    <td>{{$employee->user->name}}</td>
                                </tr>
                                <tr>
                                    <td class="fw-medium">{{__('pages.email')}}</td>
                                    <td>{{$employee->user->email}}</td>
                                </tr>

                                <tr>
                                    <td class="fw-medium">{{__('pages.gender')}}</td>
                                    <td>{{$employee->gender == 1 ? 'Male' : 'Female'}}</td>
                                </tr>

                                <tr>
                                    <td class="fw-medium">{{__('pages.date_of_birth')}}</td>
                                    <td>{{$employee->date_of_birth ? $employee->date_of_birth->format(get_option('app_date_format')) : '--'}}</td>
                                </tr>

                                <tr>
                                    <td class="fw-medium">{{__('pages.blood_group')}}</td>
                                    <td>{{$employee->blood_group}}</td>
                                </tr>

                                <tr>
                                    <td class="fw-medium">{{__('pages.phone_number')}}</td>
                                    <td>{{$employee->phone_number}}</td>
                                </tr>

                                <tr>
                                    <td class="fw-medium">{{__('pages.address')}}</td>
                                    <td>{{$employee->address}}</td>
                                </tr>

                                <tr>
                                    <td class="fw-medium">{{__('pages.educational_background')}}</td>
                                    <td>{{$employee->educational_background}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="wiz-card h-auto">
                    <div class="wiz-card-header">
                        <h5 class="wiz-card-title">{{__('pages.employment_info')}}</h5>
                    </div>
                    <div class="wiz-card-body p-0 overflow-hidden">
                        <table class="table block-table table-striped table-sm mb-0">
                            <tbody>
                            <tr>
                                <td class="fw-medium w-50">{{__('pages.branch')}}</td>
                                <td>{{$employee->branch ? $employee->branch->title : '--'}}</td>
                            </tr>

                            <tr>
                                <td class="fw-medium">{{__('pages.role')}}</td>
                                <td>{!! ucwords(str_replace("_", " ", $role)) !!}</td>
                            </tr>

                            <tr>
                                <td class="fw-medium">{{__('pages.department')}}</td>
                                <td>{{$employee->department ? $employee->department->title : '--'}}</td>
                            </tr>
                            <tr>
                                <td class="fw-medium">{{__('pages.designation')}}</td>
                                <td>{{$employee->designation ? $employee->designation->title : '--'}}</td>
                            </tr>

                            <tr>
                                <td class="fw-medium">{{__('pages.employee_id')}}</td>
                                <td>{{$employee->id_number}}</td>
                            </tr>

                            <tr>
                                <td class="fw-medium">{{__('pages.joining_date')}}</td>
                                <td>{{$employee->joining_date ? $employee->joining_date->format(get_option('app_date_format')) : '--'}}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>


    </div>
    <!-- /.container-fluid -->
@endsection


