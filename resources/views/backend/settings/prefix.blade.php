@extends('backend.layouts.app')
@section('title') Settings @endsection
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid settings-page">
        <div>
            @include('backend.settings.partial.nav')
        </div>

        <div class="wiz-card">
            <!-- Card Header - Dropdown -->
            <div class="wiz-card-header">
                <h5 class="wiz-card-title">Prefix</h5>
            </div>
            <!-- Card Body -->
            <div class="wiz-card-body p-lg-4">
                <form action="{{route('save-application-setting')}}" method="post" class="form-horizontal" enctype="multipart/form-data"  data-parsley-validate>
                    @csrf

                    <div class="row g-3">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="product_sku_prefix" class="custom-label">{{__('pages.product')}}</label>
                                <input name="product_sku_prefix" value="{{get_option('product_sku_prefix')}}" type="text" class="form-control" placeholder="Product SKU Prefix" required>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="product_sku_prefix" class="custom-label">{{__('pages.purchase_invoice')}}</label>
                                <input name="purchase_invoice_prefix" value="{{get_option('purchase_invoice_prefix')}}" type="text" class="form-control" placeholder="Purchase Invoice Prefix" required>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="product_sku_prefix" class="custom-label">{{__('pages.sell_invoice')}}</label>
                                <input name="sell_invoice_prefix" value="{{get_option('sell_invoice_prefix')}}" type="text" class="form-control" placeholder="Sell Invoice Prefix" required>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="requisition_id_prefix" class="custom-label">{{__('pages.requisition_id')}}</label>
                                <input name="requisition_id_prefix" value="{{get_option('requisition_id_prefix')}}" type="text" class="form-control" placeholder="Requisition ID Prefix" required>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="expense_id_prefix" class="custom-label">{{__('pages.expense_id')}}</label>
                                <input name="expense_id_prefix" value="{{get_option('expense_id_prefix')}}" type="text" class="form-control" placeholder="Expense ID Prefix" required>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="invoice_length" class="custom-label">Invoice Min Length</label>
                                <input name="invoice_length" value="{{get_option('invoice_length')}}" type="number" step="1" min="1" max="9" class="form-control" placeholder="Ex. 6" required>
                            </div>
                        </div>


                        <div class="col-md-12">
                            <div class="form-group text-center py-3">
                                <button type="submit" class="btn btn-brand-primary btn-brand">Save & Update</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
@endsection

@section('js')

@endsection

