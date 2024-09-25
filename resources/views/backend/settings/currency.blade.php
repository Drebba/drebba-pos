@extends('backend.layouts.app')
@section('title') Currency Settings @endsection
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid settings-page">

        <div>
            @include('backend.settings.partial.nav')
        </div>

        <div class="wiz-card">
            <!-- Card Header - Dropdown -->
            <div class="wiz-card-header">
                <h5 class="wiz-card-title">Currency</h5>
            </div>
            <!-- Card Body -->
            <div class="wiz-card-body p-lg-4 pb-xl-5">
                <form action="{{route('save-application-setting')}}" method="post" enctype="multipart/form-data" data-parsley-validate>
                    @csrf

                    <div class="text-end mb-4">
                        <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#newCurrency"  class="btn btn-brand-secondary btn-brand"><i class="fa fa-plus me-1"></i> Add Currency</a>
                    </div>

                    <div class="row justify-content-center">
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <select name="app_currency" class="form-select select2-basic">
                                    @foreach($currencies as $currency)
                                        <option value="{{$currency->currency}}" {{get_option('app_currency') == $currency->currency ? 'selected' : ''}}>
                                            {{$currency->currency}} ( {{$currency->title}} )
                                        </option>
                                    @endforeach
                                </select>

                                @if ($errors->has('currency'))
                                    <div class="error">{{ $errors->first('currency') }}</div>
                                @endif
                            </div>

                            <div class="form-group text-center">
                                <button type="submit" class="btn btn-brand-primary btn-brand">Save & Update</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="newCurrency" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <form action="{{route('app-currency.store')}}" method="post">
                    @csrf

                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Add New Currency</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">


                            <div class="row  pt-3">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="product_sku_prefix">{{__('pages.title')}} <span class="text-danger">*</span></label>
                                        <input name="title" type="text" class="form-control form-control-sm" placeholder="Currency Name (EX. USD)" required>
                                    </div>
                                </div>

                                <div class="col-md-12 mt-3">
                                    <div class="form-group">
                                        <label for="product_sku_prefix">{{__('pages.currency_symbol')}} <span class="text-danger">*</span></label>
                                        <input name="currency" type="text"  class="form-control form-control-sm" placeholder="Currency Symbol (Ex. $)" required>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save Currency</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
@endsection

@section('css')
    <link rel="stylesheet" type="text/css" href="{{asset('admin/plugin/select2/select2.min.css')}}" />
    <link rel="stylesheet" type="text/css" href="{{asset('admin/plugin/select2/select2-bootstrap/select2-bootstrap-5-theme.css')}}" />
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
@endsection

