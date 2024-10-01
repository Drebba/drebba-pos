<div class="d-flex flex-wrap flex-lg-nowrap gap-3">
    <div class="row g-3 flex-grow-1">
        <div class="col-md-6 col-lg-4 col-xl">
            <div class="form-group">
                <input type="text" name="start_date" data-date-format="yyyy-mm-dd" value="{{Request::get('start_date')}}" class="datepicker form-control" placeholder="{{__('pages.start_date')}}" autocomplete="off">
            </div>
        </div>

        <div class="col-md-6 col-lg-4 col-xl">
            <div class="form-group">
                <input type="text" name="end_date" data-date-format="yyyy-mm-dd" value="{{Request::get('end_date')}}" class="datepicker form-control" placeholder="{{__('pages.end_date')}}" autocomplete="off">
            </div>
        </div>

        <div class="col-md-6 col-lg-4 col-xl">
            <div class="form-group">
                <select name="supplier_id" class="form-select select2-basic">
                    <option value="">{{__('pages.all_supplier')}}</option>
                    @foreach($suppliers as $supplier)
                        <option value="{{$supplier->id}}" {{Request::get('supplier_id') == $supplier->id ? 'selected': ''}}> {{$supplier->company_name}} </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-md-6 col-lg-4 col-xl">
            <div class="form-group">
                <select name="by_duration" class="form-select select2-basic">
                    <option value="Y-m-d" {{Request::get('by_duration') == 'Y-m-d' ? 'selected': ''}}>{{__('pages.daily')}}</option>
                    <option value="Y-W" {{Request::get('by_duration') == 'Y-W' ? 'selected': ''}}>{{__('pages.weekly')}}</option>
                    <option value="Y-M" {{Request::get('by_duration') == 'Y-M' ? 'selected': ''}}>{{__('pages.monthly')}}</option>
                    <option value="Y" {{Request::get('by_duration') == 'Y' ? 'selected': ''}}>{{__('pages.yearly')}}</option>
                </select>
            </div>
        </div>
    </div>

    <div>
        <div class="form-group">
            <button class="btn btn-brand btn-brand-primary">{{__('pages.search')}}</button>
        </div>
    </div>
</div>
