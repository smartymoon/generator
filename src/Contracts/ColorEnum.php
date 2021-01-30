<?php


namespace Smartymoon\Generator\Contracts;


/**
 * 让 Spaite 的 Enum 在 Dcat 的后台能很方便的展示颜色
 * Interface ColorEnum
 * @package Smartymoon\Generator\Contracts
 */
interface ColorEnum
{
    public function getColor(): string;
}