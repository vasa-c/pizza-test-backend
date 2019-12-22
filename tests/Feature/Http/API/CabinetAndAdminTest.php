<?php

declare(strict_types=1);

namespace Tests\Feature\Http\API;

use Tests\TestCase;
use App\{
    ServiceContainer,
    User,
    Order
};
use Carbon\Carbon;

class CabinetAndAdminTest extends TestCase
{
    public function testCabinet(): void
    {
        $this->migrate();
        Carbon::setTestNow('2020-01-01 00:00:00');
        $admin = ServiceContainer::users()->getByEmail('admin@pizza.loc');
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
        Carbon::setTestNow('2020-01-01 00:00:01');
        $order3->toSuccess();
        $order3->save();

        // guest - forbidden
        $this->getJson('/api/cabinet')->assertStatus(403);
        $this->getJson('/api/admin')->assertStatus(403);

        $this->be($user1);
        $response = $this->getJson('/api/cabinet');
        $response->assertStatus(200);
        $data = $response->json();
        $this->assertArrayHasKey('orders', $data);
        $equals3 = [
            'number' => $order3->number,
            'status' => 'success',
            'total_price' => 1300,
            'currency' => 'eur',
            'created_at' => '2020-01-01 00:00:00',
            'finalized_at' => '2020-01-01 00:00:01',
        ];
        $equals1 = [
            'number' => $order1->number,
            'status' => 'created',
            'total_price' => 1100,
            'currency' => 'eur',
            'created_at' => '2020-01-01 00:00:00',
            'finalized_at' => null,
        ];
        $equals2 = [
            'number' => $order2->number,
            'status' => 'created',
            'total_price' => 1200,
            'currency' => 'eur',
            'created_at' => '2020-01-01 00:00:00',
            'finalized_at' => null,
        ];
        $this->assertEquals([
            $equals3,
            $equals1,
        ], $data['orders']);

        $this->be($user2);
        $response = $this->getJson('/api/cabinet');
        $response->assertStatus(200);
        $data = $response->json();
        $this->assertArrayHasKey('orders', $data);
        $this->assertEquals([
            $equals2,
        ], $data['orders']);

        // admin
        $this->get('/api/admin')->assertStatus(403); // for user 2
        $this->be($admin);
        $response = $this->get('/api/admin');
        $response->assertStatus(200);
        $this->assertEquals([
            'orders' => [
                $equals3,
                $equals2,
                $equals1,
            ],
        ], $response->json());
    }

    public function testOrderPage(): void
    {
        $this->migrate();
        $admin = ServiceContainer::users()->getByEmail('admin@pizza.loc');
        /** @var User $user1 */
        $user1 = factory(User::class)->create();
        /** @var User $user2 */
        $user2 = factory(User::class)->create();
        $order = new Order();
        $order->setUser($user1);
        $order->number = 55555;
        $order->email = 'x@x.x';
        $order->user_name = 'John';
        $order->address = 'street';
        $order->contacts = '123-45';
        $order->outside = true;
        $order->currency = 'usd';
        $order->status = Order::STATUS_DELIVERY;
        $order->created_at = '2020-01-01 00:00:00';

        $items = ServiceContainer::pizza()->parseCart([
            'chicago' => 2,
            'greek' => 1,
            'detroit' => 3,
        ]);
        $order->setItems($items);
        $order->calculatePrices();
        $order->save();
        $order->saveItems();

        $this->get('/api/cabinet/55555')->assertStatus(403);

        // Not my order
        $this->be($user2);
        $this->get('/api/cabinet/55555')->assertStatus(404); // not 403 against brute

        // my order
        $this->be($user1);
        $response = $this->get('/api/cabinet/55555');
        $response->assertStatus(200);
        $expected = [
            'order' => [
                'number' => $order->number,
                'status' => 'delivery',
                'user_name' => 'John',
                'email' => 'x@x.x',
                'address' => 'street',
                'contacts' => '123-45',
                'outside' => true,
                'currency' => 'usd',
                'delivery_price' => 0,
                'total_price' => 14660,
                'created_at' => '2020-01-01 00:00:00',
                'finalized_at' => null,
                'items' => [
                    [
                        'slug' => 'chicago',
                        'name' => 'Chicago',
                        'count' => 2,
                    ],
                    [
                        'slug' => 'greek',
                        'name' => 'Greek',
                        'count' => 1,
                    ],
                    [
                        'slug' => 'detroit',
                        'name' => 'Detroit',
                        'count' => 3,
                    ],
                ],
            ],
        ];
        $this->assertEquals($expected, $response->json());

        $this->get('/api/cabinet/77777')->assertStatus(404);

        // admin
        $this->get('/api/admin/55555')->assertStatus(403); // for user1
        $this->get('/api/admin/555-55')->assertStatus(404); // invalid url
        $this->be($admin);
        $response = $this->get('/api/admin/55555');
        $response->assertStatus(200);
        $this->assertEquals($expected, $response->json());
        $this->get('/api/admin/77777')->assertStatus(404);
    }
}
