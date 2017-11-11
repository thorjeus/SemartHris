<?php

namespace Tests\KejawenLab\Application\SemartHris\Component\Service;

use KejawenLab\Application\SemartHris\Component\Address\Model\Addressable;
use KejawenLab\Application\SemartHris\Component\Address\Model\AddressInterface;
use KejawenLab\Application\SemartHris\Component\Address\Repository\AddressRepositoryFactory;
use KejawenLab\Application\SemartHris\Component\Address\Repository\AddressRepositoryInterface;
use KejawenLab\Application\SemartHris\Component\Address\Service\DefaultAddressChecker;
use PHPUnit\Framework\TestCase;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.com>
 */
class DefaultAddressCheckerTest extends TestCase
{
    public function testUnsetDefaultAddressExceptWithDefaultAddressTrue()
    {
        $address = $this->getMockBuilder(AddressInterface::class)->getMock();

        $addressable = $this->getMockBuilder(Addressable::class)->getMock();
        $addressable->expects($this->once())->method('setAddress')->with($address);

        $address->expects($this->once())->method('isDefaultAddress')->willReturn(true);
        $address->expects($this->once())->method('getAddressable')->willReturn($addressable);

        $repository = $this->getMockBuilder(AddressRepositoryInterface::class)->getMock();
        $repository->expects($this->once())->method('unsetDefaultExcept')->with($address);
        $repository->expects($this->once())->method('apply')->with($addressable);

        $addressFactory = $this->getMockBuilder(AddressRepositoryFactory::class)->getMock();
        $addressFactory->expects($this->once())->method('getRepositoryFor')->with(get_class($address))->willReturn($repository);

        $defaultAddressChecker = new DefaultAddressChecker($addressFactory);
        $defaultAddressChecker->unsetDefaultExcept($address);
    }

    public function testUnsetDefaultAddressExceptWithDefaultAddressFalse()
    {
        $addressable = $this->getMockBuilder(Addressable::class)->getMock();
        $addressable->expects($this->never())->method('setAddress');

        $address = $this->getMockBuilder(AddressInterface::class)->getMock();
        $address->expects($this->once())->method('isDefaultAddress')->willReturn(false);
        $address->expects($this->never())->method('getAddressable');

        $repository = $this->getMockBuilder(AddressRepositoryInterface::class)->getMock();
        $repository->expects($this->never())->method('unsetDefaultExcept');
        $repository->expects($this->never())->method('apply');

        $addressFactory = $this->getMockBuilder(AddressRepositoryFactory::class)->getMock();
        $addressFactory->expects($this->never())->method('getRepositoryFor');

        $defaultAddressChecker = new DefaultAddressChecker($addressFactory);
        $defaultAddressChecker->unsetDefaultExcept($address);
    }

    public function testSetRandomDefault()
    {
        $address = $this->getMockBuilder(AddressInterface::class)->getMock();

        $addressable = $this->getMockBuilder(Addressable::class)->getMock();
        $addressable->expects($this->once())->method('setAddress')->with($address);

        $address->expects($this->once())->method('getAddressable')->willReturn($addressable);

        $repository = $this->getMockBuilder(AddressRepositoryInterface::class)->getMock();
        $repository->expects($this->once())->method('setRandomDefault')->willReturn($address);
        $repository->expects($this->once())->method('apply')->with($addressable);

        $addressFactory = $this->getMockBuilder(AddressRepositoryFactory::class)->getMock();
        $addressFactory->expects($this->once())->method('getRepositoryFor')->with(get_class($address))->willReturn($repository);

        $defaultAddressChecker = new DefaultAddressChecker($addressFactory);
        $defaultAddressChecker->setRandomDefault($address);
    }
}
