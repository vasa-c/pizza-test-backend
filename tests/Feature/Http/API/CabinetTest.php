<?php

declare(strict_types=1);

namespace Tests\Feature\Http\API;

use Tests\TestCase;
use App\{
    User,
    Order
};
use Carbon\Carbon;

class CabinetTest extends TestCase
{
    public function testCabinet(): void
    {
        $this->migrate();
        Carbon::setTestNow('2012-01-01 00:00:00');
        /** @var User $user1 */
        $user1 = factory(User::class)->create();
        /** @var User $user2 */
        $user2 = factory(User::class)->create();
        /** @var Order $order1 */
        $order1 = factory(Order::class)->make();
        $order1->setUser($user1);
        $order1->total_price = 11;
        $order1->save();
        /** @var Order $order2 */
        $order2 = factory(Order::class)->make();
        $order2->setUser($user2);
        $order2->total_price = 12;
        $order2->save();
        /** @var Order $order3 */
        $order3 = factory(Order::class)->make();
        $order3->setUser($user1);
        $order3->total_price = 13;
        $order3->save();
        Carbon::setTestNow('2012-01-01 00:00:01');
        $order3->toSuccess();
        $order3->save();

        // guest - forbidden
        $this->getJson('/api/cabinet')->assertStatus(403);

        $this->be($user1);
        $response = $this->getJson('/api/cabinet');
        $response->assertStatus(200);
        $data = $response->json();
        $this->assertArrayHasKey('orders', $data);
        $this->assertEquals([
            [
                'number' => $order3->number,
                'status' => 'success',
                'total_price' => 13,
                'currency' => 'eur',
                'created_at' => '2012-01-01 00:00:00',
                'finalized_at' => '2012-01-01 00:00:01',
            ],
            [
                'number' => $order1->number,
                'status' => 'created',
                'total_price' => 11,
                'currency' => 'eur',
                'created_at' => '2012-01-01 00:00:00',
                'finalized_at' => null,
            ],
        ], $data['orders']);

        $this->be($user2);
        $response = $this->getJson('/api/cabinet');
        $response->assertStatus(200);
        $data = $response->json();
        $this->assertArrayHasKey('orders', $data);
        $this->assertEquals([
            [
                'number' => $order2->number,
                'status' => 'created',
                'total_price' => 12,
                'currency' => 'eur',
                'created_at' => '2012-01-01 00:00:00',
                'finalized_at' => null,
            ],
        ], $data['orders']);
    }
}
