@extends('backend.layouts.app')
@section('title') {{__('pages.branch')}} @endsection
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <form action="{{route('branch.update', [$branch->id])}}" method="post" data-parsley-validate>
            @csrf
            @method('patch')

            <div class="wiz-card wiz-card-single">
                <!-- Card Header - Dropdown -->
                <div class="wiz-card-header">
                    <h6 class="page-title">{{__('pages.update_branch')}}</h6>
                    <a href="{{route('branch.index')}}" class="btn btn-sm btn-brand btn-soft-primary"><i class="fas fa-code-branch me-1"></i> {{__('pages.all_branch')}}</a>
                </div>
                <div class="wiz-card-body flex-grow-0">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="title" class="custom-label">{{__('pages.branch_name')}} <span class="text-danger">*</span></label>
                                <input type="text" name="title" id="title" value="{{$branch->title}}" placeholder="{{__('pages.branch_name')}}" class="form-control form-control-lg"  required>
                                @if ($errors->has('title'))
                                    <div class="error">{{ $errors->first('title') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="contact_person" class="custom-label">{{__('pages.contact_person')}} <span class="text-danger">*</span></label>
                                <input type="text" name="contact_person" id="contact_person" value="{{$branch->contact_person}}" placeholder="{{__('pages.contact_person')}}" class="form-control form-control-lg"  required>
                                @if ($errors->has('contact_person'))
                                    <div class="error">{{ $errors->first('contact_person') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="phone" class="custom-label">{{__('pages.phone_number')}} <span class="text-danger">*</span></label>
                                <input type="text" name="phone" id="phone" value="{{$branch->phone}}" placeholder="{{__('pages.phone_number')}}" class="form-control form-control-lg"  required>
                                @if ($errors->has('phone'))
                                    <div class="error">{{ $errors->first('phone') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="address" class="custom-label">{{__('pages.address')}} <span class="text-danger">*</span></label>
                                <input type="text" name="address" id="address" value="{{$branch->address}}" placeholder="{{__('pages.address')}}" class="form-control form-control-lg"  required>
                                @if ($errors->has('address'))
                                    <div class="error">{{ $errors->first('address') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="short_description" class="custom-label">{{__('pages.short_description')}}</label>
                                <textarea rows="5" name="short_description" id="short_description" placeholder="{{__('pages.short_description')}}" class="form-control">{{$branch->short_description}}</textarea>
                                @if ($errors->has('short_description'))
                                    <div class="error">{{ $errors->first('short_description') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-brand btn-brand-primary">{{__('pages.update')}}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </form>

    </div>
    <!-- /.container-fluid -->
@endsection

@section('js')

@endsection

