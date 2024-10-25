<div class="d-flex flex-wrap flex-md-nowrap gap-3">
    <div class="row gy-3 gx-3 gx-xl-5 flex-grow-1">
        <div class="col-md-6">
            <form action="{{url('report/purchase/statistics-filter')}}" method="get">
                <div class="d-flex gap-2">
                    <div class="flex-grow-1 g-2 row">
                        <input type="hidden" name="search_type" value="month">

                        <div class="@can('access_to_all_branch') col-md-6 @else col-12 @endcan">
                            <div class="form-group text-left">
                                <input type="text" name="month" data-date-format="yyyy-M"  value="{{Request::get('month')}}"  placeholder="{{__('pages.select_month')}}" id="datepicker" class="form-control" autocomplete="off">
                            </div>
                        </div>
                    </div>

                    <div class="form-group align-self-end">
                        <button class="btn btn-brand-primary btn-brand">{{__('pages.search')}}</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-md-6">
            <form action="{{url('report/purchase/statistics-filter')}}" method="get">
                <div class="d-flex gap-2">

                    <input type="hidden" name="search_type" value="year">
                    <div class="flex-grow-1 row g-2">
                        <div class="@can('access_to_all_branch') col-md-6 @else col-12 @endcan">
                            <div class="form-group">
                                <input type="text" name="year" data-date-format="yyyy"  value="{{Request::get('year')}}"  placeholder="{{__('pages.select_year')}}" id="yearPicker" class="form-control" autocomplete="off">
                            </div>
                        </div>
                    </div>

                    <div class="form-group align-self-end">
                        <button class="btn btn-brand-primary btn-brand">{{__('pages.search')}}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div>
        {{-- <div class="dropdown show float-right">
            <a class="btn btn-brand btn-brand-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                {{__('pages.last')}} @if(isset($days)) {{$days}} @else ** @endif {{__('pages.days')}}
            </a>

            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuLink">
                <a class="dropdown-item" href="{{url('report/purchase/statistics/last/7/days')}}">{{__('pages.last')}} 7 {{__('pages.days')}}</a>
                <a class="dropdown-item" href="{{url('report/purchase/statistics/last/15/days')}}">{{__('pages.last')}} 15 {{__('pages.days')}}</a>
                <a class="dropdown-item" href="{{url('report/purchase/statistics/last/30/days')}}">{{__('pages.last')}} 30 {{__('pages.days')}}</a>
                <a class="dropdown-item" href="{{url('report/purchase/statistics/last/45/days')}}">{{__('pages.last')}} 45 {{__('pages.days')}}</a>
                <a class="dropdown-item" href="{{url('report/purchase/statistics/last/60/days')}}">{{__('pages.last')}} 60 {{__('pages.days')}}</a>
            </div>
        </div> --}}
    </div>
</div>
