<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Unit\Repository;

use Star\Component\Sprint\Repository\YamlFileRepository;
use Star\Component\Sprint\Tests\Unit\UnitTestCase;

/**
 * Class YamlFileRepositoryTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Tests\Unit\Repository
 *
 * @covers Star\Component\Sprint\Repository\YamlFileRepository
 */
class YamlFileRepositoryTest extends UnitTestCase
{
    /**
     * @var string
     */
    private $root;

    /**
     * @var string
     */
    private $filename;

    /**
     * @var string
     */
    private $fullPath;

    public function setUp()
    {
        $this->root     = sys_get_temp_dir() . DIRECTORY_SEPARATOR  . 'data';
        $this->filename = uniqid('yamlFile');
        $this->fullPath = $this->root . DIRECTORY_SEPARATOR . $this->filename . '.yml';
    }

    public function tearDown()
    {
        if (file_exists($this->root)) {
            unlink($this->fullPath);
            rmdir($this->root);
        }
    }

    public function testShouldCreateTheFolderAndFileWhenItDoNotExists()
    {
        $this->assertFalse(file_exists($this->fullPath), 'The file should not exists at first');
        new YamlFileRepository($this->root, $this->filename);
        $this->assertTrue(file_exists($this->fullPath), 'The file should have been created');
    }

    public function testShouldContainsEntities()
    {
        $repository = new YamlFileRepository($this->root, $this->filename);
        $this->assertEmpty($repository->findAll());

        $entity = $this->getMockEntity();

        $repository->add($entity);
        $this->assertEmpty($repository->findAll(), 'The entity was not saved');

        $repository->save();
        $this->assertCount(1, $repository->findAll(), 'The entity should have been saved');
    }

    public function testShouldReturnNullWhenEntityNotFound()
    {
        $repository = new YamlFileRepository($this->getFixturesFolder(), 'teams');
        $this->assertNull($repository->find(999));
    }

    public function testShouldReturnASpecificEntity()
    {
        $repository = new YamlFileRepository($this->getFixturesFolder(), 'teams');

        $entity = $repository->find(2);
        $this->assertNotNull($entity);
        $this->assertSame(2, $entity['id']);
        $this->assertSame('The Rebel Alliance', $entity['name']);
    }
}
