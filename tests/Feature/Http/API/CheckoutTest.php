<?php

declare(strict_types=1);

namespace Tests\Feature\Http\API;

use App\ServiceContainer;
use Tests\TestCase;
use App\User;
use App\Notifications\OrderForCustomerNotification;
use Illuminate\Support\Facades\Notification;

class CheckoutTest extends TestCase
{
    /**
     * @dataProvider providerSuccess
     * @param bool $guest
     */
    public function testSuccess(bool $guest): void
    {
        $this->migrate();
        Notification::fake();
        /** @var User $user */
        if ($guest) {
            $user = null;
        } else {
            $user = factory(User::class)->create();
            $this->be($user);
        }
        $vars = [
            'pizza' => [
                'chicago' => 1,
                'greek' => 2,
            ],
            'currency' => 'eur',
            'email' => $user ? $user->email : 'one@example.com',
            'name' => 'Tester',
            'address' => 'street',
            'contacts' => '123-45-67',
            'outside' => true,
        ];
        $response = $this->postJson('/api/checkout', $vars);
        $response->assertStatus(200);
        $data = $response->json();
        $this->assertArrayHasKey('order_number', $data);
        $number = $data['order_number'];
        $order = ServiceContainer::orders()->getByNumber($number);
        $this->assertNotNull($order);
        if ($guest) {
            $this->assertEquals($order->getUser()->getDataForFrontend(), $data['user']);
        } else {
            $this->assertTrue(empty($data['user']));
        }
        Notification::assertSentTo(
            $order->getUser(),
            OrderForCustomerNotification::class,
            function (OrderForCustomerNotification $notification, $channels) use ($order) {
                $checkout = $notification->toArray(null)['checkout'];
                return $order->is($checkout->order);
            }
        );
    }

    /**
     * @return array
     */
    public function providerSuccess(): array
    {
        return [
            'guest' => [true],
            'auth' => [false],
        ];
    }

    public function testLoginReq(): void
    {
        $this->migrate();
        factory(User::class)->create([
            'email' => 'one@example.com',
        ]);
        $vars = [
            'pizza' => [
                'chicago' => 1,
            ],
            'currency' => 'eur',
            'email' => 'one@example.com',
            'name' => 'Tester',
            'address' => 'street',
            'contacts' => '123-45-67',
            'outside' => true,
        ];
        $response = $this->postJson('/api/checkout', $vars);
        $response->assertStatus(200);
        $this->assertTrue($response->json()['req_login'] ?? null);
    }

    public function testFailRequest(): void
    {
        $this->migrate();
        $vars = [
            'pizza' => [
                'chicago' => 2,
                'greek' => 0,
            ],
            'currency' => 'eur',
            'email' => 'a@example.com',
            'address' => 'street',
            'contacts' => '123-45-67',
            'outside' => true,
        ];
        $this->postJson('/api/checkout', $vars)->assertStatus(422);
        $vars['pizza']['greek'] = 2;
        $vars['pizza']['xxx'] = 3;
        $this->postJson('/api/checkout', $vars)->assertStatus(422);
        unset($vars['pizza']['xxx']);
        $vars['currency'] = 'rub';
        $this->postJson('/api/checkout', $vars)->assertStatus(422);
    }
}
