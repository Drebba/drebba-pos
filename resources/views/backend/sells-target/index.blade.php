@extends('backend.layouts.app')
@section('title') {{__('pages.sell_target')}} @endsection

@section('css')

@endsection
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

            <div class="d-flex align-items-center justify-content-between mb-3">
                <h5 class="page-title mb-0"> {{__('pages.sell_targets')}} </h5>
                <div>
                    <a href="{{route('branch-sells-target.create')}}" class="btn btn-sm btn-brand btn-brand-secondary"> <i class="fa fa-plus me-1"></i> {{__('pages.create_sell_target')}}</a>
                </div>
            </div>

            @foreach($sells_targets as $key => $sells_target)
            <div class="wiz-card h-auto mb-3">
                <div class="wiz-card-header">
                    <h5 class="wiz-card-title">{{\Carbon\Carbon::parse($key)->format('Y-F')}}</h5>
                    <div>
                        <a href="{{route('branch-sells-target.edit', [$key])}}" class="btn btn-sm btn-soft-primary"><i class="bi bi-pencil"></i> </a>
                        <a href="javascript:void(0);" onclick="$(this).confirmDelete($('#delete-{{$key}}')) " class="btn btn-sm btn-soft-danger"><i class="bi bi-trash"></i></a>
                        <form action="{{ route('branch-sells-target.destroy',$key) }}" method="post" id="delete-{{$key}}"> @csrf @method('delete') </form>
                    </div>
                </div>
                <div class="wiz-card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered wiz-table">
                            <thead>

                            <tr class="bg-secondary text-white">
                                <th class="min-w-150px col-md-3">{{__('pages.branch_name')}}</th>
                                <th class="min-w-150px col-md-3 text-center">{{__('pages.target_amount')}}</th>
                                <th class="min-w-150px col-md-3 text-center">{{__('pages.sell')}}</th>
                                <th class="min-w-150px col-md-3 text-center">{{__('pages.progress')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($sells_target as $branch_target)
                                <tr>
                                    <td>{{$branch_target->branch->title}}</td>
                                    <td class="text-center">{{get_option('app_currency')}}{{number_format($branch_target->target_amount, 2)}}</td>
                                    <td class="text-center">{{get_option('app_currency')}}{{number_format(monthlySells($branch_target->business_id, $key), 2)}}</td>
                                    <td>
                                        @php
                                            if(monthlySells($branch_target->business_id, $key) > 0){
                                                $result = monthlySells($branch_target->business_id, $key) * 100 / $branch_target->target_amount;
                                            }else{
                                                $result = 0;
                                            }
                                        @endphp

                                        <div class="progress">
                                            @if($result > 20)
                                                <div class="progress-bar progress-bar-striped text-center dwp-{{round($result)}}" role="progressbar" aria-valuenow="{{$result}}" aria-valuemin="0" aria-valuemax="100">{{number_format($result,2)}}%</div>
                                            @else
                                                <div class="progress-bar w20p progress-bar-striped text-center bg-brand-danger" role="progressbar" aria-valuenow="{{$result}}" aria-valuemin="0" aria-valuemax="100">{{number_format($result,2)}}%</div>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endforeach


    </div>
    <!-- /.container-fluid -->
@endsection

@section('js')

@endsection

