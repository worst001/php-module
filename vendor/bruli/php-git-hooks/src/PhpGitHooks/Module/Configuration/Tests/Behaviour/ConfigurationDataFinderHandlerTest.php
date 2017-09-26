<?php

namespace PhpGitHooks\Module\Configuration\Tests\Behaviour;

use PhpGitHooks\Module\Configuration\Contract\Query\ConfigurationDataFinder;
use PhpGitHooks\Module\Configuration\Contract\Query\ConfigurationDataFinderHandler;
use PhpGitHooks\Module\Configuration\Contract\Response\ConfigurationDataResponse;
use PhpGitHooks\Module\Configuration\Tests\Infrastructure\ConfigurationUnitTestCase;
use PhpGitHooks\Module\Configuration\Tests\Stub\CommitMsgStub;
use PhpGitHooks\Module\Configuration\Tests\Stub\ConfigStub;
use PhpGitHooks\Module\Configuration\Tests\Stub\PreCommitStub;
use PhpGitHooks\Module\Configuration\Tests\Stub\PrePushStub;

class ConfigurationDataFinderHandlerTest extends ConfigurationUnitTestCase
{
    /**
     * @var ConfigurationDataFinderHandler
     */
    private $configurationDataFinderQueryHandler;

    protected function setUp()
    {
        $this->configurationDataFinderQueryHandler = new ConfigurationDataFinderHandler(
            $this->getConfigurationFileReader()
        );
    }

    /**
     * @test
     */
    public function itShouldReturnEnabledTools()
    {
        $this->shouldReadConfigurationData(
            ConfigStub::create(
                PreCommitStub::createAllEnabled(),
                CommitMsgStub::createEnabled(),
                PrePushStub::createAllEnabled()
            )
        );

        /** @var ConfigurationDataResponse $data */
        $data = $this->configurationDataFinderQueryHandler->handle(new ConfigurationDataFinder());

        $this->assertTrue($data->getPreCommit()->isPreCommit());
        $this->assertNotNull($data->getPreCommit()->getRightMessage());
        $this->assertNotNull($data->getPreCommit()->getErrorMessage());
        $this->assertTrue($data->getPreCommit()->isComposer());
        $this->assertTrue($data->getPreCommit()->isJsonLint());
        $this->assertTrue($data->getPreCommit()->isPhpLint());
        $this->assertTrue($data->getPreCommit()->getPhpMd()->isPhpMd());
        $this->assertTrue($data->getPreCommit()->getPhpCs()->isPhpCs());
        $this->assertNotNull($data->getPreCommit()->getPhpCs()->getPhpCsStandard());
        $this->assertTrue($data->getPreCommit()->getPhpCsFixer()->isPhpCsFixer());
        $this->assertTrue($data->getPreCommit()->getPhpCsFixer()->isPhpCsFixerPsr0());
        $this->assertTrue($data->getPreCommit()->getPhpCsFixer()->isPhpCsFixerPsr1());
        $this->assertTrue($data->getPreCommit()->getPhpCsFixer()->isPhpCsFixerPsr2());
        $this->assertTrue($data->getPreCommit()->getPhpCsFixer()->isPhpCsFixerSymfony());
        $this->assertTrue($data->getPreCommit()->getPhpUnit()->isPhpunit());
        $this->assertTrue($data->getPreCommit()->getPhpUnit()->isPhpunitRandomMode());
        $this->assertTrue($data->getPreCommit()->getPhpUnitGuardCoverage()->isEnabled());
        $this->assertNotNull($data->getPreCommit()->getPhpUnitGuardCoverage()->getWarningMessage());
        $this->assertNotNull($data->getPreCommit()->getPhpUnit()->getPhpunitOptions());
        $this->assertTrue($data->getCommitMsg()->isCommitMsg());
        $this->assertNotNull($data->getPrePush()->getPhpUnitGuardCoverage()->getWarningMessage());
    }
}
