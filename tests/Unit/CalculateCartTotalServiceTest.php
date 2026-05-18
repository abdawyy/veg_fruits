<?php

namespace Tests\Unit;

use App\DTO\CartLineDto;
use App\Services\Cart\CalculateCartTotalService;
use App\Services\Money\DecimalMath;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CalculateCartTotalServiceTest extends TestCase
{
    #[Test]
    public function it_matches_spec_complex_sum_without_float_drift(): void
    {
        $service = new CalculateCartTotalService;

        $lines = [
            new CartLineDto('20', '18'),
            new CartLineDto('9', '130'),
            new CartLineDto('37.25', '322'),
            new CartLineDto('30', '25'),
        ];

        $result = $service->execute($lines, '0');

        $expected = DecimalMath::add(
            DecimalMath::add(
                DecimalMath::add(
                    DecimalMath::mul('20', '18'),
                    DecimalMath::mul('9', '130'),
                ),
                DecimalMath::mul('37.25', '322'),
            ),
            DecimalMath::mul('30', '25'),
        );

        $this->assertSame($expected, $result->linesSubtotal);
        $this->assertSame($expected, $result->grandTotal);
        $this->assertSame('0.0000', $result->orderPackagingFee);
    }

    #[Test]
    public function it_adds_packaging_fee_and_per_line_service_surcharges(): void
    {
        $service = new CalculateCartTotalService;

        $lines = [
            new CartLineDto(unitPrice: '10', quantity: '2', servicesSurchargeTotal: '1.5'),
            new CartLineDto(unitPrice: '5', quantity: '4', servicesSurchargeTotal: '0.25'),
        ];

        $result = $service->execute($lines, '3');

        $line0 = DecimalMath::add(DecimalMath::mul('10', '2'), '1.5');
        $line1 = DecimalMath::add(DecimalMath::mul('5', '4'), '0.25');
        $sub = DecimalMath::add($line0, $line1);

        $this->assertSame($line0, $result->lineTotals[0]);
        $this->assertSame($line1, $result->lineTotals[1]);
        $this->assertSame($sub, $result->linesSubtotal);
        $this->assertSame('3.0000', $result->orderPackagingFee);
        $this->assertSame(DecimalMath::add($sub, '3'), $result->grandTotal);
    }
}
