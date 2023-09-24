<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomCrawlerController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/test/{id?}',[CustomCrawlerController::class,'getData']);

Route::get('/', function () {
    //phpinfo();
    //return view('welcome');


    // mongo db test connection 
    $connection = DB::connection('mongodb');
    $msg = "OK";
    try{
        $connection->command(['ping'=>1]);
    }catch(\Exception $e){
        $msg = "No connection";
    }

    return $msg;

});
