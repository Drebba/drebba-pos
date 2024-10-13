<div class="table-responsive-lg">
    <table class="table table-sm table-bordered text-center wiz-table mw-col-width-skip-first">
    <thead>
    <tr class="bg-secondary text-white">
        <th>{{__('pages.sl')}}</th>
        <th>{{__('pages.requisition_id')}}</th>
        <th> {{__('pages.from')}}</th>
        <th> {{__('pages.to')}}</th>
        <th>{{__('pages.created_date')}}</th>
        <th width="10%">{{__('pages.status')}}</th>
        <th width="8%">{{__('pages.action')}}</th>
    </tr>
    </thead>
    <tbody>
    @foreach($requisitions as $key => $requisition)
        <tr>
            <td>{{$key+1}}</td>
            <td>{{$requisition->requisition_id}}</td>
            <td>{{$requisition->requisitionFrom->title}}</td>
            <td>{{$requisition->requisitionTo->title}}</td>
            <td>
                @formatdate($requisition->requisition_date)
            </td>
            <td>
                @if($requisition->status == 0)
                    <label class="custom-badge badge-soft-warning">Pending</label>
                @elseif($requisition->status == 1)
                    <label class="custom-badge badge-soft-primary">Delivered</label>
                @elseif($requisition->status == 2)
                    <label class="custom-badge badge-soft-success">Complete</label>
                @elseif($requisition->status == 3)
                    <label class="custom-badge badge-soft-danger">Rejected</label>
                @else
                    <label class="custom-badge badge-soft-danger">Canceled</label>
                @endif
            </td>
            <td>
                <div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                    <a href="{{route('requisition.show', [$requisition->id])}}" class="mx-2"><i class="bi bi-eye"></i></a>
                    @if($requisition->requisition_from == Auth::user()->business_id && $requisition->status == 0)
                        <a href="{{route('requisition.edit', [$requisition->id])}}" class="mx-2 text-brand-primary"><i class="bi bi-pencil"></i> {{$requisition->sttaus}}</a>
                        <a href="javascript:void(0);" onclick="$(this).confirmDelete($('#delete-{{$key}}')) " class="mx-2 text-danger"><i class="bi bi-trash3"></i></a>
                        <form action="{{ route('requisition.destroy',$requisition->id) }}" method="post" id="delete-{{$key}}"> @csrf @method('delete') </form>
                    @endif
                </div>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
</div>

