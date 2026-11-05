<?php

namespace Tests\Feature;

use App\Domain\Identity\Enums\Role;
use App\Models\Category;
use App\Models\Product;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UiImprovementsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function test_login_page_renders_demo_quick_login_buttons_in_non_production(): void
    {
        $response = $this->get('/login');

        $response->assertOk();
        $response->assertSee('Masuk Cepat Mode Demo / Pengujian');
        $response->assertSee('Ibu Emi Erawati');
        $response->assertSee('Dinda Permata');
        $response->assertSee('Rina Wulandari');
    }

    public function test_product_create_form_provides_auto_generated_sku(): void
    {
        $owner = User::where('role', Role::Owner)->firstOrFail();

        $response = $this->actingAs($owner)->get(route('catalog.products.create'));

        $response->assertOk();
        $response->assertSee('Kode SKU');
        $response->assertSee('Terkunci');
        $response->assertSee('PRD-');
    }

    public function test_product_store_auto_fills_sku_if_empty(): void
    {
        $owner = User::where('role', Role::Owner)->firstOrFail();
        $category = Category::firstOrCreate(['slug' => 'minuman'], ['name' => 'Minuman', 'is_active' => true]);
        $unit = Unit::firstOrCreate(['code' => 'BTL'], ['code' => 'BTL', 'name' => 'Botol', 'dimension' => 'count', 'decimal_scale' => 0, 'is_active' => true]);

        $response = $this->actingAs($owner)->post(route('catalog.products.store'), [
            'name' => 'Teh Manis Botol',
            'category_id' => $category->id,
            'base_unit_id' => $unit->id,
            'conversion_to_base' => '1.0000',
            'unit_label' => 'Botol',
            'retail_price' => '5000',
            'low_stock_threshold' => '5.0000',
            'is_active' => '1',
        ]);

        $response->assertRedirect(route('catalog.products.index'));

        $product = Product::where('name', 'Teh Manis Botol')->first();
        $this->assertNotNull($product);
        $this->assertStringStartsWith('PRD-', $product->sku);
    }

    public function test_receipt_create_form_provides_auto_generated_nota(): void
    {
        $owner = User::where('role', Role::Owner)->firstOrFail();

        $response = $this->actingAs($owner)->get(route('receipts.create'));

        $response->assertOk();
        $response->assertSee('Nomor Nota Pemasok');
        $response->assertSee('Terisi otomatis');
        $response->assertSee('NOTA-'.now()->format('Ymd'));
    }

    public function test_sales_report_calculates_profit_and_renders_layman_friendly_cards(): void
    {
        $owner = User::where('role', Role::Owner)->firstOrFail();

        $response = $this->actingAs($owner)->get(route('reports.sales'));

        $response->assertOk();
        $response->assertSee('Laporan Penjualan');
        $response->assertSee('Total Uang Penjualan (Omzet)');
        $response->assertSee('Modal Belanja Barang');
        $response->assertSee('Keuntungan Toko (Laba Bersih)');
        $response->assertSee('Grafik Tren Penjualan Harian');
    }

    public function test_pos_view_renders_smooth_confirmation_modal(): void
    {
        $owner = User::where('role', Role::Owner)->firstOrFail();

        $response = $this->actingAs($owner)->get(route('pos.index'));

        $response->assertOk();
        $response->assertSee('Konfirmasi Transaksi Penjualan');
        $response->assertSee('Periksa Lagi');
        $response->assertSee('Selesaikan');
    }

    public function test_cashier_sidebar_does_not_render_owner_navigation_groups(): void
    {
        $cashier = User::factory()->create(['role' => Role::Cashier]);

        $response = $this->actingAs($cashier)->get(route('dashboard'));

        $response->assertOk();
        $response->assertSee('Operasional');
        $response->assertSee('Kasir (POS)');
        $response->assertSee('Riwayat Penjualan');
        $response->assertSee('Barang & Gudang');
        $response->assertSee('Katalog Produk');

        // Heading kelompok khusus owner/admin tidak boleh tampil sama sekali untuk kasir
        $response->assertDontSee('Pemasok & Hutang');
        $response->assertDontSee('Laporan & Analisis');
        $response->assertDontSee('Kelola & Pengaturan');
    }

    public function test_owner_sidebar_renders_all_navigation_groups(): void
    {
        $owner = User::where('role', Role::Owner)->firstOrFail();

        $response = $this->actingAs($owner)->get(route('dashboard'));

        $response->assertOk();
        $response->assertSee('Operasional');
        $response->assertSee('Barang & Gudang');
        $response->assertSee('Pemasok & Hutang');
        $response->assertSee('Laporan & Analisis');
        $response->assertSee('Kelola & Pengaturan');
    }
}
