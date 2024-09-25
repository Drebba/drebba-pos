@extends('backend.layouts.app')
@section('title') {{__('pages.department')}} @endsection
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">
        <div class="wiz-card">
            <!-- Card Header - Dropdown -->
            <div class="wiz-card-header">
                <h6 class="wiz-card-title">{{__('pages.department')}}</h6>
            </div>
            <!-- Card Body -->
            <div class="wiz-card-body">
                <div class="table-responsive-lg">
                    <table class="table table-bordered text-center wiz-table mw-col-width-skip-first">
                        <thead>
                        <tr class="bg-secondary text-white">
                            <th width="3%">{{__('pages.sl')}}</th>
                            <th>{{__('pages.department_name')}}</th>
                            <th width="8%">{{__('pages.action')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($departments as $key => $department)
                            <tr>
                                <td>{{$key+1}}</td>
                                <td>{{$department->title}}</td>
                                <td class="font-14">
                                    <div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                                        <a href="javascript:void(0);" onclick="$(this).confirmRestore($('#delete-{{$key}}')) " class="text-brand-primary""><i class="fas fa-trash-restore-alt mr-2"></i> Restore</a>
                                        <form action="{{ route('department-restore-ok',['id' => $department->id]) }}" method="post" id="delete-{{$key}}"> @csrf </form>
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

