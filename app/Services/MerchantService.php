<?php

namespace App\Services;

use App\Models\Merchant;

class MerchantService
{
    /**
     * Set the merchant's primary category and sync the many-to-many
     * categories pivot from a single source of truth.
     *
     * The first ID in $categoryIds becomes the "primary" category
     * (stored on merchants.category_id), and the full list is written
     * to the category_merchant pivot table. This is shared by every
     * admin code path that creates or edits a merchant so the pivot
     * and the primary category can never drift apart again.
     *
     * @param  array<int, int|string>  $categoryIds
     */
    public function syncCategories(Merchant $merchant, array $categoryIds): void
    {
        $categoryIds = array_values(array_filter($categoryIds, fn ($id) => $id !== null && $id !== ''));

        $merchant->forceFill(['category_id' => $categoryIds[0] ?? null])->save();

        $merchant->categories()->sync($categoryIds);
    }
}
