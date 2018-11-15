<?php declare(strict_types=1);

/**
 * @author I. R. Vobmagturs <i+r+vobmagturs@commodea.com>
 */

use GraphQL\Server\StandardServer;
use Irvobmagturs\InvoiceCore\CommandHandler\CustomerHandler;
use Irvobmagturs\InvoiceCore\CommandHandler\InvoiceHandler;
use Irvobmagturs\InvoiceCore\Infrastructure\GraphQL\CqrsCommandHandlersResolver;
use Irvobmagturs\InvoiceCore\Infrastructure\GraphQL\CustomizedGraphqlServerConfig;
use Irvobmagturs\InvoiceCore\Infrastructure\GraphQL\EventBus;
use Irvobmagturs\InvoiceCore\Infrastructure\GraphQL\HoldsEventBus;
use Irvobmagturs\InvoiceCore\Infrastructure\GraphQL\SchemaFileCache;
use Irvobmagturs\InvoiceCore\Infrastructure\GraphQL\TypeResolver;
use Jubjubbird\Respects\DomainEvent;
use Jubjubbird\Respects\RecordedEvent;

require_once __DIR__ . '/vendor/autoload.php';
$schemaCache = __DIR__ . '/data/cache/schema';
$schemaFile = __DIR__ . '/cqrs.graphqls';
$context = new class implements HoldsEventBus
{
    private $eventBus = null;

    function getEventBus(): EventBus
    {
        $this->eventBus = $this->eventBus ?: new class implements EventBus
        {
            function handle(DomainEvent $event): void
            {
                if ($event instanceof RecordedEvent) {
                    // TODO
                } else {
                    // TODO
                }
            }
        };
        return $this->eventBus;
    }
};
$rootValue = null;
$serverConfig = null;
$typeResolver = new TypeResolver();
$typeResolver->addResolverForField('CqrsQuery', 'loadFoo', function () {
    return 'bar';
});
$typeResolver = new CqrsCommandHandlersResolver($typeResolver);
$typeResolver = new InvoiceHandler($typeResolver);
$typeResolver = new CustomerHandler($typeResolver);
try {
    $schemaFileCache = new SchemaFileCache($schemaCache);
    $schema = $schemaFileCache->loadCacheForFile($schemaFile, $typeResolver->generateTypeConfigDecorator());
    $serverConfig = new CustomizedGraphqlServerConfig($schema, $context, $rootValue);
    $standardServer = new StandardServer($serverConfig);
    $standardServer->handleRequest();
} catch (Throwable $e) {
    StandardServer::send500Error(
        $serverConfig
            ? new Exception(json_encode($serverConfig->formatError($e), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES))
            : $e,
        true
    );
}
