@extends('backend.layouts.app')
@section('title') {{__('pages.supplier')}} @endsection
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <div class="wiz-card">
            <!-- Card Header - Dropdown -->
            <div class="wiz-card-header">
                <h6 class="page-title">{{__('pages.suppliers')}}</h6>
                <div>
                    <a href="{{route('supplier.create')}}" class="btn btn-brand btn-brand-secondary btn-sm"><i class="fa-solid fa-plus me-1"></i> {{__('pages.create_supplier')}}</a>
                </div>
            </div>
            <!-- Card Body -->
            <div class="wiz-card-body">
                <div class="table-responsive">
                    <table class="table table-bordered text-center wiz-table mw-col-width-skip-first">
                        <thead>
                        <tr class="bg-secondary text-white">
                            <th>{{__('pages.sl')}}</th>
                            <th>{{__('pages.company_name')}}</th>
                            <th>{{__('pages.contact_person')}}</th>
                            <th>{{__('pages.phone_number')}}</th>
                            <th>{{__('pages.email')}}</th>

                            <th>{{__('pages.purchase')}}</th>
                            <th>{{__('pages.paid')}}</th>
                            <th>{{__('pages.due')}}</th>

                            <th width="8%">{{__('pages.action')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($suppliers as $key => $supplier)
                            <tr>
                                <td>{{$key+1}}</td>
                                <td>{{$supplier->company_name}}</td>
                                <td>{{$supplier->contact_person}}</td>
                                <td>{{$supplier->phone}}</td>
                                <td>{{$supplier->email}}</td>
                                <td>{{number_format($supplier->purchase->sum('total_amount'),2)}}</td>
                                <td>{{number_format($supplier->payments->sum('amount'),2)}}</td>
                                <td>
                                    {{number_format($supplier->purchase->sum('total_amount') - $supplier->payments->sum('amount'),2)}}
                                </td>
                                <td class="font-14">
                                    <div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                                        <a href="{{route('supplier.edit', [$supplier->id])}}" class="mx-2 text-brand-primary"><i class="bi bi-pencil"></i> </a>
                                        <a href="{{route('supplier.show', [$supplier->id])}}" class="mx-2"><i class="bi bi-eye"></i> </a>
                                        <a href="javascript:void(0);" onclick="$(this).confirmDelete($('#delete-{{$key}}')) " class="mx-1 text-brand-danger"><i class="bi bi-trash"></i></a>
                                        <form action="{{ route('supplier.destroy',$supplier->id) }}" method="post" id="delete-{{$key}}"> @csrf @method('delete') </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
    <!-- /.container-fluid -->
@endsection

@section('js')

@endsection

