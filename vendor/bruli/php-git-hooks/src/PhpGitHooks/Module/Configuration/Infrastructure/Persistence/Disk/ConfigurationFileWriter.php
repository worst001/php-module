<?php

namespace PhpGitHooks\Module\Configuration\Infrastructure\Persistence\Disk;

use PhpGitHooks\Module\Configuration\Model\ConfigurationFileWriterInterface;
use Symfony\Component\Yaml\Yaml;

class ConfigurationFileWriter implements ConfigurationFileWriterInterface
{
    /**
     * @param array $data
     */
    public function write(array $data)
    {
        $yaml = Yaml::dump($data, 5);

        file_put_contents('php-git-hooks.yml', $yaml);
    }
}
