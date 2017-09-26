<?php

namespace PhpGitHooks\Module\Files\Contract\Query;

use Bruli\EventBusBundle\QueryBus\QueryInterface;

class PhpFilesExtractor implements QueryInterface
{
    /**
     * @var array
     */
    private $files;

    /**
     * PhpFilesExtractorQuery constructor.
     *
     * @param array $files
     */
    public function __construct(array $files)
    {
        $this->files = $files;
    }

    /**
     * @return array
     */
    public function getFiles()
    {
        return $this->files;
    }
}
