<?php 

namespace App\Services\News\Contracts;


abstract class Adapter
{
    abstract public function adapt(array $properties): News;
}