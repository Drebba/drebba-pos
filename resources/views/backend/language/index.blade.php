@extends('backend.layouts.app')
@section('title') {{__('pages.language')}} @endsection
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="wiz-card">
                    <!-- Card Header - Dropdown -->
                    <div class="wiz-card-header">
                        <h6 class="wiz-card-title">{{__('pages.language')}}</h6>
                        <div>
                            <a href="{{route('language.create')}}" class="btn btn-brand-secondary btn-sm btn-brand"><i class="fa fa-plus me-1"></i> {{__('pages.add_language')}}</a>
                        </div>
                    </div>
                    <!-- Card Body -->
                    <div class="wiz-card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered text-center table-sm wiz-table">
                                <thead>
                                <tr class="bg-secondary text-white">
                                    <th style="width: 60px">{{__('pages.sl')}}</th>
                                    <th>{{__('pages.flag')}}</th>
                                    <th>{{__('pages.country')}}</th>
                                    <th>{{__('pages.iso_code')}}</th>
                                    <th>{{__('pages.rtl_support')}}</th>
                                    <th>{{__('pages.content')}}</th>
                                    <th style="width: 60px">{{__('pages.action')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach($languages as $key => $language)
                                        <tr>
                                            <td>{{$key + 1}}</td>
                                            <td style="width: 70px"><img src="{{asset($language->flag)}}" class="img-fluid w-50"></td>
                                            <td>{{$language->language}}</td>
                                            <td >{{$language->iso_code}}</td>
                                            <td >{{ucfirst($language->is_rtl_support)}}</td>
                                            <td>
                                                <a href="{{route('translate', $language->id)}}" class="btn btn-brand-secondary btn-brand btn-sm py-1"><i class="fa fa-edit me-1"></i> {{__('pages.translate')}}</a>
                                            </td>
                                            <td class="font-14">
                                                @if($language->iso_code != 'en')
                                                    <div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                                                        <a href="{{route('language.edit', [$language->id])}}" class="mx-2 text-brand-primary"><i class="bi bi-pencil"></i> </a>
                                                        <a href="javascript:void(0);" onclick="$(this).confirmDelete($('#delete-{{$key}}')) " class="mx-2 text-brand-danger"><i class="bi bi-trash"></i></a>
                                                        <form action="{{ route('language.destroy',$language->id) }}" method="post" id="delete-{{$key}}"> @csrf @method('delete') </form>
                                                    </div>
                                                @else
                                                   --
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
@endsection

@section('js')

@endsection

