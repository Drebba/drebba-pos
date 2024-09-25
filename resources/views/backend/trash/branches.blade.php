@extends('backend.layouts.app')
@section('title') {{__('pages.category')}} @endsection
@section('content')
    <!-- Begin Page Content -->

    <div class="container-fluid">
        <div class="wiz-card">
            <!-- Card Header - Dropdown -->
            <div class="wiz-card-header">
                <h6 class="wiz-card-title">{{__('pages.branch')}}</h6>
            </div>
            <!-- Card Body -->
            <div class="wiz-card-body">
                <div class="table-responsive-lg">
                    <table class="table table-bordered text-center wiz-table mw-col-width-skip-first">
                        <thead>
                        <tr class="bg-secondary text-white">
                            <th width="3%">{{__('pages.sl')}}</th>
                            <th>{{__('pages.title')}}</th>
                            <th>{{__('pages.contact_person')}}</th>
                            <th>{{__('pages.phone_number')}}</th>
                            <th width="8%">{{__('pages.action')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($branches as $key => $branch)
                            <tr>
                                <td>{{$key+1}}</td>
                                <td>{{$branch->title}}</td>
                                <td>{{$branch->contact_person}}</td>
                                <td>{{$branch->phone}}</td>
                                <td class="font-14">
                                    <div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                                        <a href="javascript:void(0);" class="text-brand-primary" onclick="$(this).confirmRestore($('#delete-{{$key}}')) "><i class="fas fa-trash-restore-alt mr-2"></i> Restore</a>
                                        <form action="{{ route('branch-restore-ok',['id' => $branch->id]) }}" method="post" id="delete-{{$key}}"> @csrf </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-muted">No Data Found</td>
                            </tr>
                        @endforelse
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

