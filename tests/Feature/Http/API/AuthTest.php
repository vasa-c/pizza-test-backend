<?php

declare(strict_types=1);

namespace Tests\Feature\Http\API;

use Tests\TestCase;
use App\User;
use Illuminate\Support\Facades\Auth;

class AuthTest extends TestCase
{
    public function testLogin(): void
    {
        $this->migrate();
        /** @var User $user1 */
        $user1 = factory(User::class)->make();
        $user1->email = 'one@example.com';
        $user1->setPassword('one');
        $user1->save();
        /** @var User $user2 */
        $user2 = factory(User::class)->make();
        $user2->email = 'two@example.com';
        $user2->setPassword('two');
        $user2->save();
        // invalid request
        $this->postJson('/api/login', ['xxx' => 3])->assertStatus(422);
        // wrong password
        $response = $this->postJson('/api/login', [
            'email' => 'one@example.com',
            'password' => 'two',
        ]);
        $response->assertStatus(200);
        $this->assertNull(Auth::user());
        $this->assertNull($response->json()['user']);
        // success
        $response = $this->postJson('/api/login', [
            'email' => 'one@example.com',
            'password' => 'one',
        ]);
        $response->assertStatus(200);
        $this->assertTrue($user1->is(Auth::user()));
        $this->assertEquals($user1->getDataForFrontend(), $response->json()['user']);
        // already logined
        $response = $this->postJson('/api/login', [
            'email' => 'two@example.com',
            'password' => 'two',
        ]);
        $response->assertStatus(200);
        $this->assertTrue($user1->is(Auth::user()));
        $this->assertEquals($user1->getDataForFrontend(), $response->json()['user']);
    }

    public function testLogout(): void
    {
        $this->migrate();
        /** @var User $user */
        $user = factory(User::class)->create();
        $this->be($user);
        $this->assertTrue($user->is(Auth::user()));
        $this->postJson('/api/logout')->assertStatus(200);
        $this->assertNull(Auth::user());
        $this->postJson('/api/logout')->assertStatus(200);
        $this->assertNull(Auth::user());
    }
}
