<?php

namespace Tests\Feature\Console\Install;

use Tests\TestCase;
use Illuminate\Support\Facades\Storage;

/**
 * Test artisan command "install:nginx"
 */
class InstallNginxConfigTest extends TestCase
{
    public function testNginxConfig()
    {
        config()->set('pizza.nginx.listen', '127.0.0.1:443');
        Storage::fake('local');
        $disk = Storage::disk('local');
        $this->assertFalse($disk->exists('nginx.conf'));
        $this->artisan('install:nginx');
        $this->assertTrue($disk->exists('nginx.conf'));
        $result = $disk->get('nginx.conf');
        $this->assertStringContainsString('listen 127.0.0.1:443', $result);
        $this->assertStringContainsString('server_name pizza.loc', $result);
    }
}
