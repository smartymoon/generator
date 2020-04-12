<?php
namespace Smartymoon\Generator\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
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
        $manager->handle();
    }

    public function drop()
    {
        $table = Str::plural(Str::snake(request()->input('table')));
        \DB::statement('drop table ' . $table);
    }
}