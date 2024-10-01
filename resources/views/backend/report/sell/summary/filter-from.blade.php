<div class="row g-3">
    <div class="col-sm-6 col-md-4 col-lg">
        <div class="form-group">
            <input type="text" name="start_date" data-date-format="yyyy-mm-dd" value="{{Request::get('start_date')}}" class="datepicker form-control" placeholder="{{__('pages.start_date')}}" autocomplete="off">
        </div>
    </div>

    <div class="col-sm-6 col-md-4 col-lg">
        <div class="form-group text-left">
            <input type="text" name="end_date" data-date-format="yyyy-mm-dd" value="{{Request::get('end_date')}}" class="datepicker form-control" placeholder="{{__('pages.end_date')}}" autocomplete="off">
        </div>
    </div>


    <div class="col-sm-6 col-md-4 col-lg">
        <div class="form-group text-left">
            <select name="by_duration" class="form-control select2-basic">
                <option value="Y-m-d" {{Request::get('by_duration') == 'Y-m-d' ? 'selected': ''}}>{{__('pages.daily')}}</option>
                <option value="Y-W" {{Request::get('by_duration') == 'Y-W' ? 'selected': ''}}>{{__('pages.weekly')}}</option>
                <option value="Y-M" {{Request::get('by_duration') == 'Y-M' ? 'selected': ''}}>{{__('pages.monthly')}}</option>
                <option value="Y" {{Request::get('by_duration') == 'Y' ? 'selected': ''}}>{{__('pages.yearly')}}</option>
            </select>
        </div>
    </div>

    <div class="col-md-2">
        <div class="form-group">
            <button class="btn btn-brand-primary btn-brand w-100">{{__('pages.search')}}</button>
        </div>
    </div>
</div>
