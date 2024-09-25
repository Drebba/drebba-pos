@extends('backend.layouts.app')
@section('title') {{__('pages.notice')}}  @endsection


@section('content')
    <div class="container-fluid">
        <div class="wiz-card">
            <!-- Card Header - Dropdown -->
            <div class="wiz-card-header">
                <h6 class="wiz-card-title">{{__('pages.create')}} {{__('pages.notice')}}</h6>
                <div>
                    <a href="{{route('notice.index')}}" class="btn btn-brand btn-brand-secondary btn-sm"><i class="fa fa-list me-1"></i> {{__('pages.manage')}} {{__('pages.notice')}}</a>
                </div>
            </div>

            <!-- Card Body -->
            <div class="wiz-card-body p-lg-4">
                <form action="{{route('notice.store')}}" method="post" data-parsley-validate>
                    @csrf

                    <div class="row g-3">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="title" class="custom-label">{{__('pages.title')}} <span class="text-danger">*</span></label>
                                <input name="title" value="{{old('title') ? old('title') : ''}}" id="title" type="text" class="form-control form-control-lg" placeholder="{{__('pages.title')}}" required autocomplete="off">
                                @if ($errors->has('title'))
                                    <div class="error">{{ $errors->first('title') }}</div>
                                @endif
                            </div>
                        </div>


                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="user_id" class="custom-label">{{__('pages.notify_to')}} <span class="text-danger">*</span></label>
                                <select name="user_id[]" id="user_id" class="form-select select2-basic d-flex" multiple="true">
                                    <option value="all" selected>{{__('pages.all_employee')}}</option>
                                    @foreach($users as $user)
                                        <option value="{{$user->id}}" {{old('user_id') == $user->id ? 'selected' : ''}}>{{$user->name}}</option>
                                    @endforeach
                                </select>

                                @if ($errors->has('user_id'))
                                    <div class="error mt-1">{{ $errors->first('user_id') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-8 col-xl-10">
                            <div class="form-group">
                                <label for="notify_date" class="custom-label">{{__('pages.notify_date')}} <span class="text-danger">*</span></label>
                                <input name="notify_date" value="{{old('notify_date') ? old('notify_date') : \Carbon\Carbon::now()->format('Y-m-d')}}" id="notify_date" type="text" data-date-format="yyyy-mm-dd" class="datepicker form-control form-control-lg" placeholder="{{__('pages.notify_date')}}" required autocomplete="off">
                                @if ($errors->has('notify_date'))
                                    <div class="error">{{ $errors->first('notify_date') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-4 col-xl-2">
                            <div class="form-group">
                                <label for="notify_time" class="custom-label">{{__('pages.notify_time')}} <span class="text-danger">*</span></label>
                                <input type="text" name="notify_time" value="{{old('notify_time') ? old('notify_time') :  \Carbon\Carbon::now()->format('h:00 a')}}" id="notify_time" class="form-control form-control-lg timepicker" placeholder="{{__('pages.notify_time')}}" autocomplete="off">
                                @if ($errors->has('notify_time'))
                                    <div class="error">{{ $errors->first('notify_time') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="note" class="custom-label">{{__('pages.description')}}</label>
                                <textarea name="description" placeholder="{{__('pages.description')}}" class="form-control" rows="5" required>{{old('description')}}</textarea>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="text-end py-3">
                                <button type="submit" class="btn btn-brand-primary btn-brand">{{__('pages.save')}}</button>
                            </div>
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
    <link rel="stylesheet" href="{{asset('backend/css/jquery.timepicker.min.css')}}">
@endsection
@section('js')
    <script src="{{asset('backend/js/custom.js')}}"></script>

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
    <script src="{{asset('backend/js/jquery.timepicker.min.js')}}"></script>
    <script>
        $('.datepicker').datepicker({
            format:'yyyy-mm-dd',
            zIndexOffset: 1198,
        }).on('changeDate', function(e) {
            // when the date is changed
            $(this).datepicker('hide');
        });

        $('.timepicker').timepicker();
    </script>
@endsection
