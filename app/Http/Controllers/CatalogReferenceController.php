<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Unit;
use App\Services\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CatalogReferenceController extends Controller
{
    public function categories(): View
    {
        return view('pages.catalog.categories', ['categories' => Category::orderBy('name')->get()]);
    }

    public function storeCategory(Request $request, AuditLogger $audit): RedirectResponse
    {
        abort_unless($request->user()->hasPermission('products.create'), 403);
        $data = $request->validate(['name' => 'required|string|max:100|unique:categories,name']);
        $category = Category::create(['name' => trim($data['name']), 'slug' => Str::slug($data['name']), 'is_active' => true]);
        $audit->record('catalog.category_created', $request->user(), $category);

        return back()->with('status', 'Kategori ditambahkan.');
    }

    public function units(): View
    {
        return view('pages.catalog.units', ['units' => Unit::orderBy('name')->get()]);
    }

    public function storeUnit(Request $request, AuditLogger $audit): RedirectResponse
    {
        abort_unless($request->user()->hasPermission('product_units.manage'), 403);
        $data = $request->validate(['code' => 'required|string|max:20|unique:units,code', 'name' => 'required|string|max:60', 'dimension' => 'required|in:count,mass,volume,package', 'decimal_scale' => 'required|integer|min:0|max:4']);
        $unit = Unit::create([...$data, 'code' => Str::lower($data['code']), 'is_active' => true]);
        $audit->record('catalog.unit_created', $request->user(), $unit);

        return back()->with('status', 'Satuan ditambahkan.');
    }
}
