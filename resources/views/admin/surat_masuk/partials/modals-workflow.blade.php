
<div class="modal fade text-start" id="modalNaik{{ $s->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('surat-masuk.status', $s->id) }}" method="POST">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Surat Naik ke Bupati</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="status" value="Naik ke Bupati">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nomor NPKND</label>
                        <input type="text" name="no_npknd" class="form-control" required placeholder="Contoh: 123/ND/2023" value="{{ $s->no_npknd }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Tanggal Naik</label>
                        <input type="date" name="tgl_input" class="form-control" required value="{{ date('Y-m-d') }}">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade text-start" id="modalTurun{{ $s->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('surat-masuk.status', $s->id) }}" method="POST">
                @csrf
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">Surat Turun dari Bupati</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="status" value="Turun dari Bupati">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Konfirmasi NPKND</label>
                        <input type="text" name="no_npknd" class="form-control" required value="{{ $s->no_npknd }}" readonly>
                        <small class="text-muted">Nomor otomatis terisi.</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Tanggal Turun</label>
                        <input type="date" name="tgl_input" class="form-control" required value="{{ date('Y-m-d') }}">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>