<?php

namespace App\Http\Controllers;

use App\Http\Requests\EnvRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use PDO;

class InstallController extends Controller
{
    public function setDatabase(){

//        Artisan::call('optimize:clear');
//        Artisan::call('migrate');
//        Artisan::call('db:seed');

        return view('install.set-db');
    }

    public function saveDBInfo(EnvRequest $request){

        $servername = $request->DB_HOST;
        $username = $request->DB_USERNAME;
        $password = $request->DB_PASSWORD;
        $db_name = $request->DB_DATABASE;

        try {
            $conn = new PDO("mysql:host=$servername; dbname=$db_name", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(\Exception $e) {
            return redirect()->back()->with('error', 'Database connection failed. Please try again')->withInput();
        }


        if ($conn) {
           // Artisan::call('config:cache');

            $inputs = Arr::except($request->all(), ['_token']);
            $keys = [];

            foreach ($inputs as $k => $v) {
                $keys[$k] = $k;
            }

            foreach ($inputs as $key => $value) {
                $oldValue = env($key);
                $newValue = str_replace(' ', '', $value);

                $path = base_path('.env');
                if (file_exists($path)) {
                    file_put_contents(
                        $path, str_replace($key . '=' . $oldValue, $key . '=' . $newValue, file_get_contents($path))
                    );
                }
            }

            try {
                Artisan::call('optimize:clear');
                Artisan::call('migrate');
                Artisan::call('db:seed');
                return redirect(url($request->base_url. '/register'))->with('success', 'Database connection Success. Please Create a Admin User');
            } catch (\Exception $exception)
            {
                return redirect(route('dbRedirect'));
            }


        }
    }

    public function dbRedirect()
    {
        Artisan::call('optimize:clear');
        Artisan::call('migrate');
        Artisan::call('db:seed');
        return redirect(url('register'))->with('success', 'Database connection Success. Please Create a Admin User');
    }

}
