<?php

namespace PhpGitHooks\Module\Configuration\Service;

use PhpGitHooks\Module\Configuration\Domain\Execute;
use PhpGitHooks\Module\Configuration\Model\ExecuteInterface;

class PreCommitExecuteFactory
{
    /**
     * @param array $data
     *
     * @return ExecuteInterface
     */
    public static function fromArray(array $data)
    {
        $tools = [
            isset($data['composer']) ? ComposerFactory::fromArray($data['composer']) : ComposerFactory::setUndefined(),
            isset($data['jsonlint']) ? JsonLintFactory::fromArray($data['jsonlint']) : JsonLintFactory::setUndefined(),
            isset($data['phplint']) ? PhpLintFactory::fromArray($data['phplint']) : PhpLintFactory::setUndefined(),
            isset($data['phpmd']) ? PhpMdFactory::fromArray($data['phpmd']) : PhpMdFactory::setUndefined(),
            isset($data['phpcs']) ? PhpCsFactory::fromArray($data['phpcs']) : PhpCsFactory::setUndefined(),
            isset($data['php-cs-fixer']) ? PhpCsFixerFactory::fromArray($data['php-cs-fixer']) :
                PhpCsFixerFactory::setUndefined(),
            isset($data['phpunit']) ? PhpUnitFactory::fromArray($data['phpunit']) : PhpUnitFactory::setUndefined(),
            isset($data['phpunit']['strict-coverage']) ? PhpUnitStrictCoverageFactory::fromArray(
                $data['phpunit']['strict-coverage']
            ) : PhpUnitStrictCoverageFactory::setUndefined(),
            isset($data['phpunit']['guard-coverage']) ? PhpUnitGuardCoverageFactory::build(
                $data['phpunit']['guard-coverage']
            ) : PhpUnitGuardCoverageFactory::setUndefined(),
        ];

        return new Execute($tools);
    }

    /**
     * @return Execute
     */
    public static function setUndefined()
    {
        $tools = [
            ComposerFactory::setUndefined(),
            JsonLintFactory::setUndefined(),
            PhpLintFactory::setUndefined(),
            PhpMdFactory::setUndefined(),
            PhpCsFactory::setUndefined(),
            PhpCsFixerFactory::setUndefined(),
            PhpUnitFactory::setUndefined(),
            PhpUnitStrictCoverageFactory::setUndefined(),
            PhpUnitGuardCoverageFactory::setUndefined(),
        ];

        return new Execute($tools);
    }
}
