<?php

namespace App\Services\Awin;

use App\Models\Category;
use Illuminate\Support\Str;

/**
 * Maps a raw Awin category/keyword string onto one of Rabattag's 5 fixed local
 * categories. Never invents a new category — if nothing matches, returns null so the
 * caller (AwinDealSyncer) can flag the offer with needs_review = true instead.
 */
class CategoryMapper
{
    /**
     * Keyword => local category slug. Matching is case-insensitive substring matching
     * against the incoming Awin category/keyword string.
     */
    private const KEYWORD_MAP = [
        'womens-fashion' => ['women', 'woman', 'dress', 'damen', 'ladies', "women's"],
        'mens-fashion' => ['men', 'herren', 'menswear', "men's"],
        'shoes' => ['shoe', 'sneaker', 'schuh', 'boot', 'footwear'],
        'beauty-cosmetics' => ['beauty', 'cosmetic', 'kosmetik', 'skincare', 'makeup'],
        'accessories' => ['bag', 'accessory', 'accessoire', 'schmuck', 'jewelry', 'jewellery'],
    ];

    /**
     * Resolve a raw Awin category/keyword string to a local Category, or null if none
     * of the 5 fixed categories match.
     *
     * @param  string|array<int, string>  $awinCategory  Raw category string(s) from Awin.
     */
    public function map(string|array $awinCategory): ?Category
    {
        $haystacks = array_map(
            fn ($value) => Str::lower((string) $value),
            is_array($awinCategory) ? $awinCategory : [$awinCategory]
        );

        foreach (self::KEYWORD_MAP as $slug => $keywords) {
            foreach ($haystacks as $haystack) {
                foreach ($keywords as $keyword) {
                    if ($haystack !== '' && str_contains($haystack, $keyword)) {
                        return Category::where('slug', $slug)->first();
                    }
                }
            }
        }

        return null;
    }
}
