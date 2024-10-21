@extends('backend.layouts.app')
@section('title') {{__('pages.employee')}} @endsection
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">
        <div class="wiz-card">
            <div class="wiz-card-header align-items-center">
                <h6 class="page-title"> {{__('pages.employees')}} </h6>
                <div>
                    <a href="{{route('employee.create')}}" class="btn btn-sm btn-brand btn-brand-secondary"> <i class="fa fa-plus"></i> {{__('pages.create_employees')}}</a>
                </div>
            </div>
            <div class="wiz-card-body">
                <div class="table-responsive">
                    <table class="table table-sm table-bordered wiz-table mw-col-width-skip-first">
                        <thead>
                        <tr class="bg-secondary text-white">
                            <th>{{__('pages.sl')}}</th>
                            <th class="text-center">{{__('pages.name')}}</th>
                            {{-- <th class="text-center">{{__('pages.branch')}}</th> --}}
                            {{-- <th class="text-center">{{__('pages.designation')}}</th> --}}
                            <th class="text-center">{{__('pages.phone_number')}}</th>
                            <th class="text-center">{{__('pages.email')}}</th>
                            <th class="text-center">{{__('pages.status')}}</th>
                            <th class="text-center">{{__('pages.action')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($employees as $key => $employee)
                            <tr>
                                <td class="text-center">{{$key + 1}}</td>
                                <td class="text-center">{{$employee->user->name}}</td>
                                {{-- <td class="text-center">{{$employee->branch->title}}</td> --}}
                                {{-- <td class="text-center">{{$employee->designation->title}}</td> --}}
                                <td class="text-center">{{$employee->phone_number}}</td>
                                <td class="text-center">{{$employee->user->email}}</td>
                                <td class="text-center">
                                    @if($employee->user->active_status == 1)
                                        <a  href="javascript:void(0)" onclick="$(this).confirm('{{url('change-user-status/'.$employee->user->id)}}');" class="custom-badge badge-soft-success">{{__('pages.active')}}</a>
                                    @else
                                        <a  href="javascript:void(0)" onclick="$(this).confirm('{{url('change-user-status/'.$employee->user->id)}}');" class="custom-badge badge-soft-danger">{{__('pages.not_active')}}</a>
                                    @endif
                                </td>
                                <td class="font-14">
                                    <div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                                        <a href="{{route('employee.edit', [$employee->id])}}" class="mx-2 text-brand-primary"><i class="bi bi-pencil"></i></a>
                                        <a href="{{route('employee.show', [$employee->id])}}" class="mx-2"><i class="bi bi-eye"></i></a>
                                        <a href="javascript:void(0);" onclick="$(this).confirmDelete($('#delete-{{$key}}')) " class="mx-2 text-brand-danger"><i class="bi bi-trash"></i></a>
                                        <form action="{{ route('employee.destroy',$employee->id) }}" method="post" id="delete-{{$key}}"> @csrf @method('delete') </form>
                                    </div>
                                </td>

                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
@endsection

@section('js')

@endsection

