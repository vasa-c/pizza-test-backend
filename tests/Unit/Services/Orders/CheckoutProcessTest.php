<?php

declare(strict_types=1);

namespace Tests\Unit\Services\Orders;

use Tests\TestCase;
use App\{
    Order,
    User,
    ServiceContainer
};
use App\Http\Requests\CheckoutRequest;
use Carbon\Carbon;

class CheckoutProcessTest extends TestCase
{
    /**
     * @var array
     */
    private $request = [
        'pizza' => [
            'chicago' => 2,
            'greek' => 1,
        ],
        'currency' => 'eur',
        'email' => 'me@example.com',
        'name' => 'John Tester',
        'address' => 'street 25',
        'contacts' => '123-45-67',
        'outside' => true,
    ];

    public function testWrongRequest(): void
    {
        $this->migrate();
        $data = $this->request;
        $data['pizza']['xxx'] = 3;
        $request = new CheckoutRequest($data);
        $result = ServiceContainer::orders()->checkout($request, null);
        $this->assertSame(422, $result->responseCode);
    }

    public function testLoginRequired(): void
    {
        $this->migrate();
        factory(User::class)->create(['email' => 'me@example.com']);
        $request = new CheckoutRequest($this->request);
        $result = ServiceContainer::orders()->checkout($request, null);
        $this->assertSame(200, $result->responseCode);
    }

    public function testSuccessWithUser(): void
    {
        $this->migrate();
        /** @var User $user */
        Carbon::setTestNow('2020-01-03 10:10:10');
        $user = factory(User::class)->create(['email' => 'tester@example.com']);
        Carbon::setTestNow('2020-01-03 11:11:11');
        $request = new CheckoutRequest($this->request);
        $result = ServiceContainer::orders()->checkout($request, $user);
        $this->assertSame(200, $result->responseCode);
        $this->assertTrue($user->is($result->user));
        $order = $result->order;
        $this->assertInstanceOf(Order::class, $order);
        $this->assertEquals([
            'order_number' => $order->number,
            'user' => $user->getDataForFrontend(),
        ], $result->responseData);
        $this->assertGreaterThan(1000, $order->number);
        $this->assertSame($user->id, $order->user_id);
        $this->assertFalse($result->isUserCreated());
        $this->assertSame(Order::STATUS_CREATED, $order->status);
        $this->assertSame('me@example.com', $order->email);
        $this->assertSame('John Tester', $order->user_name);
        $this->assertSame('eur', $order->currency);
        $this->assertSame('street 25', $order->address);
        $this->assertSame('123-45-67', $order->contacts);
        $this->assertTrue($order->outside);
        $this->assertEquals(78.97, $order->total_price);
        $this->assertEquals(1, $order->delivery_price);
        $this->assertSame('2020-01-03 11:11:11', $order->created_at);
        $user->refresh();
        $this->assertSame('tester@example.com', $user->email); // not changed
        $this->assertSame('John Tester', $user->name);
        $this->assertSame('eur', $user->currency);
        $this->assertSame('street 25', $user->address);
        $this->assertSame('123-45-67', $user->contacts);
        $this->assertSame('2020-01-03 10:10:10', $user->created_at->format('Y-m-d H:i:s'));
    }

    public function testSuccessWithRegister(): void
    {
        $this->migrate();
        Carbon::setTestNow('2020-01-03 11:11:11');
        /** @var User $user */
        $request = new CheckoutRequest($this->request);
        $result = ServiceContainer::orders()->checkout($request, null);
        $this->assertSame(200, $result->responseCode);
        $order = $result->order;
        $user = $result->user;
        $this->assertInstanceOf(Order::class, $order);
        $this->assertInstanceOf(User::class, $user);
        $this->assertSame([
            'order_number' => $order->number,
            'user' => $user->getDataForFrontend(),
        ], $result->responseData);
        $this->assertGreaterThan(1000, $order->number);
        $this->assertSame($user->id, $order->user_id);
        $this->assertTrue($result->isUserCreated());
        $this->assertSame('me@example.com', $result->createdPassword);
        $this->assertTrue($user->validatePassword('me@example.com'));
        $this->assertSame(Order::STATUS_CREATED, $order->status);
        $this->assertSame('me@example.com', $order->email);
        $this->assertSame('John Tester', $order->user_name);
        $this->assertSame('eur', $order->currency);
        $this->assertSame('street 25', $order->address);
        $this->assertSame('123-45-67', $order->contacts);
        $this->assertTrue($order->outside);
        $this->assertEquals(78.97, $order->total_price);
        $this->assertEquals(1, $order->delivery_price);
        $this->assertSame('2020-01-03 11:11:11', $order->created_at);
        $user->refresh();
        $this->assertSame('me@example.com', $user->email);
        $this->assertSame('John Tester', $user->name);
        $this->assertSame('eur', $user->currency);
        $this->assertSame('street 25', $user->address);
        $this->assertSame('123-45-67', $user->contacts);
        $this->assertSame('2020-01-03 11:11:11', $user->created_at->format('Y-m-d H:i:s'));
    }
}
