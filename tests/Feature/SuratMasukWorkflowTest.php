<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\SuratMasuk;
use App\Models\Disposisi;
use App\Models\TrackingSurat;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SuratMasukWorkflowTest extends TestCase
{
    use RefreshDatabase;

    private $admin;
    private $kabag;
    private $staff;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->admin = User::factory()->admin()->create();
        $this->kabag = User::factory()->kabag()->create();
        $this->staff = User::factory()->staff()->create();
    }

    /**
     * Test complete Surat Masuk workflow
     */
    public function test_complete_surat_masuk_workflow()
    {
        // 1. Create Surat Masuk directly using factory
        $surat = SuratMasuk::factory()->create([
            'user_id' => $this->admin->id,
            'status' => 'Menunggu Validasi'
        ]);

        $this->assertNotNull($surat);
        $this->assertEquals('Menunggu Validasi', $surat->status);

        // 2. Validasi Awal by Kabag
        $this->actingAs($this->kabag);
        
        $surat->update(['status' => 'Siap Disposisi']);

        $surat->refresh();
        $this->assertEquals('Siap Disposisi', $surat->status);

        // 3. Create Disposisi
        $disposisi = Disposisi::create([
            'surat_masuk_id' => $surat->id,
            'dari_user_id' => $this->kabag->id,
            'tujuan_user_id' => $this->staff->id,
            'instruksi' => 'Mohon diproses segera',
            'sifat' => 'Penting',
            'jenis_surat' => 'Undangan',
            'status' => 0,
        ]);

        $surat->update(['status' => 'Sedang Diproses']);

        $surat->refresh();
        $this->assertEquals('Sedang Diproses', $surat->status);
        $this->assertEquals(1, $surat->disposisi()->count());

        // 4. Staff receives disposition
        $this->actingAs($this->staff);
        
        $disposisi->update(['status' => 1]); // Sedang Dikerjakan
        
        $disposisi->refresh();
        $this->assertEquals(1, $disposisi->status);

        // 5. Staff completes work
        $disposisi->update([
            'status' => 2,
            'catatan_staff' => 'Pekerjaan selesai dengan baik'
        ]);

        $disposisi->refresh();
        $this->assertEquals(2, $disposisi->status);

        // 6. Kabag verifies and approves
        $this->actingAs($this->kabag);
        
        $surat->update(['status' => 'Selesai']);

        $surat->refresh();
        $this->assertEquals('Selesai', $surat->status);

        $this->assertTrue(true);
    }

    /**
     * Test rejection workflow
     */
    public function test_surat_masuk_rejection_workflow()
    {
        $surat = SuratMasuk::factory()->create(['status' => 'Menunggu Validasi']);

        $surat->update([
            'status' => 'Ditolak',
            'alasan_tolak' => 'Data tidak lengkap',
        ]);

        $surat->refresh();
        $this->assertEquals('Ditolak', $surat->status);
        $this->assertEquals('Data tidak lengkap', $surat->alasan_tolak);
    }

    /**
     * Test revision workflow
     */
    public function test_surat_masuk_revision_workflow()
    {
        $surat = SuratMasuk::factory()->processed()->create();
        $disposisi = $surat->disposisi()->create([
            'dari_user_id' => $this->kabag->id,
            'tujuan_user_id' => $this->staff->id,
            'instruksi' => 'test',
            'sifat' => 'Penting',
            'jenis_surat' => 'Laporan',
            'status' => 2,
        ]);

        $surat->update([
            'status' => 'Perlu Revisi',
            'catatan_revisi' => 'Kurang lengkap, mohon diperdalam analisisnya',
        ]);

        $surat->refresh();
        $this->assertEquals('Perlu Revisi', $surat->status);
    }

    /**
     * Test workflow tracking
     */
    public function test_surat_masuk_tracking()
    {
        $surat = SuratMasuk::factory()->create();

        TrackingSurat::create([
            'surat_masuk_id' => $surat->id,
            'status_log' => 'Baru',
            'tgl_status' => now(),
            'user_id' => $this->admin->id,
            'catatan' => 'Surat baru diinput',
        ]);

        $tracking = TrackingSurat::where('surat_masuk_id', $surat->id)->get();
        $this->assertGreaterThan(0, $tracking->count());
    }
}