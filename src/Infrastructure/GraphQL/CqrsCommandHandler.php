<?php declare(strict_types=1);
/**
 * @author I. R. Vobmagturs <i+r+vobmagturs@commodea.com>
 */

namespace Irvobmagturs\InvoiceCore\Infrastructure\GraphQL;

use GraphQL\Type\Definition\ResolveInfo;
use function Verraes\ClassFunctions\short;

/**
 * The class name of a subclass matches one of the result types defined in CqrsCommandHandlers from the GraphQL schema
 * and provides a method for every field of that type:
 *
 *     public function ...(string $aggregateId, array $args, $context, ResolveInfo $info): void
 *
 * $args is an associative array containing the arguments specified in the GraphQL mutation.
 */
abstract class CqrsCommandHandler extends TypeResolver
{
    /**
     * {@inheritDoc}
     */
    public function __construct(?parent $base = null)
    {
        parent::__construct($base);
        foreach (get_class_methods($this) as $method) {
            $this->addResolverForField(
                short($this),
                $method,
                function ($typeValue, array $args, $context, ResolveInfo $info) use ($method) {
                    $this->$method($typeValue, $args, $context, $info);
                    return true;
                }
            );
        }
    }
}
