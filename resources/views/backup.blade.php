@extends('backend.layouts.app')
@section('title') Backup and Restore @endsection
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <div class="wiz-card">
            <div class="wiz-card-body">
                <div class="d-flex justify-content-between mb-2">
                    <h5 class="wiz-card-title">Backup</h5>
                    <div>
                        <a href="{{ route('backup.store') }}" class="btn btn-brand-secondary btn-brand w-100 w-md-auto"><i class="fas fa-plus"></i> Create Backup</a>
                    </div>
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
                                <td>@formatdate($backup->created_at) </td>
                                <td>{{$backup->start_at}}</td>
                                <td> {{$backup->completed_at}} </td>
                                <td> {{
                                    $backup->status?'completed':'processing'
                                    }}</td>

                                <td class="font-14">
                                    <div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                                        @if ($backup->status)

                                        <a href="{{route('backup.download', ['id'=>$backup->uuid])}}" class="mx-2"><i class="bi bi-download"></i></a>
                                        @endif

                                    </div>
                                </td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                </div>

                <div class="py-3">
                    {{$backups->links()}}
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

