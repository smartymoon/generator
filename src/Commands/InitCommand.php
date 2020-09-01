<?php


namespace Smartymoon\Generator\Commands;


use Illuminate\Console\Command;

class InitCommand extends Command
{
    protected $name = 'sm:init';
    protected $description = "撤回刚刚的更新到上一个 commit by Git";

    public function handle()
    {
        // BaseController
        copy(
            __DIR__ . '/../stubs/controller/BaseController.stub',
            base_path('app/Http/Controllers')
        );
        // BaseRepository
        copy(
            __DIR__ . '/../stubs/repository/BaseRepository.stub',
            base_path('app/Repositories')
        );
        // Enum Lang

        // 在 api.php 中加 //DummyRoute
    }

}
