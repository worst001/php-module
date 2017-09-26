<?php

namespace PhpGitHooks\Module\Git\Tests\Infrastructure;

use PhpGitHooks\Module\Git\Infrastructure\OutputWriter\ToolTittleOutputWriter;
use PhpGitHooks\Module\Tests\Infrastructure\UnitTestCase\Mock;

trait ToolTitleOutputWriterTrait
{
    /**
     * @var ToolTittleOutputWriter
     */
    private $toolTitleOutputWriter;

    /**
     * @return \Mockery\MockInterface|ToolTittleOutputWriter
     */
    protected function getToolTitleOutputWriter()
    {
        return $this->toolTitleOutputWriter = $this->toolTitleOutputWriter ?: Mock::get(ToolTittleOutputWriter::class);
    }

    /**
     * @param string $title
     * @param string $return
     */
    protected function shouldWriteTitle($title, $return)
    {
        $this->getToolTitleOutputWriter()
            ->shouldReceive('writeTitle')
            ->once()
            ->with($title)
            ->andReturn($return);
    }
}
