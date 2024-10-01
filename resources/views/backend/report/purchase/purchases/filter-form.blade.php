<div class="d-flex flex-wrap flex-lg-nowrap gap-3">

    <div class="row g-3 flex-grow-1">
        <div class="col-lg">
            <div class="form-group text-left">
                <input type="text" name="start_date" data-date-format="yyyy-mm-dd" value="{{Request::get('start_date')}}" class="datepicker form-control" placeholder="{{__('pages.start_date')}}" autocomplete="off">
            </div>
        </div>

        <div class="col-lg">
            <div class="form-group text-left">
                <input type="text" name="end_date" data-date-format="yyyy-mm-dd" value="{{Request::get('end_date')}}" class="datepicker form-control" placeholder="{{__('pages.end_date')}}" autocomplete="off">
            </div>
        </div>

        <div class="col-lg">
            <div class="form-group text-left">
                <select name="supplier_id" class="form-control select2-basic">
                    <option value="">{{__('pages.all_supplier')}}</option>
                    @foreach($suppliers as $supplier)
                        <option value="{{$supplier->id}}" {{Request::get('supplier_id') == $supplier->id ? 'selected': ''}}> {{$supplier->company_name}} </option>
                    @endforeach
                </select>
            </div>
        </div>

    </div>


    <div class="form-group text-end flex-grow-1 flex-lg-grow-0">
        <button class="btn btn-brand btn-brand-primary">{{__('pages.search')}}</button>
    </div>
</div>
