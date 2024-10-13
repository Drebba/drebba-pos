@extends('backend.layouts.app')
@section('title') {{__('pages.todo')}} @endsection
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="wiz-card">
                    <!-- Card Header - Dropdown -->
                    <div class="wiz-card-header">
                        <h6 class="wiz-card-title">{{__('pages.todo_list')}}</h6>
                    </div>
                    <!-- Card Body -->
                    <div class="wiz-card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered text-center wiz-table mw-col-width-skip-first">
                                <thead>
                                    <tr class="bg-secondary text-white">
                                        <th style="width: 60px">{{__('pages.sl')}}</th>
                                        <th>{{__('pages.title')}}</th>
                                        <th>{{__('pages.created_at')}}</th>
                                        <th style="width: 60px">{{__('pages.action')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($todos as $key => $todo)
                                    <tr class="{{$todo->status == 1 ? 'bg-success' : 'bg-warning'}}">
                                        <td>{{$key+1}}</td>
                                        <td>{{$todo->title}}</td>
                                        <td>@formatdate($todo->created_at)</td>
                                        <td class="font-14">
                                            <div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                                                <a href="javascript:void(0);" onclick="$(this).confirmDelete($('#delete-{{$key}}')) "><i class="bi bi-trash text-danger"></i></a>
                                                <form action="{{ route('todo.destroy',$todo->id) }}" method="post" id="delete-{{$key}}"> @csrf @method('delete') </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{$todos->links()}}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
@endsection

@section('js')

@endsection

