<?php declare(strict_types=1);
/**
 * @author I. R. Vobmagturs <i+r+vobmagturs@commodea.com>
 */

namespace spec\Irvobmagturs\InvoiceCore\Model\Entity;

use Irvobmagturs\InvoiceCore\Model\Entity\Customer;
use Irvobmagturs\InvoiceCore\Model\Id\CustomerId;
use Irvobmagturs\InvoiceCore\Model\ValueObject\Address;
use Jubjubbird\Respects\AggregateHistory;
use Jubjubbird\Respects\AggregateRoot;
use PhpSpec\ObjectBehavior;

class CustomerSpec extends ObjectBehavior
{
    function it_can_engage_in_business()
    {
        $this->shouldBeAnInstanceOf(Customer::class);
        $this->getRecordedEvents()->shouldHaveCount(1);
    }

    function it_is_an_aggregate_root()
    {
        $this->shouldImplement(AggregateRoot::class);
    }

    function it_reconstitutes_from_aggregate_history(AggregateHistory $aggregateHistory)
    {
        $customerId = CustomerId::fromString('efe80649-220e-45ab-909f-bf57e146270f');
        $aggregateHistory->getAggregateId()->willReturn($customerId);
        $this->beConstructedThroughReconstituteFrom($aggregateHistory);
        $this->shouldBeAnInstanceOf(Customer::class);
        $this->getAggregateId()->shouldBeLike($customerId);
    }


    function let(Address $address)
    {
        $customerId = CustomerId::fromString('efe80649-220e-45ab-909f-bf57e146270f');
        $this->beConstructedThroughEngageInBusiness($customerId, 'name', $address);
    }
}
