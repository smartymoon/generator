<?php


namespace Smartymoon\Generator\Factory;


interface FactoryContract
{
    /**
     * @return String
     */
    public function BuildContent(): string;

    public function getFileName(): string;

}
