<?php


namespace Smartymoon\Generator\Factory\Admin;

use Illuminate\Support\Facades\Artisan;
use Smartymoon\Generator\factory\BaseFactory;

class AdminFactory extends BaseFactory
{

    protected $buildType = 'patch';
    protected $stub = '';
    protected $path = 'app/Admin/routes.php';

    /**
     * @inheritDoc
     */
    public function buildContent($content)
    {
        dump('make admin comamnd');
        Artisan::call('admin:make', [
            'name' => $this->model . 'Controller',
            '--model' => "App\\Models\\".$this->model,
            '--title' => $this->admin_menu,
        ]);
        dump('make admin command');

        $content = str_replace('//DummyRoute', $this->makeRoute(), $content);

        $count = \DB::table('admin_menu')->count();
        $title = $this->admin_menu ? $this->admin_menu : $this->ucModel;

        if(! \DB::table('admin_menu')->where('title', $title)->first()) {
            \DB::table('admin_menu')->insert([
                'parent_id' => 0,
                'order' => $count + 1,
                'title' => $title,
                'uri' => $this->tableName(),
                'icon' => 'fa-bars',
            ]);
        }

        Artisan::call('iseed', [
            'tables'  => 'admin_menu,admin_permissions,admin_role_menu,admin_role_permissions,admin_roles,admin_user_permissions,admin_users,admin_role_users',
            '--force' => true
        ]);

        return $content;
    }

    protected function getFileName()
    {

    }

    private function makeRoute()
    {
        return '$router->resource(\''. $this->tableName() .'\', \''. $this->model .'Controller\');'."\n"
            .$this->tab(1). '//DummyRoute';
    }
}
