@extends('admin.layouts.app')
@section('title') Business @endsection
@section('content')

    <!-- Begin Page Content -->
    <div class="container-fluid">
        <div class="wiz-card">
            <div class="wiz-card-header align-items-center">
                <h6 class="page-title">Business</h6>
                <div>
                    <a href="{{route('admin.business.create')}}" class="btn btn-sm btn-brand btn-brand-secondary"> <i class="fa fa-plus"></i>Create Business</a>
                </div>
            </div>
            <div class="wiz-card-body">
                <div class="table-responsive">
                    <table class="table table-bordered wiz-table mw-col-width-skip-first">
                        <thead>
                        <tr class="bg-secondary text-white">
                            <th>{{__('pages.sl')}}</th>
                            <th class="text-center">Name</th>
                            <th class="text-center">Contact Person</th>
                            <th class="text-center">Phone</th>
                            <th class="text-center">City</th>
                            <th class="text-center">Address</th>
                            <th class="text-center">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($businesses as $key => $business)
                            <tr>
                                <td>{{$loop->iteration}}</td>
                                <td class="text-center">{{$business->name}}</td>
                                <td class="text-center">{{$business->contact_person}}</td>
                                <td class="text-center">{{$business->phone}}</td>
                                <td class="text-center">{{$business->city}}</td>
                                <td class="text-center">{{$business->address}}</td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                                        <a href="{{route('admin.business.edit', [$business->id])}}" class="mx-2 text-brand-primary"><i class="bi bi-pencil"></i></a>
                                        <a href="javascript:void(0);" onclick="$(this).confirmDelete($('#delete-{{$key}}')) " class="mx-2 text-brand-danger"><i class="bi bi-trash"></i></a>
                                        <form action="{{ route('admin.business.destroy',$business->id) }}" method="post" id="delete-{{$key}}"> @csrf @method('delete') </form>
                                        <a href="{{route('admin.business.login', [$business->id])}}" target="_blank" class="mx-2 text-brand-primary"><i class="bi bi-power"></i></a>
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

