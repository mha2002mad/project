<?php

namespace Tests;

use Illuminate\Container\Attributes\DB;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\DB as FacadesDB;
use Tests\CreatesApplication;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        Parent::setUp();

        $this->seed();
    }
}
