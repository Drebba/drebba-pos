@extends('backend.layouts.app')
@section('title') {{__('pages.notice')}}  @endsection

@section('css')

@endsection

@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="wiz-card">
                    <!-- Card Header - Dropdown -->
                    <div class="wiz-card-header">
                        <h6 class="wiz-card-title">{{__('pages.notice')}}</h6>
                        <div>
                            <a href="{{route('notice.index')}}" class="btn btn-brand-secondary btn-sm btn-brand"><i class="fa fa-list me-1"></i> {{__('pages.all')}} {{__('pages.notice')}}</a>
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="wiz-card-body p-lg-4">
                        <div class="mb-1">
                            <span class="me-2 fw-medium">Title:</span> {{$notice->title}}
                        </div>

                        <div class="mb-1">
                            <span class="me-2 fw-medium">From:</span> {{$notice->createdBy->name}}
                        </div>

                        <div class="mb-1">
                            <span class="me-2 fw-medium">Date:</span> {{$notice->notify_date->format(get_option('app_date_format'))}} , {{\Carbon\Carbon::parse($notice->notify_time)->format('h:m a')}}
                        </div>

                        <div>
                            <span class="me-2 fw-medium">Description:</span> {{$notice->description}}
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

