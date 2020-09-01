<?php


namespace Smartymoon\Generator\Commands;


use Illuminate\Console\Command;

class RollbackCommand extends Command
{

    protected $name = "sm:rollback";

    protected $description = "撤回刚刚的更新到上一个 commit by Git";


    public function handle()
    {
        exec('git reset --hard');
        exec('git clean -f');
    }

}
