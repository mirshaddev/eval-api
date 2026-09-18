<?php

namespace Tests;

use Faker\Factory;
use Faker\Generator;
use PHPUnit\Framework\TestCase;
use Zenstruck\Foundry\Test\Factories;

class UnitTestCase extends TestCase
{
    use Factories;

    protected static Generator $fake;

    protected function setUp(): void
    {
        parent::setUp();
        self::$fake = Factory::create();
    }
}
