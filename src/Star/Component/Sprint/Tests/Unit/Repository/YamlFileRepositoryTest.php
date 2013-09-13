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

    public function testShouldKeepOldDataBetweenExecution()
    {
        $repository = new YamlFileRepository($this->root, $this->filename);
        $content = file_get_contents($this->fullPath);
        $this->assertEmpty($content);

        $object1 = $this->getMockEntity();
        $object1
            ->expects($this->once())
            ->method('toArray')
            ->will($this->returnValue(array('name' => 'value1')));
        $object1
            ->expects($this->once())
            ->method('getId')
            ->will($this->returnValue(1));
        $repository->add($object1);
        $repository->save();

        $repository = new YamlFileRepository($this->root, $this->filename);
        $content = file_get_contents($this->fullPath);
        $this->assertNotEmpty($content);

        $object2 = $this->getMockEntity();
        $object2
            ->expects($this->once())
            ->method('toArray')
            ->will($this->returnValue(array('name' => 'value2')));
        $object2
            ->expects($this->once())
            ->method('getId')
            ->will($this->returnValue(2));
        $repository->add($object2);
        $repository->save();

        $content = file_get_contents($this->fullPath);
        $this->assertContains('value1', $content);
        $this->assertContains('value2', $content);
    }
}
