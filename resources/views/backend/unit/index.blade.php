@extends('backend.layouts.app')
@section('title') {{__('pages.units')}}  @endsection
@section('content')
    <div id="app">
        <unit :all_units="{{$units}}"></unit>
    </div>
@endsection

@section('js')

@endsection
