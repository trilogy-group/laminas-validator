<?php // phpcs:disable SlevomatCodingStandard.Classes.UnusedPrivateElements.UnusedMethod

namespace LaminasTest\Validator;

use Laminas\ServiceManager\ServiceManager;
use Laminas\ServiceManager\Test\CommonPluginManagerTrait;
use Laminas\Validator\Exception\RuntimeException;
use Laminas\Validator\ValidatorInterface;
use Laminas\Validator\ValidatorPluginManager;
use PHPUnit\Framework\TestCase;
use ReflectionProperty;

use function method_exists;
use function strpos;

final class ValidatorPluginManagerCompatibilityTest extends TestCase
{
    use CommonPluginManagerTrait;

    protected function getPluginManager(): ValidatorPluginManager
    {
        return new ValidatorPluginManager(new ServiceManager());
    }

    protected function getV2InvalidPluginException(): string
    {
        return RuntimeException::class;
    }

    protected function getInstanceOf(): string
    {
        return ValidatorInterface::class;
    }

    /**
     * @psalm-return iterable<string, array{0: string, 1: string}>
     */
    public function aliasProvider(): iterable
    {
        $pluginManager     = $this->getPluginManager();
        $isV2PluginManager = method_exists($pluginManager, 'validatePlugin');

        $r = new ReflectionProperty($pluginManager, 'aliases');
        $r->setAccessible(true);
        $aliases = $r->getValue($pluginManager);

        foreach ($aliases as $alias => $target) {
            // Skipping due to required options
            if (strpos($target, '\\Barcode')) {
                continue;
            }

            // Skipping due to required options
            if (strpos($target, '\\Between')) {
                continue;
            }

            // Skipping on v2 releases of service manager
            if ($isV2PluginManager && strpos($target, '\\BusinessIdentifierCode')) {
                continue;
            }

            // Skipping due to required options
            if (strpos($target, '\\Db\\')) {
                continue;
            }

            // Skipping due to required options
            if (strpos($target, '\\File\\ExcludeExtension')) {
                continue;
            }

            // Skipping due to required options
            if (strpos($target, '\\File\\Extension')) {
                continue;
            }

            // Skipping due to required options
            if (strpos($target, '\\File\\FilesSize')) {
                continue;
            }

            // Skipping due to required options
            if (strpos($target, '\\Regex')) {
                continue;
            }

            yield $alias => [$alias, $target];
        }
    }

    /**
     * Provided only for compatibility with the lowest integration tests from Laminas\ServiceManager (v2)
     */
    private function setExpectedException(string $exceptionClassName): void
    {
        $this->expectException($exceptionClassName);
    }
}
