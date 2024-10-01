@extends('backend.layouts.app')
@section('title') {{__('pages.sell_target')}}  @endsection
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="wiz-card">
                    <!-- Card Header - Dropdown -->
                    <div class="wiz-card-header">
                        <h6 class="page-title">{{__('pages.update_sell_target')}}</h6>
                        <div>
                            <a href="{{route('branch-sells-target.index')}}" class="btn btn-brand btn-brand-secondary btn-sm"><i class="fa fa-list me-1"></i> {{__('pages.sell_targets')}}</a>
                        </div>
                    </div>
                    <!-- Card Body -->
                    <div class="wiz-card-body">
                        <form action="{{route('branch-sells-target.update', [\Carbon\Carbon::parse($sell_targets[0]->month)->format('Y-m')])}}" method="post" data-parsley-validate>
                            @csrf
                            @method('patch')

                            <div class="d-flex gap-3 align-items-center mb-3">
                                <label for="company_name" class="custom-label mb-0">{{__('pages.select_month')}}<span class="text-danger">*</span></label>
                                <div>
                                    <input type="text" name="month" data-date-format="yyyy-M" value="{{\Carbon\Carbon::parse($sell_targets[0]->month)->format('Y-F')}}"  placeholder="{{__('pages.select_month')}}" id="monthpicker" class="form-control month-picker" autocomplete="off" required>
                                </div>
                            </div>


                            @foreach($sell_targets as $sell_target)
                                <div class="mb-3">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <input type="hidden" name="business_id[]" value="{{$sell_target->business_id}}" class="form-control" required>
                                                <input type="text" value="{{$sell_target->branch->title}}" class="form-control" required readonly>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <input type="number" name="target_amount[]" placeholder="Target Amount" step="1" min="1" value="{{$sell_target->target_amount}}" class="form-control" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach


                            <div class="d-flex justify-content-end py-3">
                                <button type="submit" class="btn btn-brand btn-brand-primary">{{__('pages.update')}}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
@endsection

@section('css')
    <link rel="stylesheet" type="text/css" href="{{asset('admin/plugin/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css')}}" />
@endsection
@section('js')
    <script src="{{asset('/backend/js/custom.js')}}"></script>

    {{--============== Datepicker ================--}}
    <script src="{{asset('admin/plugin/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js')}}"></script>
    <script>
        $('.month-picker').datepicker({
            format: $(this).attr('data-date-format'),
            startView: "months",
            minViewMode: "months",
            zIndexOffset: 1198,
        }).on('changeMonth', function(e) {
            // when the date is changed
            $(this).datepicker('hide');
        });
    </script>
@endsection
