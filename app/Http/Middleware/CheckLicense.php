<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use DB;

class CheckLicense
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            $connection = DB::connection()->getPdo();
        } catch (\Exception $e) {
            return redirect('install');
        }

        if ($connection){
            if (empty(get_option('purchase_code')))
                return redirect()->route('verifyLicense');
        }



        return $next($request);
    }
}
