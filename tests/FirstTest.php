<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;

class FirstTest extends TestCase
{
    public function testAddition(): void
    {
        $this->assertSame(4, 2 + 2);
    }
}