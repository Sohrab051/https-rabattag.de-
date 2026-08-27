<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Merchant;
use App\Models\Offer;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['super-admin', 'content-manager', 'finance-manager', 'support'] as $role) {
            Role::findOrCreate($role);
        }

        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            ['name' => 'Admin', 'password' => bcrypt('password'), 'locale_preference' => 'de']
        );
        $admin->assignRole('super-admin');

        $categories = collect([
            ['name_en' => "Women's Fashion", 'name_de' => 'Damenmode', 'icon' => '👗'],
            ['name_en' => "Men's Fashion", 'name_de' => 'Herrenmode', 'icon' => '👔'],
            ['name_en' => 'Shoes', 'name_de' => 'Schuhe', 'icon' => '👟'],
            ['name_en' => 'Beauty & Cosmetics', 'name_de' => 'Beauty & Kosmetik', 'icon' => '💄'],
            ['name_en' => 'Accessories', 'name_de' => 'Accessoires', 'icon' => '👜'],
        ])->map(fn ($c, $i) => Category::firstOrCreate(
            ['slug' => Str::slug($c['name_en'])],
            [...$c, 'sort_order' => $i]
        ));

        $stores = [
            [
                'slug' => 'modehaus-berlin',
                'name_en' => 'Berlin Fashion House',
                'name_de' => 'Modehaus Berlin',
                'category' => "Women's Fashion",
                'description_en' => 'Trendy women\'s clothing and accessories for every season.',
                'description_de' => 'Trendige Damenmode und Accessoires für jede Saison.',
                'commission_rate' => 8,
                'offers' => [
                    ['title_en' => '30% off the new autumn collection', 'title_de' => '30% Rabatt auf die neue Herbstkollektion', 'discount_value' => 30, 'featured' => true],
                    ['title_en' => 'Free shipping on all orders', 'title_de' => 'Kostenloser Versand auf alle Bestellungen', 'discount_value' => null, 'featured' => false],
                ],
            ],
            [
                'slug' => 'herrenstil',
                'name_en' => 'Herrenstil Menswear',
                'name_de' => 'Herrenstil',
                'category' => "Men's Fashion",
                'description_en' => 'Classic and modern menswear for every occasion.',
                'description_de' => 'Klassische und moderne Herrenmode für jeden Anlass.',
                'commission_rate' => 7,
                'offers' => [
                    ['title_en' => '20% off suits and shirts', 'title_de' => '20% Rabatt auf Anzüge und Hemden', 'discount_value' => 20, 'featured' => true],
                ],
            ],
            [
                'slug' => 'schuhwelt',
                'name_en' => 'ShoeWorld',
                'name_de' => 'Schuhwelt',
                'category' => 'Shoes',
                'description_en' => 'Sneakers, boots, and heels from top brands.',
                'description_de' => 'Sneaker, Stiefel und High Heels von Topmarken.',
                'commission_rate' => 9,
                'offers' => [
                    ['title_en' => '35% off sneakers', 'title_de' => '35% Rabatt auf Sneaker', 'discount_value' => 35, 'featured' => true],
                    ['title_en' => '10% off running shoes', 'title_de' => '10% Rabatt auf Laufschuhe', 'discount_value' => 10, 'featured' => false],
                ],
            ],
            [
                'slug' => 'glow-beauty',
                'name_en' => 'Glow Beauty',
                'name_de' => 'Glow Beauty',
                'category' => 'Beauty & Cosmetics',
                'description_en' => 'Skincare and cosmetics from top international brands.',
                'description_de' => 'Hautpflege und Kosmetik von führenden internationalen Marken.',
                'commission_rate' => 10,
                'offers' => [
                    ['title_en' => '25% off your first order', 'title_de' => '25% Rabatt auf Ihre erste Bestellung', 'discount_value' => 25, 'featured' => true],
                ],
            ],
            [
                'slug' => 'accessoire-atelier',
                'name_en' => 'Accessory Atelier',
                'name_de' => 'Accessoire Atelier',
                'category' => 'Accessories',
                'description_en' => 'Bags, jewelry, and accessories to complete your look.',
                'description_de' => 'Taschen, Schmuck und Accessoires für den perfekten Look.',
                'commission_rate' => 6,
                'offers' => [
                    ['title_en' => '15% off handbags', 'title_de' => '15% Rabatt auf Handtaschen', 'discount_value' => 15, 'featured' => false],
                ],
            ],
        ];

        foreach ($stores as $store) {
            $category = $categories->firstWhere('name_en', $store['category']);

            $merchant = Merchant::firstOrCreate(
                ['slug' => $store['slug']],
                [
                    'name_en' => $store['name_en'],
                    'name_de' => $store['name_de'],
                    'category_id' => $category?->id,
                    'description_en' => $store['description_en'],
                    'description_de' => $store['description_de'],
                    'website_url' => 'https://example.com/'.$store['slug'],
                    'affiliate_link' => 'https://example.com/'.$store['slug'].'?ref=demo',
                    'commission_rate' => $store['commission_rate'],
                    'status' => 'active',
                    'is_featured' => true,
                ]
            );

            foreach ($store['offers'] as $offer) {
                Offer::firstOrCreate(
                    ['merchant_id' => $merchant->id, 'title_en' => $offer['title_en']],
                    [
                        'title_de' => $offer['title_de'],
                        'description_en' => 'Valid for a limited time only. See terms for details.',
                        'description_de' => 'Nur für kurze Zeit gültig. Details siehe Bedingungen.',
                        'discount_value' => $offer['discount_value'] ?? null,
                        'min_purchase_amount' => $offer['min'] ?? null,
                        'status' => 'published',
                        'is_featured' => $offer['featured'] ?? false,
                        'published_at' => now(),
                        'expires_at' => now()->addDays(30),
                    ]
                );
            }
        }

        $this->call(MasterMerchantSeeder::class);
    }
}
