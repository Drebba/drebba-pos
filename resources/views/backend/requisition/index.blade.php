@extends('backend.layouts.app')
@section('title') {{__('pages.requisition')}} @endsection
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">
        <div class="wiz-box mb-4">
            <a href="javascript:void(0)" data-bs-toggle="collapse" data-bs-target="#mwFilterCollapse" class="d-flex d-md-none justify-content-between align-items-center mb-2">
                <span class="title-normal m-0">Filter</span>
                <span><i class="bi bi-funnel"></i></span>
            </a>

            <div class="collapse d-md-block" id="mwFilterCollapse">
                @include('backend.requisition.filter-form')
            </div>
        </div>

        <div class="wiz-card">
            <div class="wiz-card-body">
                <h5 class="wiz-card-title">Purchase</h5>
                @include('backend.requisition.data')
            </div>

            <div class="pt-3 pb-4">
                {{$requisitions->links()}}
            </div>
        </div>






    </div>
    <!-- /.container-fluid -->
@endsection

@section('css')
    <link rel="stylesheet" type="text/css" href="{{asset('admin/plugin/select2/select2.min.css')}}" />
    <link rel="stylesheet" type="text/css" href="{{asset('admin/plugin/select2/select2-bootstrap/select2-bootstrap-5-theme.css')}}" />


    {{--========== Datepicker ============--}}
    <link rel="stylesheet" type="text/css" href="{{asset('admin/plugin/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css')}}" />
@endsection
@section('js')
    <script src="{{asset('admin/plugin/select2/select2.min.js')}}"></script>
    <script>
        $('.select2-basic').select2({
            theme: "bootstrap-5",
            width: $( this ).data( 'width' ) ? $( this ).data( 'width' ) : $( this ).hasClass( 'w-100' ) ? '100%' : 'style',
            placeholder: $( this ).data( 'placeholder' ),
        });
    </script>

    {{--============== Datepicker ================--}}
    <script src="{{asset('admin/plugin/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js')}}"></script>
    <script>
        $('.datepicker').datepicker({
            format:'yyyy-mm-dd',
            zIndexOffset: 1198,
        }).on('changeDate', function(e) {
            // when the date is changed
            $(this).datepicker('hide');
        });
    </script>
@endsection

