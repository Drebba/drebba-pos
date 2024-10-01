<form action="{{route('requisition.index')}}" method="get">
    <div class="row g-3">
        <div class="col-sm-6 col-md-4 col-lg">
            <div class="form-group">
                <input type="text" name="invoice" class="form-control" placeholder="{{__('pages.requisition_id')}}">
            </div>
        </div>

        <div class="col-sm-6 col-md-4 col-lg">
            <div class="form-group">
                <input type="text" name="start_date" data-date-format="yyyy-mm-dd" value="{{Request::get('start_date')}}" class="datepicker form-control" placeholder="{{__('pages.start_date')}}" autocomplete="off">
            </div>
        </div>

        <div class="col-sm-6 col-md-4 col-lg">
            <div class="form-group">
                <input type="text" name="end_date" data-date-format="yyyy-mm-dd" value="{{Request::get('end_date')}}" class="datepicker form-control" placeholder="{{__('pages.end_date')}}" autocomplete="off">
            </div>
        </div>

        <div class="col-sm-6 col-md-4 col-lg">
            <div class="form-group">
                <select name="status" class="form-select">
                    <option value="">{{__('pages.select_one')}}</option>
                    <option value="0" {{Request::get('status') ? Request::get('status') == 0 ? 'selected' : '' : ''}}>{{__('pages.pending')}} </option>
                    <option value="1" {{Request::get('status') == 1 ? 'selected' : ''}}>{{__('pages.delivered')}} </option>
                    <option value="2" {{Request::get('status') == 2 ? 'selected' : ''}}>{{__('pages.complete')}} </option>
                    <option value="3" {{Request::get('status') == 3 ? 'selected' : ''}}>{{__('pages.rejected')}} </option>
                    <option value="4" {{Request::get('status') == 4 ? 'selected' : ''}}>{{__('pages.canceled')}} </option>
                </select>
            </div>
        </div>

        @if (Auth::user()->can('access_to_all_branch'))
            <div class="col-sm-6 col-md-4 col-lg">
                <div class="form-group">
                    <button class="btn btn-brand-primary btn-brand w-100">{{__('pages.search')}}</button>
                </div>
            </div>
        @else
            <div class="col-sm-6 col-md-4 col-lg">
                <div class="form-group">
                    <button class="btn btn-brand-primary btn-brand w-100">{{__('pages.search')}}</button>
                </div>
            </div>
        @endif

    </div>
</form>
