<div class="d-flex gap-3 flex-wrap flex-lg-nowrap">

    <div class="flex-grow-1 row g-3">
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
    </div>

    <div>
        <div class="form-group">
            <button class="btn btn-brand btn-brand-primary">{{__('pages.search')}}</button>
        </div>
    </div>
</div>
