<?php

declare(strict_types=1);

namespace Star\Component\Sprint\Application\BacklogBundle;

use Star\Component\Sprint\Application\BacklogBundle\Helpers\ResponseHelper;
use Star\Component\Sprint\Application\BacklogBundle\Helpers\TestRequest;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class BacklogWebTestCase extends WebTestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function tearDown()
    {
        parent::tearDown();
    }

    /**
     * @param TestRequest $request
     *
     * @return ResponseHelper
     */
    protected function request(TestRequest $request)
    {
        $client = self::createClient();

        return new ResponseHelper($client, $request->request($client));
    }
}
