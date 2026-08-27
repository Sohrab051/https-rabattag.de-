<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Merchant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class MasterMerchantSeeder extends Seeder
{
    /**
     * Merchant name => [primary category name_en, ...additional category name_en].
     * Category names match the name_en values seeded by DatabaseSeeder.
     */
    private const MERCHANTS = [
        'Foot Locker' => ['Shoes'],
        'SportSpar' => ['Shoes'],
        'SportScheck' => ['Shoes'],
        'BSTN' => ['Shoes'],
        'size?' => ['Shoes'],
        'Tamaris' => ['Shoes', "Women's Fashion"],
        'UGG' => ['Shoes'],
        'JD Sports' => ['Shoes'],
        'adidas' => ['Shoes'],
        'JOOP!' => ["Men's Fashion", "Women's Fashion"],
        'Mustang' => ["Women's Fashion", "Men's Fashion"],
        'Guess' => ["Women's Fashion", "Men's Fashion", 'Accessories'],
        'Breuninger' => ["Women's Fashion", "Men's Fashion"],
        'Peter Hahn' => ["Women's Fashion"],
        'LASCANA' => ["Women's Fashion"],
        'CECIL' => ["Women's Fashion"],
        'Street One' => ["Women's Fashion"],
        'S.Oliver' => ["Women's Fashion", "Men's Fashion"],
        'Marc Aurel' => ["Women's Fashion"],
        'Jack Wolfskin' => ["Men's Fashion", "Women's Fashion"],
        'Engbers' => ["Women's Fashion"],
        'Flaconi' => ['Beauty & Cosmetics'],
        'Sephora' => ['Beauty & Cosmetics'],
        'Asambeauty' => ['Beauty & Cosmetics'],
        'Beautywelt' => ['Beauty & Cosmetics'],
        'top Parfümerie' => ['Beauty & Cosmetics'],
        'Hairlust' => ['Beauty & Cosmetics'],
        'Beauty Bay' => ['Beauty & Cosmetics'],
        'Beauty The Shop' => ['Beauty & Cosmetics'],
        'Parfumgroup' => ['Beauty & Cosmetics'],
        'KIKO' => ['Beauty & Cosmetics'],
        'Taschenkaufhaus' => ['Accessories'],
        'Liebeskind Berlin' => ['Accessories'],
        'Michael Kors' => ['Accessories'],
        'CHRIST' => ['Accessories'],
        'Brinckmann & Lange' => ['Accessories'],
        'Heideman Schmuck' => ['Accessories'],
        'thejewellershop' => ['Accessories'],
        'TAKE A SHOT' => ['Accessories'],
        'NEW ONE' => ['Accessories'],
        'Glambou' => ['Accessories'],
    ];

    public function run(): void
    {
        $categories = Category::all()->keyBy('name_en');

        foreach (self::MERCHANTS as $name => $categoryNames) {
            $categoryIds = collect($categoryNames)
                ->map(fn (string $categoryName) => $categories->get($categoryName)?->id)
                ->filter()
                ->values();

            $merchant = Merchant::firstOrCreate(
                ['slug' => Str::slug($name)],
                [
                    'name_en' => $name,
                    'name_de' => $name,
                    'logo' => null,
                    'category_id' => $categoryIds->first(),
                    'awin_merchant_id' => null,
                    'source' => 'manual',
                    'affiliate_link' => null,
                    'status' => 'pending_contract',
                    'last_synced_at' => null,
                ]
            );

            $merchant->categories()->sync($categoryIds);
        }
    }
}
