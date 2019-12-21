<?php

declare(strict_types=1);

namespace Tests\Feature\Http\API;

use Tests\TestCase;
use App\User;

class LayoutTest extends TestCase
{
    public function testLayout(): void
    {
        $this->migrate();
        $this->get('/api/pizza/xxx')->assertStatus(404);
        $response = $this->get('/api/pizza/chicago');
        $response->assertStatus(200);
        $data = $response->json();
        $this->assertArrayHasKey('pizza', $data);
        $this->assertSame('chicago', $data['pizza']['slug']);
        $this->assertSame('Chicago', $data['pizza']['name']);
        $this->assertArrayHasKey('description', $data['pizza']);
    }
}
