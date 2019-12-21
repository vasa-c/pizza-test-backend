<?php

declare(strict_types=1);

namespace Tests\Feature\Http\API;

use Tests\TestCase;
use App\User;
use Illuminate\Support\Facades\Auth;

class AuthTest extends TestCase
{
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
