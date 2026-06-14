<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Umkm;
use App\Models\Menu;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class AdminMenuTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $umkm;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'name' => 'Admin Test',
            'email' => 'admin@test.com',
            'password' => bcrypt('password123'),
            'role' => 'admin',
        ]);

        $this->umkm = Umkm::create([
            'nama_umkm' => 'Warung Test',
            'deskripsi' => 'Deskripsi Warung',
            'alamat' => 'Alamat Warung',
            'no_whatsapp' => '08123456789',
            'kategori' => ['Makanan Berat'],
            'is_delivery' => true,
            'jam_operasional' => '08:00 - 22:00',
            'hari_operasional' => 'Senin - Minggu',
        ]);
    }

    public function test_store_menu_with_alphabetic_price_fails()
    {
        $response = $this->actingAs($this->admin)
            ->from(route('admin.umkm.menus.index', $this->umkm->id))
            ->post(route('admin.umkm.menus.store', $this->umkm->id), [
                'nama_menu' => 'Nasi Goreng',
                'harga' => 'abc', // alphabetic price
                'kategori' => 'Makanan Berat',
                'deskripsi' => 'Nasi goreng enak',
            ]);

        $response->assertRedirect(route('admin.umkm.menus.index', $this->umkm->id));
        $response->assertSessionHasErrors(['harga' => 'Harga harus berupa angka.']);
        
        $this->assertEquals(0, Menu::count());
    }

    public function test_store_menu_with_negative_price_fails()
    {
        $response = $this->actingAs($this->admin)
            ->from(route('admin.umkm.menus.index', $this->umkm->id))
            ->post(route('admin.umkm.menus.store', $this->umkm->id), [
                'nama_menu' => 'Nasi Goreng',
                'harga' => '-5000', // negative price
                'kategori' => 'Makanan Berat',
                'deskripsi' => 'Nasi goreng enak',
            ]);

        $response->assertRedirect(route('admin.umkm.menus.index', $this->umkm->id));
        $response->assertSessionHasErrors(['harga' => 'Harga tidak boleh negatif.']);
        
        $this->assertEquals(0, Menu::count());
    }

    public function test_store_menu_with_pdf_image_fails()
    {
        $response = $this->actingAs($this->admin)
            ->from(route('admin.umkm.menus.index', $this->umkm->id))
            ->post(route('admin.umkm.menus.store', $this->umkm->id), [
                'nama_menu' => 'Nasi Goreng',
                'harga' => '15000',
                'kategori' => 'Makanan Berat',
                'deskripsi' => 'Nasi goreng enak',
                'gambar' => UploadedFile::fake()->create('document.pdf', 100, 'application/pdf'), // pdf format
            ]);

        $response->assertRedirect(route('admin.umkm.menus.index', $this->umkm->id));
        $response->assertSessionHasErrors(['gambar' => 'Format file tidak didukung']);
        
        $this->assertEquals(0, Menu::count());
    }
}
