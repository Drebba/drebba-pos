@extends('backend.layouts.app')
@section('title') Currency Settings @endsection
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid settings-page">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4 rounded-0">
                    <!-- Card Header - Dropdown -->
                    <div class="card-body p-5">
                        <form method="post" action="{{route('check.purchase.key')}}">
                            @csrf
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="purchase_code">Input your product purchase code </label>
                                    <input type="text" class="form-control" name="purchase_code" id="purchase_code"
                                           placeholder="Purchase code">
                                </div>
                                <div class="form-group">
                                    <label for="user_name">Input your codecanyon username</label>
                                    <input type="text" class="form-control" name="user_name" id="user_name"
                                           placeholder="User Name">
                                </div>
                                <p id="validating" style="display: none">Validating License....</p>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-danger float-right" id="purchase_button">Verify</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
@endsection

@section('js')

@endsection

