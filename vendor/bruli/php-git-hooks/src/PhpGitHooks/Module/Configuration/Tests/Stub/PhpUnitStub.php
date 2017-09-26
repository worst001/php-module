<?php

namespace PhpGitHooks\Module\Configuration\Tests\Stub;

use PhpGitHooks\Module\Configuration\Domain\Enabled;
use PhpGitHooks\Module\Configuration\Domain\PhpUnit;
use PhpGitHooks\Module\Configuration\Domain\PhpUnitOptions;
use PhpGitHooks\Module\Configuration\Domain\PhpUnitRandomMode;
use PhpGitHooks\Module\Configuration\Domain\Undefined;
use PhpGitHooks\Module\Tests\Infrastructure\Stub\RandomStubInterface;

class PhpUnitStub implements RandomStubInterface
{
    /**
     * @param Undefined         $undefined
     * @param Enabled           $enabled
     * @param PhpUnitRandomMode $randomMode
     * @param PhpUnitOptions    $options
     *
     * @return PhpUnit
     */
    public static function create(
        Undefined $undefined,
        Enabled $enabled,
        PhpUnitRandomMode $randomMode,
        PhpUnitOptions $options
    ) {
        return new PhpUnit($undefined, $enabled, $randomMode, $options);
    }

    /**
     * @return PhpUnit
     */
    public static function random()
    {
        return self::create(
            new Undefined(false),
            EnabledStub::random(),
            PhpUnitRandomModeStub::random(),
            PhpUnitOptionsStub::random()
        );
    }

    /**
     * @param string $options
     *
     * @return PhpUnit
     */
    public static function createEnabled($options = '--testsuite default')
    {
        return self::create(
            new Undefined(false),
            EnabledStub::create(true),
            PhpUnitRandomModeStub::create(true),
            PhpUnitOptionsStub::create($options)
        );
    }

    /**
     * @return PhpUnit
     */
    public static function setUndefined()
    {
        return self::create(
            new Undefined(true),
            EnabledStub::create(false),
            PhpUnitRandomModeStub::create(false),
            PhpUnitOptionsStub::create(null)
        );
    }
}
