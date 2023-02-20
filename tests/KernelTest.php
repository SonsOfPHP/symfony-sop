<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @coversDefaultClass \Kernel
 */
final class KernelTest extends TestCase
{
    public function appNameDataProvider()
    {
        // 1. value passed into constructor
        // 2. kernel.name
        // 3. kernel.root_namespace
        yield ['APP', 'app', 'App'];
        yield ['API', 'api', 'Api'];
        yield ['sons-of-php', 'sons_of_php', 'SonsOfPhp'];
    }

    /**
     * @covers ::__construct
     */
    public function testItThrowsExceptionForInvalidApp(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $kernel = new \Kernel('test', true, 'shared');
    }

    /**
     * @covers ::__construct
     * @covers ::getName
     *
     * @dataProvider appNameDataProvider
     */
    public function testGetName(string $name, string $expected): void
    {
        $kernel = new \Kernel('test', true, $name);

        $this->assertSame($expected, $kernel->getName());
    }

    /**
     * @covers ::getAppNameBundlesPath
     */
    public function testGetAppNameBundlesPath(): void
    {
        $kernel = new \Kernel('test', true, 'app');

        $this->assertStringEndsWith('etc/app/bundles.php', $kernel->getAppNameBundlesPath());
    }

    /**
     * @covers ::getAppConfigDir
     */
    public function testGetAppConfigDir(): void
    {
        $kernel = new \Kernel('test', true, 'app');

        $this->assertStringEndsWith('etc/app', $kernel->getAppConfigDir());
    }

    /**
     * @covers ::getCacheDir
     */
    public function testGetCacheDir(): void
    {
        $kernel = new \Kernel('test', true, 'app');

        $this->assertStringEndsWith('/var/cache/app/test', $kernel->getCacheDir());
    }

    /**
     * @covers ::getLogDir
     */
    public function testGetLogDir(): void
    {
        $kernel = new \Kernel('test', true, 'app');

        $this->assertStringEndsWith('/var/log/app', $kernel->getLogDir());
    }

    /**
     * @dataProvider containerClassProvider
     *
     * @covers ::getContainerClass
     */
    public function testContainerClass(string $environment, bool $debug, string $name, string $expected): void
    {
        $kernel = new \Kernel($environment, $debug, $name);
        $method = new \ReflectionMethod($kernel, 'getContainerClass');

        $this->assertSame($expected, $method->invoke($kernel));
    }

    public function containerClassProvider(): iterable
    {
        yield ['prod', true, 'app', 'AppKernelProdDebugContainer'];
        yield ['dev', true, 'app', 'AppKernelDevDebugContainer'];
        yield ['test', true, 'app', 'AppKernelTestDebugContainer'];
        yield ['prod', true, 'admin', 'AdminKernelProdDebugContainer'];
        yield ['dev', true, 'admin', 'AdminKernelDevDebugContainer'];
        yield ['test', true, 'admin', 'AdminKernelTestDebugContainer'];
    }

    /**
     * @covers ::getKernelParameters
     *
     * @dataProvider appNameDataProvider
     */
    public function testKernelParameters(string $appName, string $kernelName, string $kernelRootNamespace): void
    {
        $kernel = new \Kernel('test', true, $appName);
        $method = new \ReflectionMethod($kernel, 'getKernelParameters');

        $output = $method->invoke($kernel);
        $this->assertSame($kernelName, $output['kernel.name']);
        $this->assertSame($kernelRootNamespace, $output['kernel.root_namespace']);
    }

    /**
     * @covers ::__sleep()
     * @covers ::__wakeup()
     */
    public function testSerialize(): void
    {
        $kernel = new \Kernel('test', true, 'sons-of-php');

        $data = unserialize(serialize($kernel));

        $this->assertSame('test', $data->getEnvironment());
        $this->assertSame(true, $data->isDebug());
        $this->assertSame('sons_of_php', $data->getName());
    }
}
