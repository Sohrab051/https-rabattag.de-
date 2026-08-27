<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::query()
            ->withCount(['merchants', 'offers'])
            ->orderBy('sort_order')
            ->get();

        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        $category = new Category();

        return view('admin.categories.create', compact('category'));
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);

        Category::create($data);

        return redirect()
            ->route('admin.categories.index', ['locale' => app()->getLocale()])
            ->with('status', __('Category created.'));
    }

    public function edit(string $locale, Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, string $locale, Category $category)
    {
        $data = $this->validateData($request, $category->id);

        $category->update($data);

        return redirect()
            ->route('admin.categories.index', ['locale' => app()->getLocale()])
            ->with('status', __('Category updated.'));
    }

    public function toggleStatus(string $locale, Category $category)
    {
        $category->update(['is_active' => ! $category->is_active]);

        return back()->with('status', __('Category status updated.'));
    }

    public function destroy(string $locale, Category $category)
    {
        $merchantCount = $category->merchants()->count() + $category->pivotMerchants()->count();
        $offerCount = $category->offers()->count();

        if ($merchantCount > 0 || $offerCount > 0) {
            return back()->withErrors([
                'category' => __('This category cannot be deleted because it still has :merchants store(s) and :offers offer(s) assigned to it.', [
                    'merchants' => $merchantCount,
                    'offers' => $offerCount,
                ]),
            ]);
        }

        $category->delete();

        return redirect()
            ->route('admin.categories.index', ['locale' => app()->getLocale()])
            ->with('status', __('Category deleted.'));
    }

    private function validateData(Request $request, ?int $categoryId = null): array
    {
        $data = $request->validate([
            'name_en' => ['required', 'string', 'max:255'],
            'name_de' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'alpha_dash', 'unique:categories,slug'.($categoryId ? ",{$categoryId}" : '')],
            'icon' => ['nullable', 'string', 'max:255'],
        ]);

        $data['is_active'] = $request->boolean('is_active', true);

        return $data;
    }
}
