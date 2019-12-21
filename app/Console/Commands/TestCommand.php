<?php

declare(strict_types=1);

namespace App\Console\Commands;

class TestCommand extends BaseCommand
{
    /**
     * {@inheritdoc}
     */
    protected $signature = 'test';

    /**
     * {@inheritdoc}
     */
    protected $description = 'Just test';

    /**
     * {@inheritdoc}
     */
    public function handle()
    {
        $this->line('PHP: '.PHP_VERSION);
        $this->line('Env: '.app()->environment());
    }
}
