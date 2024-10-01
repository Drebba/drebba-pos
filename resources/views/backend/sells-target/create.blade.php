@extends('backend.layouts.app')
@section('title') {{__('pages.sell_target')}}  @endsection
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <div class="wiz-card">
            <!-- Card Header - Dropdown -->
            <div class="wiz-card-header">
                <h6 class="page-title">{{__('pages.create_sell_target')}}</h6>
                <div>
                    <a href="{{route('branch-sells-target.index')}}" class="btn btn-brand-secondary btn-sm"><i class="fa fa-list me-1"></i> {{__('pages.sell_targets')}}</a>
                </div>
            </div>
            <!-- Card Body -->
            <div class="wiz-card-body">
                <form action="{{route('branch-sells-target.store')}}" method="post" data-parsley-validate>
                    @csrf

                    <div class="d-flex gap-3 align-items-center mb-3">
                        <label for="company_name" class="custom-label mb-0">{{__('pages.select_month')}}<span class="text-danger">*</span></label>
                        <div>
                            <input type="text" name="month" data-date-format="yyyy-M"   placeholder="{{__('pages.select_month')}}" id="monthpicker" class="form-control month-picker" autocomplete="off" required>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end py-3">
                        <button type="submit" class="btn btn-brand btn-brand-primary">{{__('pages.save')}}</button>
                    </div>
                </form>
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
