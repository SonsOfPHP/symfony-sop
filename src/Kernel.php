<?php

declare(strict_types=1);

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

use function Symfony\Component\String\u;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    private string $name;

    /**
     * {@inheritdoc}
     */
    public function __construct(string $environment, bool $debug, string $name = 'app')
    {
        $this->name = $name;

        if ('shared' === $this->getName()) {
            throw new \InvalidArgumentException(sprintf('App Name "%s" is invalid.', $name));
        }

        parent::__construct($environment, $debug);
    }

    /**
     * Returns the App Name.
     */
    public function getName(): string
    {
        return u($this->name)->snake()->toString();
    }

    public function getAppNameBundlesPath(): string
    {
        return $this->getAppConfigDir().'/bundles.php';
    }

    public function getAppConfigDir(): string
    {
        return $this->getProjectDir().'/etc/'.$this->getName();
    }

    /**
     * {@inheritdoc}
     */
    public function getCacheDir(): string
    {
        return $this->getProjectDir().'/var/cache/'.$this->getName().'/'.$this->environment;
    }

    /**
     * {@inheritdoc}
     */
    public function getLogDir(): string
    {
        return $this->getProjectDir().'/var/log/'.$this->getName();
    }

    /**
     * {@inheritDoc}
     */
    public function registerBundles(): iterable
    {
        $sharedBundles = require $this->getBundlesPath();
        $appNameBundles = require $this->getAppNameBundlesPath();

        $contents = array_merge($sharedBundles, $appNameBundles);

        foreach ($contents as $class => $envs) {
            if ($envs[$this->environment] ?? $envs['all'] ?? false) {
                /** @psalm-suppress UndefinedClass */
                yield new $class();
            }
        }
    }

    /**
     * {@inheritDoc}
     */
    protected function getContainerClass(): string
    {
        return u($this->getName())->camel()->title()->toString().parent::getContainerClass();
    }

    /**
     * {@inheritdoc}
     */
    protected function build(ContainerBuilder $container)
    {
        $container->fileExists($this->getBundlesPath());
        $container->fileExists($this->getAppNameBundlesPath());
    }

    /**
     * {@inheritdoc}
     */
    protected function getKernelParameters(): array
    {
        return array_merge([
            'kernel.name' => $this->getName(),
            'kernel.root_namespace' => u($this->getName())->camel()->title()->toString(),
        ], parent::getKernelParameters());
    }

    /**
     * {@inheritdoc}
     */
    protected function configureContainer(ContainerConfigurator $container, LoaderInterface $loader, ContainerBuilder $builder): void
    {
        foreach ([$this->getConfigDir(), $this->getAppConfigDir()] as $configDir) {
            $container->import($configDir.'/{packages}/*.{php,yaml}');
            $container->import($configDir.'/{packages}/'.$this->environment.'/*.{php,yaml}');
            if (is_file($configDir.'/services.yaml')) {
                $container->import($configDir.'/services.yaml');
                $container->import($configDir.'/{services}_'.$this->environment.'.yaml');
            } else {
                $container->import($configDir.'/{services}.php');
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function configureRoutes(RoutingConfigurator $routes): void
    {
        foreach ([$this->getConfigDir(), $this->getAppConfigDir()] as $configDir) {
            $routes->import($configDir.'/{routes}/'.$this->environment.'/*.{php,yaml}');
            $routes->import($configDir.'/{routes}/*.{php,yaml}');

            if (is_file($configDir.'/routes.yaml')) {
                $routes->import($configDir.'/routes.yaml');
            } else {
                $routes->import($configDir.'/{routes}.php');
            }

            if (false !== ($fileName = (new \ReflectionObject($this))->getFileName())) {
                $routes->import($fileName, 'annotation');
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function __sleep(): array
    {
        return ['environment', 'debug', 'name'];
    }

    /**
     * {@inheritdoc}
     */
    public function __wakeup(): void
    {
        if (is_object($this->environment) || is_object($this->debug) || is_object($this->name)) {
            throw new \BadMethodCallException('Cannot unserialize '.__CLASS__);
        }

        $this->__construct($this->environment, $this->debug, $this->name);
    }
}
