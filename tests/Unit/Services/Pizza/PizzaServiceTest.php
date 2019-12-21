<?php

declare(strict_types=1);

namespace Tests\Unit;

use Tests\TestCase;
use App\ServiceContainer;

class PizzaServiceTest extends TestCase
{
    public function testGetBySlug(): void
    {
        $this->migrate();
        $pizza = ServiceContainer::pizza()->getBySlug('chicago');
        $this->assertNotNull($pizza);
        $this->assertSame('Chicago', $pizza->name);
        $this->assertNull(ServiceContainer::pizza()->getBySlug('xxx'));
    }
}
