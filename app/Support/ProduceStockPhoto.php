<?php

namespace App\Support;

/**
 * Curated stock photos mapped by English product name, with a verified fallback pool.
 * Primary overrides use Wikimedia Commons (stable) or verified Unsplash URLs.
 */
final class ProduceStockPhoto
{
    /** @var list<string> Verified working Unsplash produce / market photos. */
    private const POOL = [
        'https://images.unsplash.com/photo-1610832958506-aa56368176cf?auto=format&fit=crop&w=800&q=85',
        'https://images.unsplash.com/photo-1542838132-92c53300491e?auto=format&fit=crop&w=800&q=85',
        'https://images.unsplash.com/photo-1464226184884-fa280b87c399?auto=format&fit=crop&w=800&q=85',
        'https://images.unsplash.com/photo-1592924357228-91a4daadcfea?auto=format&fit=crop&w=800&q=85',
        'https://images.unsplash.com/photo-1566385101042-1a0aa0c1268c?auto=format&fit=crop&w=800&q=85',
        'https://images.unsplash.com/photo-1598170845058-32b9d6a5da37?auto=format&fit=crop&w=800&q=85',
        'https://images.unsplash.com/photo-1576045057995-568f588f82fb?auto=format&fit=crop&w=800&q=85',
        'https://images.unsplash.com/photo-1563565375-f3fdfdbefa83?auto=format&fit=crop&w=800&q=85',
        'https://images.unsplash.com/photo-1518977676601-b53f82aba655?auto=format&fit=crop&w=800&q=85',
        'https://images.unsplash.com/photo-1592841200221-a6898f307baa?auto=format&fit=crop&w=800&q=85',
        'https://images.unsplash.com/photo-1584270354949-c26b0d5b4a0c?auto=format&fit=crop&w=800&q=85',
        'https://images.unsplash.com/photo-1601004890684-d8cbf643f5f2?auto=format&fit=crop&w=800&q=85',
        'https://images.unsplash.com/photo-1615485290382-441e4d049cb5?auto=format&fit=crop&w=800&q=85',
        'https://images.unsplash.com/photo-1705928629040-c701a1e70531?auto=format&fit=crop&w=800&q=85',
        'https://images.unsplash.com/photo-1722187715333-6597d7ac576e?auto=format&fit=crop&w=800&q=85',
        'https://images.unsplash.com/photo-1716558836636-cf1a81f798ca?auto=format&fit=crop&w=800&q=85',
    ];

    /** @var array<string, string> English product name => image URL */
    private const OVERRIDES = [
        'Apple' => 'https://images.unsplash.com/photo-1560806887-1e4cd0b6cbd6?auto=format&fit=crop&w=800&q=85',
        'Red apple' => 'https://images.unsplash.com/photo-1568702846914-96b305d2aaeb?auto=format&fit=crop&w=800&q=85',
        'Green apple' => 'https://images.unsplash.com/photo-1619546813926-a78fa6372cd2?auto=format&fit=crop&w=800&q=85',
        'Gala apple' => 'https://images.unsplash.com/photo-1560806887-1e4cd0b6cbd6?auto=format&fit=crop&w=800&q=85',
        'Apricot' => 'https://upload.wikimedia.org/wikipedia/commons/2/2a/Apricot_and_cross_section.jpg',
        'Avocado' => 'https://images.unsplash.com/photo-1523049673857-eb18f1d7b578?auto=format&fit=crop&w=800&q=85',
        'Banana' => 'https://images.unsplash.com/photo-1571771894821-ce9b6c11b08e?auto=format&fit=crop&w=800&q=85',
        'Blackberry' => 'https://upload.wikimedia.org/wikipedia/commons/7/78/Ripe%2C_juicy_blackberries_on_the_bush.jpg',
        'Blueberry' => 'https://upload.wikimedia.org/wikipedia/commons/1/15/Blueberries.jpg',
        'Cantaloupe' => 'https://upload.wikimedia.org/wikipedia/commons/4/4f/Cantaloupe_and_cross_section.jpg',
        'Cherry' => 'https://upload.wikimedia.org/wikipedia/commons/b/bb/Cherry_Stella444.jpg',
        'Coconut' => 'https://upload.wikimedia.org/wikipedia/commons/3/36/Cocos_nucifera_-_K%C3%B6hler%E2%80%93s_Medizinal-Pflanzen-197.jpg',
        'Date (Medjool)' => 'https://upload.wikimedia.org/wikipedia/commons/9/92/Dates_on_branch.jpg',
        'Dragon fruit' => 'https://upload.wikimedia.org/wikipedia/commons/7/79/HylocereusUndatus.jpg',
        'Fig' => 'https://upload.wikimedia.org/wikipedia/commons/4/4c/Ficus_carica_003.JPG',
        'Grapefruit' => 'https://upload.wikimedia.org/wikipedia/commons/8/8a/Citrus_paradisi_%28Grapefruit%29.jpg',
        'Grapes (green)' => 'https://upload.wikimedia.org/wikipedia/commons/1/1e/Red_grapes.jpg',
        'Grapes (red)' => 'https://images.unsplash.com/photo-1596363505729-4190a9506133?auto=format&fit=crop&w=800&q=85',
        'Guava' => 'https://upload.wikimedia.org/wikipedia/commons/8/8e/Psidium_guajava_fruit.jpg',
        'Honeydew melon' => 'https://upload.wikimedia.org/wikipedia/commons/5/5f/HoneydewMelon.jpg',
        'Kiwi' => 'https://upload.wikimedia.org/wikipedia/commons/b/b8/Kiwi_aka.jpg',
        'Lemon' => 'https://upload.wikimedia.org/wikipedia/commons/3/36/Lemon-Whole-Split.jpg',
        'Lime' => 'https://upload.wikimedia.org/wikipedia/commons/8/8a/Limes.jpg',
        'Lychee' => 'https://upload.wikimedia.org/wikipedia/commons/4/4c/Litchi_chinensis_fruits.jpg',
        'Mango' => 'https://upload.wikimedia.org/wikipedia/commons/9/90/Hapus_Mango.jpg',
        'Nectarine' => 'https://upload.wikimedia.org/wikipedia/commons/4/48/Nectarine_fruit.jpg',
        'Orange' => 'https://upload.wikimedia.org/wikipedia/commons/c/c4/Orange-Fruit-Pieces.jpg',
        'Papaya' => 'https://upload.wikimedia.org/wikipedia/commons/2/27/Homemade_papaya.jpg',
        'Passion fruit' => 'https://upload.wikimedia.org/wikipedia/commons/e/e2/Passionfruit_and_cross_section.jpg',
        'Peach' => 'https://upload.wikimedia.org/wikipedia/commons/9/9f/Peach_%28Prunus_persica%29_B.jpg',
        'Pear' => 'https://upload.wikimedia.org/wikipedia/commons/c/cf/Pears.jpg',
        'Persimmon' => 'https://upload.wikimedia.org/wikipedia/commons/d/d4/Persimmon_fruit.jpg',
        'Pineapple' => 'https://images.unsplash.com/photo-1550258987-190a2d41a8ba?auto=format&fit=crop&w=800&q=85',
        'Plum' => 'https://upload.wikimedia.org/wikipedia/commons/e/e7/Plums_MS.jpg',
        'Pomegranate' => 'https://upload.wikimedia.org/wikipedia/commons/9/9b/Granada_espana2.jpg',
        'Quince' => 'https://upload.wikimedia.org/wikipedia/commons/4/4f/Cydonia_oblonga_001.JPG',
        'Raspberry' => 'https://upload.wikimedia.org/wikipedia/commons/2/2b/Raspberries_%28Rubus_idaeus%29.jpg',
        'Strawberry' => 'https://upload.wikimedia.org/wikipedia/commons/2/29/PerfectStrawberry.jpg',
        'Tangerine' => 'https://upload.wikimedia.org/wikipedia/commons/c/c4/Orange-Fruit-Pieces.jpg',
        'Watermelon' => 'https://upload.wikimedia.org/wikipedia/commons/4/4e/Watermelon_cross_section.jpg',
        'Williams pear' => 'https://upload.wikimedia.org/wikipedia/commons/c/cf/Pears.jpg',
        'Seedless grapes' => 'https://upload.wikimedia.org/wikipedia/commons/1/1e/Red_grapes.jpg',
        'Melon (Galia)' => 'https://upload.wikimedia.org/wikipedia/commons/5/5f/HoneydewMelon.jpg',
        'Artichoke' => 'https://upload.wikimedia.org/wikipedia/commons/8/8f/Artichoke_J1.jpg',
        'Asparagus' => 'https://upload.wikimedia.org/wikipedia/commons/9/91/Asparagus_officinalis.jpg',
        'Baby spinach' => 'https://upload.wikimedia.org/wikipedia/commons/3/3a/Spinach_leaves.jpg',
        'Beetroot' => 'https://upload.wikimedia.org/wikipedia/commons/7/7e/Beetroot_jm26647.jpg',
        'Bell pepper (green)' => 'https://upload.wikimedia.org/wikipedia/commons/8/85/Green-Bell-Pepper.jpg',
        'Bell pepper (red)' => 'https://images.unsplash.com/photo-1563565375-f3fdfdbefa83?auto=format&fit=crop&w=800&q=85',
        'Bell pepper (yellow)' => 'https://upload.wikimedia.org/wikipedia/commons/8/85/Green-Bell-Pepper.jpg',
        'Broccoli' => 'https://images.unsplash.com/photo-1584270354949-c26b0d5b4a0c?auto=format&fit=crop&w=800&q=85',
        'Brussels sprouts' => 'https://upload.wikimedia.org/wikipedia/commons/0/08/Brussels_sprout_closeup.jpg',
        'Butternut squash' => 'https://upload.wikimedia.org/wikipedia/commons/9/99/Butternut_squash.jpg',
        'Cabbage (green)' => 'https://upload.wikimedia.org/wikipedia/commons/4/4e/Savoy_cabbage.jpg',
        'Cabbage (red)' => 'https://upload.wikimedia.org/wikipedia/commons/2/2f/Red_cabbage.jpg',
        'Carrot' => 'https://images.unsplash.com/photo-1598170845058-32b9d6a5da37?auto=format&fit=crop&w=800&q=85',
        'Cauliflower' => 'https://upload.wikimedia.org/wikipedia/commons/7/78/Cauliflower.jpg',
        'Celery' => 'https://upload.wikimedia.org/wikipedia/commons/0/0d/Celery_1.jpg',
        'Chard' => 'https://upload.wikimedia.org/wikipedia/commons/0/08/Swiss_Chard.jpg',
        'Chili pepper (hot)' => 'https://upload.wikimedia.org/wikipedia/commons/6/64/Bhut_Jolokia_pepper.jpg',
        'Chives' => 'https://upload.wikimedia.org/wikipedia/commons/4/49/Allium_schoenoprasum_-_Hapu_oblikas.jpg',
        'Cilantro (coriander)' => 'https://upload.wikimedia.org/wikipedia/commons/5/5a/Coriander_leaves.jpg',
        'Corn (sweet)' => 'https://upload.wikimedia.org/wikipedia/commons/5/5d/Corn.jpg',
        'Cucumber' => 'https://images.unsplash.com/photo-1449300079323-02e209d9d3a6?auto=format&fit=crop&w=800&q=85',
        'Curly kale' => 'https://upload.wikimedia.org/wikipedia/commons/2/2e/Kale-Bundle.jpg',
        'Dill' => 'https://upload.wikimedia.org/wikipedia/commons/4/4b/Anethum_graveolens.jpg',
        'Eggplant' => 'https://images.unsplash.com/photo-1622206151226-18ca2c9ab4a1?auto=format&fit=crop&w=800&q=85',
        'Fennel' => 'https://upload.wikimedia.org/wikipedia/commons/8/8e/Foeniculum_vulgare_001.JPG',
        'Garlic' => 'https://upload.wikimedia.org/wikipedia/commons/3/3d/Garlic_bulbs_and_cloves.jpg',
        'Ginger' => 'https://upload.wikimedia.org/wikipedia/commons/1/18/Ginger_Root.jpg',
        'Green beans' => 'https://upload.wikimedia.org/wikipedia/commons/1/11/Green_beans.jpg',
        'Iceberg lettuce' => 'https://upload.wikimedia.org/wikipedia/commons/5/5b/Romaine_lettuce.jpg',
        'Kale' => 'https://upload.wikimedia.org/wikipedia/commons/2/2e/Kale-Bundle.jpg',
        'Leek' => 'https://upload.wikimedia.org/wikipedia/commons/5/57/Leek.jpg',
        'Lettuce (romaine)' => 'https://upload.wikimedia.org/wikipedia/commons/5/5b/Romaine_lettuce.jpg',
        'Mint' => 'https://upload.wikimedia.org/wikipedia/commons/7/7b/Mentha_spicata_002.JPG',
        'Mushroom (white)' => 'https://upload.wikimedia.org/wikipedia/commons/4/4e/Agaricus_bisporus.jpg',
        'Okra' => 'https://upload.wikimedia.org/wikipedia/commons/9/96/Abelmoschus_esculentus_in_Malaysia.jpg',
        'Onion (red)' => 'https://images.unsplash.com/photo-1518977676601-b53f82aba655?auto=format&fit=crop&w=800&q=85',
        'Onion (white)' => 'https://upload.wikimedia.org/wikipedia/commons/2/25/Onion_on_White.JPG',
        'Onion (yellow)' => 'https://upload.wikimedia.org/wikipedia/commons/2/25/Onion_on_White.JPG',
        'Parsley' => 'https://upload.wikimedia.org/wikipedia/commons/0/07/Petroselinum_crispum_001.JPG',
        'Peas (green)' => 'https://upload.wikimedia.org/wikipedia/commons/5/51/Green_peas_in_pod.jpg',
        'Potato' => 'https://images.unsplash.com/photo-1518977676601-b53f82aba655?auto=format&fit=crop&w=800&q=85',
        'Pumpkin' => 'https://upload.wikimedia.org/wikipedia/commons/9/9d/Pumpkin_patch_in_Virginia.jpg',
        'Radish' => 'https://upload.wikimedia.org/wikipedia/commons/7/7a/Radish_IGP.jpg',
        'Red chili' => 'https://upload.wikimedia.org/wikipedia/commons/6/64/Bhut_Jolokia_pepper.jpg',
        'Rocket (arugula)' => 'https://upload.wikimedia.org/wikipedia/commons/7/79/Eruca_sativa_1_IP0204081.jpg',
        'Romaine hearts' => 'https://upload.wikimedia.org/wikipedia/commons/5/5b/Romaine_lettuce.jpg',
        'Scallions (spring onion)' => 'https://upload.wikimedia.org/wikipedia/commons/5/57/Leek.jpg',
        'Shallot' => 'https://upload.wikimedia.org/wikipedia/commons/8/8a/Shallots_-_whole_and_split.jpg',
        'Spinach' => 'https://upload.wikimedia.org/wikipedia/commons/3/3a/Spinach_leaves.jpg',
        'Spring mix salad' => 'https://upload.wikimedia.org/wikipedia/commons/4/4b/Mesclun.jpg',
        'Sweet corn cob' => 'https://upload.wikimedia.org/wikipedia/commons/5/5d/Corn.jpg',
        'Sweet potato' => 'https://upload.wikimedia.org/wikipedia/commons/5/58/Ipomoea_batatas_002.JPG',
        'Tomato' => 'https://images.unsplash.com/photo-1592841200221-a6898f307baa?auto=format&fit=crop&w=800&q=85',
        'Cherry tomatoes' => 'https://images.unsplash.com/photo-1546094096-0df4bcaaa337?auto=format&fit=crop&w=800&q=85',
        'Turnip' => 'https://upload.wikimedia.org/wikipedia/commons/4/4f/Turnip_2622027.jpg',
        'Zucchini' => 'https://upload.wikimedia.org/wikipedia/commons/a/a5/Courgette_%28Zucchini%29.jpg',
        'Basil' => 'https://upload.wikimedia.org/wikipedia/commons/5/5a/Basil-Basilico-Ocimum_basilicum-albahaca.jpg',
        'Bean sprouts' => 'https://upload.wikimedia.org/wikipedia/commons/6/6c/Mung_bean_sprouts.jpg',
        'Bok choy' => 'https://upload.wikimedia.org/wikipedia/commons/8/8f/Bok_choy_in_market.jpg',
        'Cherry tomatoes on vine' => 'https://upload.wikimedia.org/wikipedia/commons/8/88/Cherry_tomatoes_on_the_vine.jpg',
        'Collard greens' => 'https://upload.wikimedia.org/wikipedia/commons/0/04/Collard-Greens-Bundle.jpg',
        'Edamame' => 'https://upload.wikimedia.org/wikipedia/commons/6/6e/Edamame_by_avlxyz_in_Tokyo.jpg',
        'Green peas (shelled)' => 'https://upload.wikimedia.org/wikipedia/commons/5/51/Green_peas_in_pod.jpg',
        'Jerusalem artichoke' => 'https://upload.wikimedia.org/wikipedia/commons/4/4e/Topinambour.jpg',
        'Microgreens mix' => 'https://upload.wikimedia.org/wikipedia/commons/4/4b/Mesclun.jpg',
        'Portobello mushroom' => 'https://upload.wikimedia.org/wikipedia/commons/4/4e/Agaricus_bisporus.jpg',
        'Purple cabbage' => 'https://upload.wikimedia.org/wikipedia/commons/2/2f/Red_cabbage.jpg',
        'Savoy cabbage' => 'https://upload.wikimedia.org/wikipedia/commons/4/4e/Savoy_cabbage.jpg',
        'Snow peas' => 'https://upload.wikimedia.org/wikipedia/commons/5/51/Green_peas_in_pod.jpg',
        'Sugar snap peas' => 'https://upload.wikimedia.org/wikipedia/commons/5/51/Green_peas_in_pod.jpg',
        'Watercress' => 'https://upload.wikimedia.org/wikipedia/commons/4/4c/Nasturtium_officinale.jpg',
        'White mushroom' => 'https://upload.wikimedia.org/wikipedia/commons/4/4e/Agaricus_bisporus.jpg',
    ];

    public static function urlFor(string $englishName, string $sku): string
    {
        return self::candidateUrlsFor($englishName, $sku)[0];
    }

    /**
     * @return list<string>
     */
    public static function candidateUrlsFor(string $englishName, string $sku): array
    {
        $candidates = [];

        if (isset(self::OVERRIDES[$englishName])) {
            $candidates[] = self::OVERRIDES[$englishName];
        }

        $pool = self::POOL;
        $start = (int) (crc32($sku) % max(1, count($pool)));

        for ($i = 0; $i < count($pool); $i++) {
            $candidates[] = $pool[($start + $i) % count($pool)];
        }

        return array_values(array_unique($candidates));
    }
}
