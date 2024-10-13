@extends('backend.layouts.app')
@section('title') {{__('pages.requisition')}}  @endsection
@section('content')

    @if($requisition->status == 0 && $requisition->requisition_from != Auth::user()->business_id)
        <div id="app">
            <show-requisition :requisition="{{ $requisition }}"></show-requisition>
        </div>
    @else
        <div class="container-fluid">

            <div class="wiz-box d-flex justify-content-between align-items-center mb-3">
                <h6 class="wiz-card-title">{{__('pages.requisition_details')}}</h6>
                <div class="btn-group btn-group-sm custom-btn-group" role="group">
                    @if($requisition->requisition_from == Auth::user()->business_id && $requisition->status == 0)
                        <a href="{{route('requisition.edit', [$requisition->id])}}" class="btn btn-brand-primary rounded-0"><i class="bi bi-pencil me-1"></i> Edit </a>
                    @endif
                    <a href="{{url('/export/requisition/print-invoice/id='.$requisition->id.'/type={print}')}}" target="_blank" class="btn btn-brand-warning"><i class="fa fa-print me-1"></i> Print </a>
                </div>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <div class="wiz-box h-100">
                        <table class="table table-bordered wiz-table mb-0 text-center">
                            <thead>
                            <tr>
                                <th colspan="2">{{__('pages.requisition_form')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td class="fw-medium">{{__('pages.branch')}}</td>
                                <td>{{$requisition->requisitionFrom->title}}</td>
                            </tr>

                            <tr>
                                <td class="fw-medium">{{__('pages.phone_number')}}</td>
                                <td>{{$requisition->requisitionFrom->phone}}</td>
                            </tr>

                            <tr>
                                <td class="fw-medium">{{__('pages.address')}}</td>
                                <td>{{$requisition->requisitionFrom->address}}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="wiz-box h-100">
                        <table class="table table-bordered wiz-table mb-0 text-center">
                            <thead>
                            <tr class="bg-secondary text-white text-center">
                                <th colspan="2">{{__('pages.requisition_to')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td class="fw-medium">{{__('pages.branch')}}</td>
                                <td>{{$requisition->requisitionTo->title}}</td>
                            </tr>

                            <tr>
                                <td class="fw-medium">{{__('pages.phone_number')}}</td>
                                <td>{{$requisition->requisitionTo->phone}}</td>
                            </tr>

                            <tr>
                                <td class="fw-medium">{{__('pages.address')}}</td>
                                <td>{{$requisition->requisitionTo->address}}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="wiz-box h-100">
                        <table class="table table-bordered wiz-table mb-0 text-center">
                            <thead>
                            <tr class="bg-secondary text-white text-center">
                                <th colspan="2">{{__('pages.requisition_summary')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td class="fw-medium">{{__('pages.requisition_id')}}</td>
                                <td>{{$requisition->requisition_id}}</td>
                            </tr>

                            @if($requisition->status == 2 )
                                <tr>
                                    <td class="fw-medium">{{__('pages.transfer_date')}}</td>
                                    <td>@formatdate($requisition->complete_date)</td>
                                </tr>
                            @else
                                <tr>
                                    <td class="fw-medium">{{__('pages.created_date')}}</td>
                                    <td>@formatdate($requisition->requisition_date)</td>
                                </tr>
                            @endif

                            <tr>
                                <td class="fw-medium">Status</td>
                                <td>
                                    @if($requisition->status == 0)
                                        <label class="btn btn-soft-warning btn-sm">{{__('pages.pending')}}</label>
                                    @elseif($requisition->status == 1)
                                        <label class="btn btn-soft-secondary btn-sm">{{__('pages.delivered')}}</label>
                                    @elseif($requisition->status == 2)
                                        <label class="btn btn-soft-success btn-sm">{{__('pages.complete')}}</label>
                                    @elseif($requisition->status == 3)
                                        <label class="btn btn-soft-danger btn-sm">{{__('pages.rejected')}}</label>
                                    @else
                                        <label class="btn btn-soft-danger btn-sm">{{__('pages.canceled')}}</label>
                                    @endif
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>


            <div class="wiz-card">
                <div class="wiz-card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered text-center wiz-table">
                            <thead>
                            <tr class="bg-secondary text-white">
                                <th>{{__('pages.sl')}}</th>
                                <th>{{__('pages.product')}}</th>
                                <th>{{__('pages.quantity')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($requisition->requisitionProducts as $key => $requisition_product)
                                <tr>
                                    <td width="3%">{{$key+1}}</td>
                                    <td>{{$requisition_product->product->title}}</td>
                                    <td> {{$requisition_product->quantity}} {{$requisition_product->product->unit->title ?? ''}}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <td class="border-end-0"></td>
                                <td class="text-right border-start-0">
                                    <strong>{{__('pages.total')}}:</strong>
                                </td>

                                <td>
                                    <strong>{{$requisition->requisitionProducts->sum('quantity')}}</strong>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                    <div>
                        @if($requisition->requisition_from == Auth::user()->business_id && $requisition->status == 1)
                            <div class="py-3 d-flex justify-content-end">
                                <a href="{{route('requisition-received', [$requisition->id])}}" class="btn btn-brand btn-brand-primary" onclick="return confirm('Are you sure. you want to receive this requisition ? ')"> Received </a>
                            </div>
                        @endif

                        @if($requisition->requisition_from == Auth::user()->business_id && $requisition->status < 2)
                            <div class="py-3 d-flex justify-content-end">
                                <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#exampleModalLong" class="btn btn-brand btn-brand-danger"> Cancel </a>
                            </div>
                            <!-- Modal -->

                                <form action="{{route('requisition-canceled', [$requisition->id])}}" method="post" data-parsley-validate>
                                    @csrf
                                    <div class="modal fade" id="exampleModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLongTitle">Cancellation Note</h5>
                                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                                <i class="bi bi-x-lg"></i>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div>
                                                <textarea name="cancel_note" placeholder="Short Note" class="form-control" required>{{old('note')}}</textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer justify-content-center">
                                            <button type="submit" class="btn btn-brand btn-brand-secondary">{{__('pages.save')}}</button>
                                        </div>
                                    </div>

                                </div>
                            </div>
                                </form>
                            @endif
                    </div>

                    <div class="row">
                        @if($requisition->status == 4)
                            <div class="col-md-12">
                                <p><b>Cancellation Note</b>: {{$requisition->cancel_note }}</p>
                            </div>
                        @endif

                        @if($requisition->status == 3)
                            <div class="col-md-12">
                                <p><b>Reject Note</b>: {{$requisition->reject_note }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection


