
<div class="container">
    <div class="d-flex justify-content-between align-items-center">
        <h2 class="fw-bold">Kelola Jasa Saya</h2>
        <button class="btn btn-primary-custom rounded-pill" data-bs-toggle="modal" data-bs-target="#addModal">+ Tambah Jasa</button>
    </div>
    <table class="table mt-4 align-middle">
        <thead class="table-light"><tr><th>Gambar</th><th>Judul</th><th>Kategori</th><th>Harga</th><th>Status</th><th>Aksi</th></tr></thead>
        <tbody>
            <tr>
                <td><i class="bi bi-image fs-3 text-muted"></i></td>
                <td>Jasa Bersih Rumah</td>
                <td>Bersih-bersih</td>
                <td>Rp 150.000</td>
                <td><span class="badge bg-success">Aktif</span></td>
                <td>
                    <button class="btn btn-sm btn-outline-warning rounded-pill">Edit</button> 
                    <button class="btn btn-sm btn-outline-danger rounded-pill">Hapus</button>
                </td>
            </tr>
        </tbody>
    </table>
</div>

<!-- Modal Tambah Jasa -->
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">Tambah Jasa Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="mb-3"><label>Judul Jasa</label><input type="text" class="form-control rounded-pill"></div>
                    <div class="mb-3"><label>Kategori</label><select class="form-select rounded-pill"><option>Bersih-bersih</option></select></div>
                    <div class="mb-3"><label>Harga (Rp)</label><input type="number" class="form-control rounded-pill"></div>
                    <div class="mb-3"><label>Deskripsi</label><textarea class="form-control" rows="3"></textarea></div>
                    <button type="submit" class="btn btn-primary-custom w-100 rounded-pill">Simpan Jasa</button>
                </form>
            </div>
        </div>
    </div>
</div>