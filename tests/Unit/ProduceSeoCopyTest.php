<?php

namespace Tests\Unit;

use App\Support\ProduceSeoCopy;
use Tests\TestCase;

final class ProduceSeoCopyTest extends TestCase
{
    public function test_every_catalog_product_has_a_unique_bilingual_description(): void
    {
        /** @var array{fruits: list<array{0: string, 1: string}>, vegetables: list<array{0: string, 1: string}>} $catalog */
        $catalog = require dirname(__DIR__, 2).'/database/seeders/Catalog/produce_items.php';
        $copy = ProduceSeoCopy::all();

        $expected = [];
        foreach ([...$catalog['fruits'], ...$catalog['vegetables']] as $row) {
            $expected[$row[0]] = $row[1];
        }

        $this->assertSame(array_keys($expected), array_keys($copy));

        $english = [];
        $arabic = [];

        foreach ($expected as $enName => $arName) {
            $text = $copy[$enName];
            $this->assertArrayHasKey('en', $text);
            $this->assertArrayHasKey('ar', $text);

            $en = trim($text['en']);
            $ar = trim($text['ar']);

            $this->assertStringContainsString($enName, $en);
            $this->assertStringContainsString($arName, $ar);
            $this->assertStringContainsString('AL-DAWY', $en);
            $this->assertStringContainsString('الدوي', $ar);
            $this->assertStringNotContainsString('hand-selected', $en);

            $this->assertGreaterThanOrEqual(155, mb_strlen($en), $enName.' English copy is too short');
            $this->assertLessThanOrEqual(520, mb_strlen($en), $enName.' English copy is too long');
            $this->assertGreaterThanOrEqual(80, mb_strlen($ar), $enName.' Arabic copy is too short');
            $this->assertLessThanOrEqual(520, mb_strlen($ar), $enName.' Arabic copy is too long');

            $enSentence = strstr($en, '.', true);
            $arSentence = strstr($ar, '.', true);
            $this->assertNotFalse($enSentence, $enName.' English copy needs a first sentence');
            $this->assertNotFalse($arSentence, $enName.' Arabic copy needs a first sentence');
            $this->assertGreaterThanOrEqual(50, mb_strlen($enSentence), $enName.' English snippet is too short');
            $this->assertLessThanOrEqual(155, mb_strlen($enSentence), $enName.' English snippet is too long: '.$enSentence);
            $this->assertGreaterThanOrEqual(40, mb_strlen($arSentence), $enName.' Arabic snippet is too short');
            $this->assertLessThanOrEqual(155, mb_strlen($arSentence), $enName.' Arabic snippet is too long: '.$arSentence);

            $english[] = $en;
            $arabic[] = $ar;
        }

        $this->assertSame($english, array_values(array_unique($english)));
        $this->assertSame($arabic, array_values(array_unique($arabic)));
    }
}
