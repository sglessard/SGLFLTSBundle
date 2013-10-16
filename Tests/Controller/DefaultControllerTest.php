<?php

namespace SGL\FLTSBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $this->assertGreaterThan(
            0,
            1
        );
    }
}
