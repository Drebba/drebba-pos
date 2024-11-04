@extends('backend.layouts.app')
@section('title') {{__('pages.category')}}  @endsection
@section('content')
    <div id="app">
        <menucategory :all_categories="{{$categories}}"></menucategory>
    </div>
@endsection

@section('js')

@endsection
