@extends('backend.layouts.app')
@section('title') Set Permission @endsection
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid settings-page">
        <div class="wiz-card">
            <div class="wiz-card-header">
                <h5 class="wiz-card-title">Permission</h5>
            </div>
            <div class="wiz-card-body">
                <form action="{{route('savePermission')}}" method="post" role="form">
                    @csrf
                    <div class="table-responsive scroll-bar">
                        <table class="table table-bordered table-hover table-sm wiz-table">
                                <thead>
                                <tr class="sticky-top-80">
                                    <th class="sticky-top-80">Permission</th>
                                    @foreach($roles as $role)
                                        <th class="text-center extra-small text-nowrap sticky-top-80">{!! ucwords(str_replace("_", " ", $role->name)) !!}</th>
                                    @endforeach
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($permissions as $permission)
                                    <tr>
                                        <td>{!! ucwords(str_replace("_", " ", $permission->name)) !!}</td>
                                        @foreach($roles as $role)
                                            <td class="text-center">
                                                <input type="checkbox"
                                                       name="permission[{!!$role->id!!}][{!!$permission->id!!}]"
                                                       value='1' {!! (in_array($role->id.'-'.$permission->id, $permissionRole)) ? 'checked' : '' !!} >
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                    </div>

                    <div class="pt-3 pb-2 text-end">
                        <button type="submit" class="btn btn-brand-primary btn-brand">Save Permission</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
@endsection

@section('js')

@endsection

