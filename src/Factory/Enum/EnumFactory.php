<?php


namespace Smartymoon\Generator\Factory\Enum;

use Smartymoon\Generator\Factory\FactoryContract;
use Smartymoon\Generator\Factory\MakeFactory;

class EnumFactory extends MakeFactory implements FactoryContract
{
    protected string $stubFile = 'enum/Enum.stub';
    protected string $path = 'app/Enums/';

    protected string $fileName;
    protected array $enums;

    public function initEnum(array $enum)
    {
        $this->fileName = $enum['fileName'];
        $this->enums = $enum['list'];
    }

    public function buildContent(string $content): string
    {
        $content = $this->replaceNamespace('App\Enums', $content);
        $content = str_replace('DummyComment', $this->makeComment(), $content);
        $content = str_replace('DummyClass', $this->fileName, $content);
        $content = str_replace('DummyLabels', $this->makeLabels(), $content);

        return $content;
    }

    public function getFilePath(): string
    {
        return $this->dealModulePath(base_path($this->path)) . $this->fileName . '.php';
    }

    public function getTemplate(): string
    {
        return $this->getStub($this->stubFile);
    }

    private function makeComment(): string
    {
        $string = '';
        foreach($this->enums as $enum) {
            $string .= ' * @method static self ' . $enum['english'] ."()\n";
        }
        return $string;
    }

    /**
     * @return string
     */
    public function makeLabels(): string
    {
        $content = '';
        foreach($this->enums as $enum) {
            $content .= $this->tab(3) . "'{$enum['english']}' => '{$enum['chinese']}',\n";
        }
        return $content;
    }
}
