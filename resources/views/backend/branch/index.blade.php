@extends('backend.layouts.app')
@section('title') {{__('pages.branch')}} @endsection
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">
        <div class="wiz-card">
            <div class="wiz-card-header align-items-center">
                <h6 class="page-title">{{__('pages.all_branch')}}</h6>
                <div>
                    <a href="{{route('branch.create')}}" class="btn btn-sm btn-brand btn-brand-secondary"> <i class="fa fa-plus"></i> {{__('pages.create_branch')}}</a>
                </div>
            </div>
            <div class="wiz-card-body">
                <div class="table-responsive">
                    <table class="table table-bordered wiz-table mw-col-width-skip-first">
                        <thead>
                        <tr class="bg-secondary text-white">
                            <th>{{__('pages.sl')}}</th>
                            <th class="text-center">{{__('pages.branch_name')}}</th>
                            <th class="text-center">{{__('pages.contact_person')}}</th>
                            <th class="text-center">{{__('pages.phone_number')}}</th>
                            <th class="text-center">{{__('pages.note')}}</th>
                            <th class="text-center">{{__('pages.address')}}</th>
                            <th class="text-center">{{__('pages.action')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($branches as $key => $branch)
                            <tr>
                                <td>{{$key+1}}</td>
                                <td class="text-center">{{$branch->title}}</td>
                                <td class="text-center">{{$branch->contact_person}}</td>
                                <td class="text-center">{{$branch->phone}}</td>
                                <td class="text-center">{{$branch->short_description}}</td>
                                <td class="text-center">{{$branch->address}}</td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                                        <a href="{{route('branch.edit', [$branch->id])}}" class="mx-2 text-brand-primary"><i class="bi bi-pencil"></i></a>
                                        <a href="javascript:void(0);" onclick="$(this).confirmDelete($('#delete-{{$key}}')) " class="mx-2 text-brand-danger"><i class="bi bi-trash"></i></a>
                                        <form action="{{ route('branch.destroy',$branch->id) }}" method="post" id="delete-{{$key}}"> @csrf @method('delete') </form>
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

