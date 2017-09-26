<?php

namespace PhpGitHooks\Module\PhpUnit\Infrastructure\Tool;

use PhpGitHooks\Infrastructure\Tool\ToolPathFinder;
use PhpGitHooks\Module\PhpUnit\Model\StrictCoverageProcessorInterface;
use Symfony\Component\Process\Process;

class StrictCoverageProcessor implements StrictCoverageProcessorInterface
{
    /**
     * @var ToolPathFinder
     */
    private $toolPathFinder;

    /**
     * StrictCoverageProcessor constructor.
     *
     * @param ToolPathFinder $toolPathFinder
     */
    public function __construct(ToolPathFinder $toolPathFinder)
    {
        $this->toolPathFinder = $toolPathFinder;
    }

    /**
     * @return float
     */
    public function process()
    {
        $tool = $this->toolPathFinder->find('phpunit');
        $command = 'php '.$tool.' --coverage-text|grep Classes|cut -d " " -f 4|cut -d "%" -f 1';

        $process = new Process($command);
        $process->run();

        return (float) $process->getOutput();
    }
}
