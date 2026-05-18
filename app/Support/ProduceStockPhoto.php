<?php

namespace App\Support;

/**
 * Curated stock photos (Unsplash — free to use per Unsplash license; attribute in footer).
 * Overrides keep hero items visually on-theme; pool gives every SKU a stable image.
 */
final class ProduceStockPhoto
{
    /** @var list<string> */
    private const POOL = [
        'https://images.unsplash.com/photo-1610832958506-aa56368176cf?auto=format&fit=crop&w=800&q=85',
        'https://images.unsplash.com/photo-1542838132-92c53300491e?auto=format&fit=crop&w=800&q=85',
        'https://images.unsplash.com/photo-1547514708-6f15cec3e005?auto=format&fit=crop&w=800&q=85',
        'https://images.unsplash.com/photo-1464226184884-fa280b87c399?auto=format&fit=crop&w=800&q=85',
        'https://images.unsplash.com/photo-1592924357228-91a4daadcfea?auto=format&fit=crop&w=800&q=85',
        'https://images.unsplash.com/photo-1587735243475-66f406351fcd?auto=format&fit=crop&w=800&q=85',
        'https://images.unsplash.com/photo-1592419044706-39796d40fccc?auto=format&fit=crop&w=800&q=85',
        'https://images.unsplash.com/photo-1566385101042-1a0aa0c1268c?auto=format&fit=crop&w=800&q=85',
        'https://images.unsplash.com/photo-1598170845058-32b9d6a5da37?auto=format&fit=crop&w=800&q=85',
        'https://images.unsplash.com/photo-1576045057995-568f588f82fb?auto=format&fit=crop&w=800&q=85',
        'https://images.unsplash.com/photo-1563565375-f3fdfdbefa83?auto=format&fit=crop&w=800&q=85',
        'https://images.unsplash.com/photo-1592924357228-91a4daadcfea?auto=format&fit=crop&w=800&q=85',
        'https://images.unsplash.com/photo-1518977676601-b53f82aba655?auto=format&fit=crop&w=800&q=85',
        'https://images.unsplash.com/photo-1595475207225-3b46c9c4c7e0?auto=format&fit=crop&w=800&q=85',
        'https://images.unsplash.com/photo-1586201375768-838b0e7d8409?auto=format&fit=crop&w=800&q=85',
        'https://images.unsplash.com/photo-1592841200221-a6898f307baa?auto=format&fit=crop&w=800&q=85',
        'https://images.unsplash.com/photo-1584270354949-c26b0d5b4a0c?auto=format&fit=crop&w=800&q=85',
        'https://images.unsplash.com/photo-1566385101042-1a0aa0c1268c?auto=format&fit=crop&w=800&q=85',
        'https://images.unsplash.com/photo-1604977042234-1fb7b9a39e84?auto=format&fit=crop&w=800&q=85',
        'https://images.unsplash.com/photo-1592417813508-3f16b1ead1cc?auto=format&fit=crop&w=800&q=85',
        'https://images.unsplash.com/photo-1590165482129-1aefb4e4a4f8?auto=format&fit=crop&w=800&q=85',
        'https://images.unsplash.com/photo-1601004890684-d8cbf643f5f2?auto=format&fit=crop&w=800&q=85',
        'https://images.unsplash.com/photo-1592924357228-91a4daadcfea?auto=format&fit=crop&w=800&q=85',
        'https://images.unsplash.com/photo-1587049352846-4a222e70d878?auto=format&fit=crop&w=800&q=85',
        'https://images.unsplash.com/photo-1590779038400-5052d050b6d3?auto=format&fit=crop&w=800&q=85',
        'https://images.unsplash.com/photo-1593113598334-c2882885f298?auto=format&fit=crop&w=800&q=85',
        'https://images.unsplash.com/photo-1587735243615-2d6d8a8b0e8a?auto=format&fit=crop&w=800&q=85',
        'https://images.unsplash.com/photo-1599599810769-bcde5a160d17?auto=format&fit=crop&w=800&q=85',
        'https://images.unsplash.com/photo-1604977042234-1fb7b9a39e84?auto=format&fit=crop&w=800&q=85',
        'https://images.unsplash.com/photo-1598170845058-32b9d6a5da37?auto=format&fit=crop&w=800&q=85',
        'https://images.unsplash.com/photo-1595855709687-aabed89a6e2b?auto=format&fit=crop&w=800&q=85',
        'https://images.unsplash.com/photo-1594282486552-05dee4de219e?auto=format&fit=crop&w=800&q=85',
        'https://images.unsplash.com/photo-1592419044706-39796d40fccc?auto=format&fit=crop&w=800&q=85',
        'https://images.unsplash.com/photo-1595475207225-3b46c9c4c7e0?auto=format&fit=crop&w=800&q=85',
        'https://images.unsplash.com/photo-1587049352846-4a222e70d878?auto=format&fit=crop&w=800&q=85',
        'https://images.unsplash.com/photo-1598170845058-32b9d6a5da37?auto=format&fit=crop&w=800&q=85',
        'https://images.unsplash.com/photo-1615485290382-441e4d049cb5?auto=format&fit=crop&w=800&q=85',
        'https://images.unsplash.com/photo-1592417813508-3f16b1ead1cc?auto=format&fit=crop&w=800&q=85',
        'https://images.unsplash.com/photo-1590165482129-1aefb4e4a4f8?auto=format&fit=crop&w=800&q=85',
        'https://images.unsplash.com/photo-1592924357228-91a4daadcfea?auto=format&fit=crop&w=800&q=85',
        'https://images.unsplash.com/photo-1566385101042-1a0aa0c1268c?auto=format&fit=crop&w=800&q=85',
        'https://images.unsplash.com/photo-1592924357228-91a4daadcfea?auto=format&fit=crop&w=800&q=85',
        'https://images.unsplash.com/photo-1587735243475-66f406351fcd?auto=format&fit=crop&w=800&q=85',
        'https://images.unsplash.com/photo-1592419044706-39796d40fccc?auto=format&fit=crop&w=800&q=85',
        'https://images.unsplash.com/photo-1547514708-6f15cec3e005?auto=format&fit=crop&w=800&q=85',
        'https://images.unsplash.com/photo-1610832958506-aa56368176cf?auto=format&fit=crop&w=800&q=85',
    ];

    /** @var array<string, string> */
    private const OVERRIDES = [
        'Apple' => 'https://images.unsplash.com/photo-1560806887-1e4cd0b6cbd6?auto=format&fit=crop&w=800&q=85',
        'Red apple' => 'https://images.unsplash.com/photo-1568702846914-96b305d2aaeb?auto=format&fit=crop&w=800&q=85',
        'Green apple' => 'https://images.unsplash.com/photo-1619546813926-a78fa6372cd2?auto=format&fit=crop&w=800&q=85',
        'Gala apple' => 'https://images.unsplash.com/photo-1560806887-1e4cd0b6cbd6?auto=format&fit=crop&w=800&q=85',
        'Banana' => 'https://images.unsplash.com/photo-1571771894821-ce9b6c11b08e?auto=format&fit=crop&w=800&q=85',
        'Orange' => 'https://images.unsplash.com/photo-1547514708-6f15cec3e005?auto=format&fit=crop&w=800&q=85',
        'Mango' => 'https://images.unsplash.com/photo-1605027990121-c42e40f43593?auto=format&fit=crop&w=800&q=85',
        'Watermelon' => 'https://images.unsplash.com/photo-1587049352846-4a222e70d878?auto=format&fit=crop&w=800&q=85',
        'Pomegranate' => 'https://images.unsplash.com/photo-1541344996734-89a4b4f560b3?auto=format&fit=crop&w=800&q=85',
        'Date (Medjool)' => 'https://images.unsplash.com/photo-1599599810769-bcde5a160d17?auto=format&fit=crop&w=800&q=85',
        'Strawberry' => 'https://images.unsplash.com/photo-1464965901868-44a824576e10?auto=format&fit=crop&w=800&q=85',
        'Grapes (green)' => 'https://images.unsplash.com/photo-1599599810769-bcde5a160d17?auto=format&fit=crop&w=800&q=85',
        'Grapes (red)' => 'https://images.unsplash.com/photo-1596363505729-4190a9506133?auto=format&fit=crop&w=800&q=85',
        'Tomato' => 'https://images.unsplash.com/photo-1592841200221-a6898f307baa?auto=format&fit=crop&w=800&q=85',
        'Cherry tomatoes' => 'https://images.unsplash.com/photo-1546094096-0df4bcaaa337?auto=format&fit=crop&w=800&q=85',
        'Cucumber' => 'https://images.unsplash.com/photo-1449300079323-02e209d9d3a6?auto=format&fit=crop&w=800&q=85',
        'Potato' => 'https://images.unsplash.com/photo-1518977676601-b53f82aba655?auto=format&fit=crop&w=800&q=85',
        'Carrot' => 'https://images.unsplash.com/photo-1598170845058-32b9d6a5da37?auto=format&fit=crop&w=800&q=85',
        'Eggplant' => 'https://images.unsplash.com/photo-1622206151226-18ca2c9ab4a1?auto=format&fit=crop&w=800&q=85',
        'Bell pepper (red)' => 'https://images.unsplash.com/photo-1563565375-f3fdfdbefa83?auto=format&fit=crop&w=800&q=85',
        'Onion (red)' => 'https://images.unsplash.com/photo-1518977676601-b53f82aba655?auto=format&fit=crop&w=800&q=85',
        'Garlic' => 'https://images.unsplash.com/photo-1540148426945-6ac22f9ca8b2?auto=format&fit=crop&w=800&q=85',
        'Lemon' => 'https://images.unsplash.com/photo-1590502593741-1f3b3d2e6b6e?auto=format&fit=crop&w=800&q=85',
        'Pineapple' => 'https://images.unsplash.com/photo-1550258987-190a2d41a8ba?auto=format&fit=crop&w=800&q=85',
        'Avocado' => 'https://images.unsplash.com/photo-1523049673857-eb18f1d7b578?auto=format&fit=crop&w=800&q=85',
        'Broccoli' => 'https://images.unsplash.com/photo-1584270354949-c26b0d5b4a0c?auto=format&fit=crop&w=800&q=85',
        'Lettuce (romaine)' => 'https://images.unsplash.com/photo-1622206151226-18ca2c9ab4a1?auto=format&fit=crop&w=800&q=85',
        'Okra' => 'https://images.unsplash.com/photo-1594282486552-05dee4de219e?auto=format&fit=crop&w=800&q=85',
    ];

    public static function urlFor(string $englishName, string $sku): string
    {
        if (isset(self::OVERRIDES[$englishName])) {
            return self::OVERRIDES[$englishName];
        }

        $pool = self::POOL;
        $idx = (int) (crc32($sku) % max(1, count($pool)));

        return $pool[$idx];
    }
}
