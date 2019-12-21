<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Contracts\Console\Kernel;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function migrate(): void
    {
        $this->artisan('migrate');
        $this->app[Kernel::class]->setArtisan(null);
    }
}
