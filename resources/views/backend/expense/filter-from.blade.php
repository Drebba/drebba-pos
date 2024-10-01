<form action="{{route('expense.index')}}" method="get">

    <div class="row g-3">
        <div class="col-sm-6 col-lg-4 col-xl">
            <div class="form-group">
                <input type="text" name="expense_id" value="{{Request::get('expense_id')}}" class="form-control" placeholder="{{__('pages.expense_id')}}">
            </div>
        </div>

        <div class="col-sm-6 col-lg-4 col-xl">
            <div class="form-group">
                <select name="expense_category_id" class="form-control select2-basic">
                    <option value="">{{__('pages.all_category')}}</option>
                    @foreach($expense_categories as $expense_category)
                        <option value="{{$expense_category->id}}">{{$expense_category->name}} </option>
                    @endforeach
                </select>
            </div>
        </div>


        <div class="col-sm-6 col-lg-4 col-xl">
            <div class="form-group">
                <input type="text" name="start_date" value="{{Request::get('start_date')}}" data-date-format="yyyy-mm-dd" class="datepicker form-control" placeholder="{{__('pages.start_date')}}" autocomplete="off">
            </div>
        </div>

        <div class="col-sm-6 col-lg-4 col-xl">
            <div class="form-group">
                <input type="text" name="end_date" value="{{Request::get('end_date')}}" data-date-format="yyyy-mm-dd" class="datepicker form-control" placeholder="{{__('pages.end_date')}}" autocomplete="off">
            </div>
        </div>

        <div class="col-sm-6 col-lg-4 col-xl">
            <div class="form-group">
                <button class="btn btn-brand-primary btn-brand w-100">{{__('pages.search')}}</button>
            </div>
        </div>
    </div>
</form>
