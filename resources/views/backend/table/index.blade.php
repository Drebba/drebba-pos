@extends('backend.layouts.app')
@section('title') Table  @endsection
@section('content')
    <div id="app">
        <tables :all_tables="{{$tables}}"></tables>
    </div>
@endsection

@section('js')

@endsection
