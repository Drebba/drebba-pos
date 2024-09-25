@extends('backend.layouts.app')
@section('title') {{__('pages.expense')}}  @endsection
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">


        <div class="wiz-card">
            <div class="wiz-card-header">
                <h5 class="wiz-card-title mb-0">{{__('pages.create_expense')}}</h5>
                <div>
                    <a href="{{route('expense.index')}}" class="btn btn-brand btn-brand-secondary btn-sm"><i class="fa fa-list me-2"></i> {{__('pages.expense_list')}}</a>
                </div>
            </div>
            <div class="wiz-card-body p-4">
                <form action="{{route('expense.store')}}" method="post" data-parsley-validate>
                    @csrf

                    <div>
                        <div class="custom-form-group">
                            <label for="expense_date" class="custom-label">{{__('pages.expense_date')}} <span class="text-danger">*</span></label>
                            <input name="expense_date" value="{{old('expense_date') ? old('expense_date') : \Carbon\Carbon::now()->format('Y-m-d')}}" id="expense_date" type="text" data-date-format="yyyy-mm-dd" class="datepicker form-control" placeholder="{{__('pages.expense_date')}}" required autocomplete="off">
                            @if ($errors->has('expense_date'))
                                <div class="error">{{ $errors->first('expense_date') }}</div>
                            @endif
                        </div>

                        <div class="custom-form-group">
                            <label for="expense_category_id" class="custom-label">{{__('pages.expense_category')}} <span class="text-danger">*</span></label>
                            <select name="expense_category_id" id="expense_category_id" class="form-select select2-basic">
                                <option value="">{{__('pages.select_category')}}</option>
                                @foreach($expense_categories as $expense_category)
                                    <option value="{{$expense_category->id}}" {{old('expense_category_id') == $expense_category->id ? 'selected' : ''}}>{{$expense_category->name}}</option>
                                @endforeach
                            </select>

                            @if ($errors->has('expense_category_id'))
                                <div class="error mt-1">{{ $errors->first('expense_category_id') }}</div>
                            @endif
                        </div>


                        <div class="custom-form-group">
                            <label for="amount" class="custom-label">{{__('pages.amount')}} <span class="text-danger">*</span></label>
                            <input type="number" name="amount" step=".1" min="0" id="amount" value="{{old('amount')}}" placeholder="{{__('pages.amount')}}" class="form-control" aria-describedby="emailHelp" required>
                            @if ($errors->has('amount'))
                                <div class="error">{{ $errors->first('amount') }}</div>
                            @endif
                        </div>

                        <div class="custom-form-group mb-4">
                            <label for="note" class="custom-label">{{__('pages.note')}}</label>
                            <textarea name="note" placeholder="Short Note" class="form-control">{{old('note')}}</textarea>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-brand btn-brand-primary">{{__('pages.save')}}</button>
                        </div>
                    </div>

                </form>
            </div>
        </div>



    </div>
    <!-- /.container-fluid -->
@endsection

@section('css')
    <link rel="stylesheet" type="text/css" href="{{asset('admin/plugin/select2/select2.min.css')}}" />
    <link rel="stylesheet" type="text/css" href="{{asset('admin/plugin/select2/select2-bootstrap/select2-bootstrap-5-theme.css')}}" />


    {{--========== Datepicker ============--}}
    <link rel="stylesheet" type="text/css" href="{{asset('admin/plugin/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css')}}" />
@endsection
@section('js')
    <script src="{{asset('/backend/js/custom.js')}}"></script>

    <script src="{{asset('admin/plugin/select2/select2.min.js')}}"></script>
    <script>
        $('.select2-basic').select2({
            theme: "bootstrap-5",
            width: $( this ).data( 'width' ) ? $( this ).data( 'width' ) : $( this ).hasClass( 'w-100' ) ? '100%' : 'style',
            placeholder: $( this ).data( 'placeholder' ),
        });
    </script>

    {{--============== Datepicker ================--}}
    <script src="{{asset('admin/plugin/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js')}}"></script>
    <script>
        $('.datepicker').datepicker({
            format:'yyyy-mm-dd',
            zIndexOffset: 1198,
        }).on('changeDate', function(e) {
            // when the date is changed
            $(this).datepicker('hide');
        });
    </script>
@endsection

