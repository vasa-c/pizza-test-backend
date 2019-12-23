<?php

declare(strict_types=1);

namespace Tests\Unit\Services\Orders;

use Tests\TestCase;
use App\Services\Orders\CheckoutResult;
use App\{
    Order,
    User
};

class CheckoutResultTest extends TestCase
{
    public function testBuildResponse(): void
    {
        $this->migrate();
        $result = new CheckoutResult();
        // request invalid
        $result->buildResponse();
        $this->assertEquals([], $result->responseData);
        $this->assertSame(422, $result->responseCode);
        // other error
        $result->isRequestValid = true;
        $result->buildResponse();
        $this->assertEquals([], $result->responseData);
        $this->assertSame(500, $result->responseCode);
        // login required
        $result->isLoginRequired = true;
        $result->buildResponse();
        $this->assertEquals(['req_login' => true], $result->responseData);
        $this->assertSame(200, $result->responseCode);
        // success
        /** @var User $user */
        $user = factory(User::class)->create();
        /** @var Order $order */
        $order = factory(Order::class)->make();
        $order->setUser($user);
        $order->save();
        $result->isLoginRequired = false;
        $result->order = $order;
        $result->user = $user;
        $result->buildResponse();
        $this->assertEquals([
            'order_number' => $order->number,
            'user' => $user->getDataForFrontend(),
        ], $result->responseData);
        $this->assertSame(200, $result->responseCode);
        // user was created
        $result->createdPassword = 'xxx';
        $result->buildResponse();
        $expected = [
            'order_number' => $order->number,
            'user' => $user->getDataForFrontend(),
        ];
        $this->assertEquals($expected, $result->responseData);
        $this->assertSame(200, $result->responseCode);
    }
}
