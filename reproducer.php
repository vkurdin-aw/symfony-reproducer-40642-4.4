<?php
use Symfony\Bridge\ProxyManager\LazyProxy\PhpDumper\ProxyDumper;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Dumper\PhpDumper;
include_once __DIR__. '/vendor/autoload.php';
$container = new ContainerBuilder();
$container->setParameter('container.dumper.inline_class_loader', true);
$container
    ->register(\Acme\MyClass::class, \Acme\MyClass::class)
    ->setShared(false)
    ->setLazy(true)
    ->setPublic(true);
$container->compile();
$dumper = new PhpDumper($container);
$dumper->setProxyDumper(new ProxyDumper());
$dump = $dumper->dump([
    'as_files' => true,
    'file' => 'cache/appAppKernelDevDebugContainer.php',
]);
$generatedFileKey = \array_values(\array_filter(\array_keys($dump), fn ($file) => false !== \stripos($file, 'getMyClassService.php')))[0];
\print_r($generatedFileKey);
\print_r($dump[$generatedFileKey]);
