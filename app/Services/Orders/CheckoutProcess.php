<?php

declare(strict_types=1);

namespace App\Services\Orders;

use App\{
    Order,
    User,
    ServiceContainer
};
use App\Http\Requests\CheckoutRequest;
use Carbon\Carbon;

class CheckoutProcess
{
    /**
     * @param CheckoutRequest $request
     * @param User|null $user
     */
    public function __construct(CheckoutRequest $request, ?User $user)
    {
        $this->request = $request;
        $this->result = new CheckoutResult();
        $this->result->user = $user;
    }

    /**
     * @return CheckoutResult
     */
    public function process(): CheckoutResult
    {
        $result = $this->result;
        $this->buildBlankOrder();
        if (!$this->loadItems()) {
            $result->isRequestValid = false;
            return $result;
        }
        $result->isRequestValid = true;
        if ($this->isLoginRequired()) {
            $this->result->isLoginRequired = true;
            return $result;
        }
        $this->processUser();
        $this->createOrder();
        return $result;
    }

    /**
     * @return bool
     */
    private function loadItems(): bool
    {
        $items = ServiceContainer::pizza()->parseCart($this->request->pizza);
        if ($items === null) {
            return false;
        }
        $this->order->setItems($items);
        return true;
    }

    /**
     * @return bool
     */
    private function isLoginRequired(): bool
    {
        if ($this->result->user !== null) {
            return false;
        }
        return (ServiceContainer::users()->getByEmail($this->request->email) !== null);
    }

    private function processUser(): void
    {
        if ($this->result->user === null) {
            $this->createUser();
        }
        $this->fillUser();
        $this->result->user->save();
        $this->result->user->refresh();
    }

    private function createUser(): void
    {
        $user = new User();
        $user->email = $this->request->email;
        $user->is_admin = false;
        $this->result->createdPassword = $user->generatePassword();
        $this->result->user = $user;
    }

    private function fillUser(): void
    {
        $request = $this->request;
        $user = $this->result->user;
        foreach (['name', 'currency', 'address', 'contacts'] as $k) {
            $user->$k = $request->$k;
        }
    }

    private function buildBlankOrder(): void
    {
        $order = new Order();
        $request = $this->request;
        foreach (['email', 'address', 'contacts', 'outside', 'currency'] as $k) {
            $order->$k = $request->$k;
        }
        $order->user_name = $request->name;
        $order->status = Order::STATUS_CREATED;
        $order->created_at = Carbon::now()->format('Y-m-d H:i:s');
        $this->order = $order;
    }

    private function createOrder(): void
    {
        $order = $this->order;
        $order->setUser($this->result->user);
        $order->calculatePrices();
        $order->save();
        $order->saveItems();
        $order->refresh();
        $this->result->order = $this->order;
    }

    /**
     * @var CheckoutResult
     */
    private $result;

    /**
     * @var CheckoutRequest
     */
    private $request;

    /**
     * @var Order
     */
    private $order;
}
