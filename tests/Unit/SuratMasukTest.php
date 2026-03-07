<?php

namespace Tests\Unit;

use App\Models\SuratMasuk;
use App\Models\User;
use App\Models\Disposisi;
use App\Models\TrackingSurat;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SuratMasukTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test SuratMasuk model creation
     */
    public function test_surat_masuk_creation()
    {
        $surat = SuratMasuk::factory()->create();

        $this->assertNotNull($surat->id);
        $this->assertNotNull($surat->no_agenda);
        $this->assertEquals('Menunggu Validasi', $surat->status);
    }

    /**
     * Test SuratMasuk relationships
     */
    public function test_surat_masuk_relationships()
    {
        $surat = SuratMasuk::factory()->create();
        $disposisi = Disposisi::factory()
            ->create(['surat_masuk_id' => $surat->id]);
        $tracking = TrackingSurat::factory()
            ->create(['surat_masuk_id' => $surat->id]);

        $this->assertEquals(1, $surat->disposisi()->count());
        $this->assertNotNull($surat->user);
    }

    /**
     * Test status transitions
     */
    public function test_status_transitions()
    {
        $surat = SuratMasuk::factory()
            ->create(['status' => 'Menunggu Validasi']);

        $surat->update(['status' => 'Siap Disposisi']);
        $this->assertEquals('Siap Disposisi', $surat->fresh()->status);

        $surat->update(['status' => 'Sedang Diproses']);
        $this->assertEquals('Sedang Diproses', $surat->fresh()->status);

        $surat->update(['status' => 'Selesai']);
        $this->assertEquals('Selesai', $surat->fresh()->status);
    }
}