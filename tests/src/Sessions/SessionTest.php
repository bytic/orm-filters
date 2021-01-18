<?php

namespace Nip\Records\Filters\Tests\Sessions;

use Nip\Records\Filters\Column\BasicFilter;
use Nip\Records\Filters\Sessions\Session;
use Nip\Records\Filters\Tests\AbstractTest;

/**
 * Class SessionTest
 * @package Nip\Records\Filters\Tests\Sessions
 */
class SessionTest extends AbstractTest
{
    public function test_toArray()
    {
        $session = new Session();
        $session->addFilter((new BasicFilter())->setField('t1'));
        $session->addFilter((new BasicFilter())->setField('t2'));

        self::assertSame([], $session->toArray());
        self::assertSame([], (array) $session);
    }
}