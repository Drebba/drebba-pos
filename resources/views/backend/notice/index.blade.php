@extends('backend.layouts.app')
@section('title') {{__('pages.notice')}} @endsection
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">
        <div class="wiz-card">
            <!-- Card Header - Dropdown -->
            <div class="wiz-card-header">
                <h6 class="wiz-card-title">{{__('pages.manage')}} {{__('pages.notice')}}</h6>
                <div>
                    <a href="{{route('notice.create')}}" class="btn btn-brand-secondary btn-sm btn-brand"> <i class="fa fa-plus me-1"></i> {{__('pages.create')}} {{__('pages.notice')}}</a>
                </div>
            </div>
            <!-- Card Body -->
            <div class="wiz-card-body">
                <div class="table-responsive">
                    <table class="table table-bordered text-center wiz-table">
                        <thead>
                        <tr>
                            <th width="3%">{{__('pages.sl')}}</th>
                            <th>{{__('pages.title')}}</th>
                            <th width="15%">{{__('pages.date_time')}}</th>
                            <th width="10%">{{__('pages.notify_from')}}</th>
                            <th width="20%">{{__('pages.notify_to')}}</th>
                            <th width="30%">{{__('pages.description')}}</th>
                            <th width="8%">{{__('pages.action')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($notices as $key => $notice)
                            <tr>
                                <td>{{$key+1}}</td>
                                <td>{{$notice->title}}</td>
                                <td>{{$notice->notify_date->format(get_option('app_date_format'))}} <br> {{\Carbon\Carbon::parse($notice->notify_time)->format('h:00 a')}}</td>
                                <td>{{$notice->createdBy->name}}</td>
                                <td>
                                    <div class="d-flex flex-wrap gap-1 justify-content-center">
                                        @foreach($notice->notifications as $notification)
                                            <div class="w-25px">
                                                <div class="ratio ratio-1x1">
                                                    <div class="avatar-box border-3">
                                                        <img class="img-fit-cover"  title="{{$notification->messageTo->name}}" src="{{asset($notification->messageTo->employee && $notification->messageTo->employee->profile_picture != '' ? $notification->messageTo->employee->profile_picture : 'backend/img/user-placeholder.png')}}">
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </td>
                                <td>{{\Illuminate\Support\Str::limit($notice->description, 180)}}</td>
                                <td class="font-14">
                                    <div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                                        <a href="{{route('notice.edit', [$notice->id])}}" class="mx-2 text-brand-primary"><i class="bi bi-pencil"></i> </a>
                                        <a href="{{route('notice.show', [$notice->id])}}" class="mx-2"><i class="bi bi-eye"></i> </a>
                                        <a href="javascript:void(0);" onclick="$(this).confirmDelete($('#delete-{{$key}}')) " class="mx-2 text-brand-danger"><i class="bi bi-trash"></i></a>
                                        <form action="{{ route('notice.destroy',$notice->id) }}" method="post" id="delete-{{$key}}"> @csrf @method('delete') </form>
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
