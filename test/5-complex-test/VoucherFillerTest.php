<?php

use PHPUnit\Framework\TestCase;

use Prophecy\Argument;
use Symfony\Component\HttpFoundation\Request;

class VoucherFillerTest extends TestCase
{
    protected $voucherFiller;

    protected $uploaderMock;

    public function setUp()
    {
        $this->uploaderMock = $this->prophesize(PictureUploaderInterface::class);
        $this->voucherFiller = new VoucherFiller($this->uploaderMock->reveal());
    }

    public function testFillVoucher()
    {
        $expectedVoucher = (new Voucher())
            ->setTitle('title')
            ->setDescription('shortDescription')
            ->setLongDescription('')
            ->setType('always')
            ->setPicture('')
            ->setQuantity(1)
            ->setBegin(new \DateTimeImmutable('2018-02-02T09:42:14.974272+0000'))
            ->setEnd(new \DateTimeImmutable('2018-02-02T09:42:14.974272+0000'));

        $filledVoucher = null;

        $this->assertEquals($expectedVoucher, $filledVoucher);
    }

    public function testFillVoucherWithPicture()
    {
        $expectedVoucher = (new Voucher())
            ->setTitle('title')
            ->setDescription('shortDescription')
            ->setLongDescription('')
            ->setType('always')
            ->setPicture('/offers/1-5a743ab8453c7-foo.jpg')
            ->setQuantity(1)
            ->setBegin(new \DateTimeImmutable('2018-02-02T09:42:14.974272+0000'))
            ->setEnd(new \DateTimeImmutable('2018-02-02T09:42:14.974272+0000'));


        $filledVoucher = null;

        $this->assertEquals($expectedVoucher, $filledVoucher);
    }
}
