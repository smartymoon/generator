<?php
namespace Smartymoon\Generator\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Smartymoon\Generator\Config;
use Smartymoon\Generator\Exceptions\GenerateException;
use Smartymoon\Generator\Manager;

class HomeController extends Controller
{
    public function index()
    {
        return view('generator::index');
    }

    public function store()
    {
        $manager = new Manager(request()->all());

        // 有可能在中间时结束， try catch, 有可能成功，返回
        // 异常： 可捕获，不可捕获

        $res = [
            'code' => 201 , // 201 表示成功, 204 表示失败, 4XX,5XX 表示系统异常
            'message' => ''
        ];

        try {
            $manager->handle();
        } catch(GenerateException $e) {
            $res['code'] = 204;
            $res['message'] = $e->getMessage() ?: '发生错误';
        }

        return $res;
    }

    public function drop()
    {
        $table = Str::plural(Str::snake(request()->input('table')));
        \DB::statement('drop table ' . $table);
    }
}