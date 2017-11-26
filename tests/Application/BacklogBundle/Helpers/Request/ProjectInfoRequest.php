<?php declare(strict_types=1);

namespace Star\Component\Sprint\Application\BacklogBundle\Helpers\Request;

use Star\Component\Sprint\Application\BacklogBundle\Helpers\TestRequest;
use Star\Component\Sprint\Domain\Model\Identity\ProjectId;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Component\DomCrawler\Crawler;

final class ProjectInfoRequest implements TestRequest
{
    /**
     * @var ProjectId
     */
    private $projectId;

    /**
     * @param ProjectId $projectId
     */
    public function __construct(ProjectId $projectId)
    {
        $this->projectId = $projectId;
    }

    /**
     * @param Client $client
     *
     * @return Crawler
     */
    public function request(Client $client)
    {
        return $client->request('', '/project/' . $this->projectId->toString());
    }
}
