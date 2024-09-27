@extends('backend.layouts.app')
@section('title') Business @endsection
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <form action="{{route('businesses.update', [$business->id])}}" method="post" data-parsley-validate>
            @csrf
            @method('patch')

            <div class="wiz-card wiz-card-single">
                <!-- Card Header - Dropdown -->
                <div class="wiz-card-header">
                    <h6 class="page-title">Update Business</h6>
                    <a href="{{route('businesses.index')}}" class="btn btn-sm btn-brand btn-soft-primary"><i class="fas fa-code-branch me-1"></i> All Business</a>
                </div>
                <div class="wiz-card-body flex-grow-0">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name" class="custom-label">Business Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" id="name" value="{{$business->name}}" placeholder="Business Name" class="form-control form-control-lg"  required>
                                @if ($errors->has('name'))
                                    <div class="error">{{ $errors->first('name') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="phone" class="custom-label">Phone<span class="text-danger">*</span></label>
                                <input type="text" name="phone" id="phone" value="{{$business->phone}}" placeholder="Business phone" class="form-control form-control-lg"  required>
                                @if ($errors->has('phone'))
                                    <div class="error">{{ $errors->first('phone') }}</div>
                                @endif
                            </div>
                        </div>


                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email" class="custom-label">Business Email <span class="text-danger">*</span></label>
                                <input type="text" name="email" id="email" value="{{$business->email}}" placeholder="Business email" class="form-control form-control-lg"  required>
                                @if ($errors->has('email'))
                                    <div class="error">{{ $errors->first('email') }}</div>
                                @endif
                            </div>
                        </div>


                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="address" class="custom-label">Business Address <span class="text-danger">*</span></label>
                                <input type="text" name="address" id="address" value="{{$business->address}}" placeholder="Business address" class="form-control form-control-lg"  required>
                                @if ($errors->has('address'))
                                    <div class="error">{{ $errors->first('address') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="contact_person" class="custom-label">Contact Person <span class="text-danger">*</span></label>
                                <input type="text" name="contact_person" id="contact_person" value="{{$business->contact_person}}" placeholder="Business conatct Person" class="form-control form-control-lg"  required>
                                @if ($errors->has('contact_person'))
                                    <div class="error">{{ $errors->first('contact_person') }}</div>
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

