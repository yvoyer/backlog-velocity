<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Tests\Unit\Null;

use Star\Component\Sprint\Null\NullDialog;
use Star\Component\Sprint\Tests\Unit\UnitTestCase;

/**
 * Class NullDialogTest
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Tests\Unit\Null
 *
 * @covers Star\Component\Sprint\Null\NullDialog
 */
class NullDialogTest extends UnitTestCase
{
    /**
     * @var NullDialog
     */
    private $dialog;

    public function setUp()
    {
        $this->dialog = new NullDialog();
    }

    public function testShouldBeASymphonyDialogHelper()
    {
        $this->assertInstanceOf('Symfony\Component\Console\Helper\DialogHelper', $this->dialog);
    }

    public function testShouldReturnTheOverriddenName()
    {
        $this->assertSame('NullDialog', $this->dialog->getName());
    }

    /**
     * @dataProvider provideOverriddenMethodsData
     */
    public function testMethodShouldDoNothing($method)
    {
        $output = $this->getMock('Symfony\Component\Console\Output\OutputInterface');
        $this->assertNull($this->dialog->{$method}($output, 'question', 'mixed'));
    }

    public function provideOverriddenMethodsData()
    {
        return array(
            'Method select should do nothing' => array('select'),
            'Method ask should do nothing' => array('ask'),
            'Method askConfirmation should do nothing' => array('askConfirmation'),
            'Method askHiddenResponse should do nothing' => array('askHiddenResponse'),
            'Method askAndValidate should do nothing' => array('askAndValidate'),
            'Method askHiddenResponseAndValidate should do nothing' => array('askHiddenResponseAndValidate'),
        );
    }
}
