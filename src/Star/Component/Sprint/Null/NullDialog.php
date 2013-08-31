<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Null;

use Symfony\Component\Console\Helper\DialogHelper;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class NullDialog
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Null
 */
class NullDialog extends DialogHelper
{
    /**
     * {@inheritDoc}
     */
    public function select(OutputInterface $output, $question, $choices, $default = null, $attempts = false, $errorMessage = 'Value "%s" is invalid', $multiselect = false)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function ask(OutputInterface $output, $question, $default = null, array $autocomplete = null)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function askConfirmation(OutputInterface $output, $question, $default = true)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function askHiddenResponse(OutputInterface $output, $question, $fallback = true)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function askAndValidate(OutputInterface $output, $question, $validator, $attempts = false, $default = null, array $autocomplete = null)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function askHiddenResponseAndValidate(OutputInterface $output, $question, $validator, $attempts = false, $fallback = true)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'NullDialog';
    }

}
