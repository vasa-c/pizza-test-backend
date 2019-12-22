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
        $response = $this->get('/api/layout');
        $response->assertStatus(200);
        $data = $response->json();
        $this->assertArrayHasKey('pizza_types', $data);
        $this->assertArrayHasKey('user', $data);
        $this->assertArrayHasKey('csrf', $data);
        $types = $data['pizza_types'];
        $this->assertIsArray($types);
        $this->assertNull($data['user']);
        $this->assertSame(csrf_token(), $data['csrf']);

        $user = new User();
        $user->email = 'me@example.com';
        $user->name = 'Me';
        $user->address = 'street 25';
        $user->contacts = '777-77';
        $user->setPassword('xxx');
        $user->save();
        $user->refresh();
        $this->be($user);
        $expected = $data;
        $expected['user'] = [
            'email' => 'me@example.com',
            'name' => 'Me',
            'currency' => 'eur',
            'address' => 'street 25',
            'contacts' => '777-77',
        ];
        $response = $this->get('/api/layout');
        $response->assertStatus(200);
        $data = $response->json();
        $this->assertEquals($expected, $data);
    }
}
