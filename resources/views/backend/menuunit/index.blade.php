@extends('backend.layouts.app')
@section('title') {{__('pages.units')}}  @endsection
@section('content')
    <div id="app">
        <menuunit :all_units="{{$units}}"></menuunit>
    </div>
@endsection

@section('js')

@endsection
