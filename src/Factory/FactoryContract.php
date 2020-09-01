<?php


namespace Smartymoon\Generator\Factory;


/**
 * 在 Directory 中调用这些抽象方法
 * Interface FactoryContract
 * @package Smartymoon\Generator\Factory
 */
interface FactoryContract
{
    public function buildContent(): string;
    public function getFilePath(): string;
}
