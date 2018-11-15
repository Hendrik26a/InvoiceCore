<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 05.11.18
 * Time: 09:24
 */

namespace Irvobmagturs\InvoiceCore\Model\Event;

use Irvobmagturs\InvoiceCore\Model\ValueObject\Address;
use Jubjubbird\Respects\Serializable;

class CustomerAddressWasChanged implements Serializable
{
    /**
     * @var string
     */
    private $customerAddress;

    /**
     * CustomerAddressWasChanged constructor.
     * @param Address $customerAddress
     */
    public function __construct(Address $customerAddress)
    {
        $this->customerAddress = $customerAddress;
    }

    /**
     * @return string
     */
    public function getCustomerAddress(): Address
    {
        return $this->customerAddress;
    }

    /**
     * @return array
     */
    function serialize(): array
    {
        return [$this->customerAddress->serialize()];
    }

    /**
     * @param array $data
     * @return Serializable
     */
    static function deserialize(array $data): Serializable
    {
        return new self(Address::deserialize($data[0]));
    }
}