<?php

namespace Nip\Records\Filters\Tests\Sessions\Traits;

use Nip\Records\Filters\Column\BasicFilter;
use Nip\Records\Filters\Sessions\Session;
use Nip\Records\Filters\Tests\AbstractTest;

/**
 * Class ArrayAccessTraitTest
 * @package Nip\Records\Filters\Tests\Sessions\Traits
 */
class ArrayAccessTraitTest extends AbstractTest
{
    public function test_offsetExists()
    {
        $session = new Session();
        $session->addFilter((new BasicFilter())->setField('t1'));
        $session->addFilter((new BasicFilter())->setField('t2'));

        self::assertTrue(isset($session['t1']));
        self::assertTrue(isset($session['t2']));
        self::assertFalse(isset($session['t3']));
    }
}