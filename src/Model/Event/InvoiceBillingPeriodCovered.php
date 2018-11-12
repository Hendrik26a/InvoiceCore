<?php
/**
 * Created by PhpStorm.
 * User: hendr
 * Date: 12.11.2018
 * Time: 00:22
 */

namespace Irvobmagturs\InvoiceCore\Model\Event;


use Irvobmagturs\InvoiceCore\Infrastructure\Serializable;
use Irvobmagturs\InvoiceCore\Model\ValueObject\BillingPeriod;

class InvoiceBillingPeriodCovered implements Serializable
{

    /**
     * @var
     */
    private $period;

    /**
     * InvoiceBillingPeriodCovered constructor.
     * @param BillingPeriod $period
     */
    public function __construct(BillingPeriod $period)
    {
        $this->period = $period;;
    }

    function serialize(): array
    {
        return [$this->period->serialize()];
    }

    /**
     * @param array $data
     * @return InvoiceBillingPeriodCovered
     * @throws \Exception
     */
    public static function deserialize(array $data): self
    {
        return new self(BillingPeriod::deserialize($data[0]));
    }
}