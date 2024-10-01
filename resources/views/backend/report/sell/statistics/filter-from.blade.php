<div class="d-flex flex-wrap flex-md-nowrap gap-3">
    <div class="row gy-3 gx-3 gx-xl-5 flex-grow-1">
        <div class="col-md-6">
            <form action="{{url('report/sell/statistics-filter')}}" method="get">
                <div class="d-flex gap-2">
                    <input type="hidden" name="search_type" value="month">

                    <div class="flex-grow-1 g-2 row">
                        <div class="@can('access_to_all_branch') col-md-6 @else col-12 @endcan">
                            <div class="form-group">
                                <input type="text" name="month" data-date-format="yyyy-M"  value="{{Request::get('month')}}"  placeholder="{{__('pages.select_month')}}" id="datepicker" class="form-control" autocomplete="off">
                            </div>
                        </div>

                    </div>



                    <div class="form-group align-self-end">
                        <button class="btn btn-brand btn-brand-primary">{{__('pages.search')}}</button>
                    </div>
                </div>
            </form>
        </div>

        <div class="col-md-6">
            <form action="{{url('report/sell/statistics-filter')}}" method="get">
                <div class="d-flex gap-2">
                    <input type="hidden" name="search_type" value="year">
                    <div class="flex-grow-1 row g-2">
                        <div class="@can('access_to_all_branch') col-md-6 @else col-12 @endcan">
                            <div class="form-group">
                                <input type="text" name="year" data-date-format="yyyy"  value="{{Request::get('year')}}"  placeholder="{{__('pages.select_year')}}" id="yearPicker" class="form-control" autocomplete="off">
                            </div>
                        </div>

                    <div class=" align-self-end">
                        <button class="btn btn-brand btn-brand-primary">{{__('pages.search')}}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div>
        @include('backend.report.sell.statistics.more-filter')
    </div>
</div>
