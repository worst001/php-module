<?php

namespace PhpGitHooks\Module\PhpUnit\Infrastructure\Tool;

use PhpGitHooks\Module\PhpUnit\Model\PhpUnitProcessorInterface;
use Symfony\Component\Process\Process;

class PhpUnitProcessor extends AbstractPhpUnitProcessor implements PhpUnitProcessorInterface
{
    /**
     * @param $options
     *
     * @return bool
     */
    public function process($options)
    {
        $tool = sprintf('php %s %s', $this->toolPathFinder->find('phpunit'), $options);

        $process = new Process($tool);

        return $this->runProcess($process);
    }
}
