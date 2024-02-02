<?php
namespace App\Http\Controllers;

use App\Classes\Bar;


class Foo
{
    public $bar='';

    public function __construct(Bar $bar)
    {
        $this->bar = $bar;
    }
    public function hello()
    {
        return $this->bar->hello();
    }
}