@extends('backend.layouts.app')
@section('title') {{__('pages.tax')}} @endsection
@section('content')

    <!-- Begin Page Content -->
    <div class="container-fluid">
        <div class="wiz-card">
            <!-- Card Header - Dropdown -->
            <div class="wiz-card-header">
                <h6 class="wiz-card-title">{{__('pages.tax')}}</h6>
            </div>
            <!-- Card Body -->
            <div class="wiz-card-body">
                <div class="table-responsive-lg">
                    <table class="table table-bordered text-center wiz-table mw-col-width-skip-first">
                        <thead>
                        <tr class="bg-secondary text-white">
                            <th>{{__('pages.sl')}}</th>
                            <th>{{__('pages.title')}}</th>
                            <th>{{__('pages.value')}}</th>
                            <th width="8%">{{__('pages.value')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($taxes as $key => $tax)
                            <tr>
                                <td>{{$key+1}}</td>
                                <td>{{$tax->title}}</td>
                                <td>{{$tax->value}} %</td>
                                <td class="font-14">
                                    <div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                                        <a href="javascript:void(0);" onclick="$(this).confirmRestore($('#delete-{{$key}}')) " class="text-brand-primary"><i class="fas fa-trash-restore-alt mr-2"></i> Restore</a>
                                        <form action="{{ route('tax-restore-ok',['id' => $tax->id]) }}" method="post" id="delete-{{$key}}"> @csrf </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-muted">No Data Found</td>
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

