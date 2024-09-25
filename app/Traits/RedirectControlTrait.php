<?php


namespace App\Traits;

trait RedirectControlTrait
{
    private function controlRedirection($request, $route, $model){
        switch ($request) {
            case $request->has('save_and_add'):
                toastrMessage('success', trans('message.created', ['title' => $model]));
                return redirect()->back();
                break;
            case $request->has('update_and_create_another'):
                toastrMessage('success', trans('message.updated', ['title' => $model]));
                return redirect()->route($route.'.create');
                break;
            case $request->has('save'):
                toastrMessage('success', trans('message.created', ['title' => $model]));
                return redirect()->route($route.'.index');
                break;
            default :
                toastrMessage('success', trans('message.created', ['title' => $model]));
                return redirect()->route($route.'.index');
        }
    }
}
