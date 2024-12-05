@extends('backend.layouts.app')
@section('title') Backup and Restore @endsection
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <div class="wiz-card">
            <div class="wiz-card-body">
                <div class="d-flex justify-content-between">
                    <h5 class="wiz-card-title">Backup</h5>
                </div>
                <div class="table-responsive-xl">
                    <table class="table table-bordered text-center wiz-table mw-col-width-skip-first">
                        <thead class="sticky-top">
                        <tr class="bg-secondary text-white">
                            <th>{{__('pages.sl')}}</th>
                            <th>Date</th>
                            <th>Start AT</th>
                            <th>Completed AT</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>

                        @foreach($backups as $key => $backup)

                            <tr>
                                <td>{{$key+1}}</td>
                                <td>@formatdate($backup->created_at)</td>
                                <td>{{$sell->user?->name}}</td>
                                <td> @formatdate($sell->sell_date) </td>
                                <td> {{get_option('app_currency')}}{{number_format($sell->grand_total_price, 2)}} </td>
                                <td> {{get_option('app_currency')}}{{number_format($sell->paid_amount, 2)}} </td>
                                <td> {{get_option('app_currency')}}{{number_format($sell->due_amount, 2)}} </td>
                                <td class="font-14">
                                    <div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                                        {{-- <a href="{{route('sell.edit', [$sell->id])}}" class="mx-2 text-brand-primary"><i class="bi bi-pencil"></i></a> --}}
                                        <a href="{{route('sell.show', [$sell->id])}}" class="mx-2"><i class="bi bi-eye"></i></a>
                                        <a href="javascript:void(0);" onclick="$(this).confirmDelete($('#delete-{{$key}}')) " class="mx-2 text-danger"><i class="bi bi-trash3"></i></a>
                                        <form action="{{ route('sell.destroy',$sell->id) }}" method="post" id="delete-{{$key}}"> @csrf @method('delete') </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        <tr>
                            <th colspan="4">Total</th>
                            <th>{{$grandtotal}}</th>
                            <th>{{$paid}}</th>
                            <th>{{$due}}</th>

                        </tr>
                        </tbody>
                    </table>
                </div>

                <div class="py-3">
                    {{$sells->appends(Request::all())->links()}}
                </div>
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

