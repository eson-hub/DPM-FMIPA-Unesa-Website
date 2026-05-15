<?php
// admin_dashboard.php
require_once 'config.php';
proteksi_halaman();

// ✅ FIX 1: Whitelist menu yang valid untuk mencegah SQL injection & query gagal
$menu_valid = ['articles', 'agenda', 'gallery', 'regulations', 'quotes', 'supervisions', 'organization_profile'];
$menu_aktif = $_GET['menu'] ?? 'articles';

// Jika menu tidak valid, paksa kembali ke default
if (!in_array($menu_aktif, $menu_valid)) {
    $menu_aktif = 'articles';
}

// Ambil semua data dari tabel yang dipilih
try {
    $stmt = $db->query("SELECT * FROM `$menu_aktif` ORDER BY id DESC");
    $daftar_data = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Eror pengambilan data panel: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - DPM FMIPA UNESA</title>

    <!-- ✅ FIX 2: URL CDN Bootstrap & Bootstrap Icons yang benar -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        body { background-color: #f8f9fa; }
        .sidebar { min-height: 100vh; background-color: #343a40; color: white; }
        .sidebar .nav-link { color: #c2c7d0; }
        .sidebar .nav-link.active { background-color: #007bff; color: white; border-radius: 4px; }
        .sidebar .nav-link:hover { color: white; background-color: rgba(255,255,255,0.1); }
        .main-content { padding: 25px; }
        .img-thumb { width: 50px; height: 50px; object-fit: cover; border-radius: 4px; }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <!-- SIDEBAR -->
        <div class="col-md-3 col-lg-2 sidebar p-3 d-flex flex-column">
            <h5 class="text-center py-3 border-bottom border-secondary">DPM FMIPA UNESA</h5>
            <ul class="nav nav-pills flex-column mb-auto mt-3">
                <li class="nav-item">
                    <a href="?menu=articles" class="nav-link <?= $menu_aktif === 'articles' ? 'active' : '' ?>">
                        <i class="bi bi-journal-text me-2"></i> Artikel
                    </a>
                </li>
                <li>
                    <a href="?menu=agenda" class="nav-link <?= $menu_aktif === 'agenda' ? 'active' : '' ?>">
                        <i class="bi bi-calendar-event me-2"></i> Agenda Kegiatan
                    </a>
                </li>
                <li>
                    <a href="?menu=gallery" class="nav-link <?= $menu_aktif === 'gallery' ? 'active' : '' ?>">
                        <i class="bi bi-images me-2"></i> Galeri
                    </a>
                </li>
                <li>
                    <a href="?menu=regulations" class="nav-link <?= $menu_aktif === 'regulations' ? 'active' : '' ?>">
                        <i class="bi bi-file-earmark-pdf me-2"></i> Regulasi
                    </a>
                </li>
                <li>
                    <a href="?menu=quotes" class="nav-link <?= $menu_aktif === 'quotes' ? 'active' : '' ?>">
                        <i class="bi bi-chat-quote me-2"></i> Quotes
                    </a>
                </li>
                <li>
                    <a href="?menu=supervisions" class="nav-link <?= $menu_aktif === 'supervisions' ? 'active' : '' ?>">
                        <i class="bi bi-eye me-2"></i> Pengawasan Komisi
                    </a>
                </li>
                <li>
                    <a href="?menu=organization_profile" class="nav-link <?= $menu_aktif === 'organization_profile' ? 'active' : '' ?>">
                        <i class="bi bi-building me-2"></i> Profil Organisasi
                    </a>
                </li>
            </ul>
            <hr class="border-secondary">
            <a href="logout.php" class="btn btn-danger btn-sm w-100">
                <i class="bi bi-box-arrow-right me-1"></i> Keluar / Logout
            </a>
        </div>

        <!-- MAIN CONTENT -->
        <div class="col-md-9 col-lg-10 main-content">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Kelola Data: <span class="text-capitalize"><?= str_replace('_', ' ', $menu_aktif) ?></span></h2>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">
                    <i class="bi bi-plus-circle me-1"></i> Tambah Data Baru
                </button>
            </div>

            <?php if (isset($_GET['status'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    🚀 Operasi data database berhasil dieksekusi!
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="card shadow-sm">
                <div class="card-body p-0">
                    <table class="table table-striped table-hover mb-0 align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th class="ps-3">ID</th>
                                <?php if($menu_aktif === 'articles'): ?>
                                    <th>Foto</th><th>Judul</th><th>Status</th>
                                <?php elseif($menu_aktif === 'agenda'): ?>
                                    <th>Pamflet</th><th>Kegiatan</th><th>Tanggal</th><th>Lokasi</th>
                                <?php elseif($menu_aktif === 'gallery'): ?>
                                    <th>Foto</th><th>Keterangan</th>
                                <?php elseif($menu_aktif === 'regulations'): ?>
                                    <th>Judul Dokumen</th><th>Kategori</th>
                                <?php elseif($menu_aktif === 'quotes'): ?>
                                    <th>Kutipan Teks</th><th>Penulis</th>
                                <?php elseif($menu_aktif === 'supervisions'): ?>
                                    <th>Foto</th><th>Tugas Pengawasan</th><th>Komisi</th>
                                <?php elseif($menu_aktif === 'organization_profile'): ?>
                                    <th>Bagian Profil</th><th>Konten Ringkas</th>
                                <?php endif; ?>
                                <th class="text-end pe-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($daftar_data) > 0): ?>
                                <?php foreach ($daftar_data as $row): ?>
                                <tr>
                                    <td class="ps-3"><?= $row['id'] ?></td>

                                    <?php if($menu_aktif === 'articles'): ?>
                                        <td><img src="<?= htmlspecialchars($row['photo_url'] ?: 'uploads/default.jpg') ?>" class="img-thumb" alt="foto"></td>
                                        <td><strong><?= htmlspecialchars($row['title']) ?></strong></td>
                                        <td><span class="badge bg-<?= $row['status'] === 'Publik' ? 'success' : 'secondary' ?>"><?= $row['status'] ?></span></td>

                                    <?php elseif($menu_aktif === 'agenda'): ?>
                                        <td><img src="<?= htmlspecialchars($row['pamphlet_url'] ?: 'uploads/default.jpg') ?>" class="img-thumb" alt="pamflet"></td>
                                        <td><?= htmlspecialchars($row['title']) ?></td>
                                        <td><?= $row['event_date'] ?></td>
                                        <td><?= htmlspecialchars($row['location']) ?></td>

                                    <?php elseif($menu_aktif === 'gallery'): ?>
                                        <td><img src="<?= htmlspecialchars($row['photo_url']) ?>" class="img-thumb" alt="foto galeri"></td>
                                        <td><?= htmlspecialchars($row['caption']) ?></td>

                                    <?php elseif($menu_aktif === 'regulations'): ?>
                                        <td>
                                            <a href="<?= htmlspecialchars($row['file_url']) ?>" target="_blank">
                                                <i class="bi bi-file-earmark-pdf-fill text-danger me-1"></i>
                                                <?= htmlspecialchars($row['title']) ?>
                                            </a>
                                        </td>
                                        <td><span class="badge bg-info text-dark"><?= $row['category'] ?></span></td>

                                    <?php elseif($menu_aktif === 'quotes'): ?>
                                        <td>"<?= htmlspecialchars($row['quote_text']) ?>"</td>
                                        <td><em><?= htmlspecialchars($row['author'] ?: 'Anonim') ?></em></td>

                                    <?php elseif($menu_aktif === 'supervisions'): ?>
                                        <td><img src="<?= htmlspecialchars($row['photo_url'] ?: 'uploads/default.jpg') ?>" class="img-thumb" alt="foto"></td>
                                        <td>
                                            <strong><?= htmlspecialchars($row['title']) ?></strong><br>
                                            <small><?= htmlspecialchars($row['subtitle']) ?></small>
                                        </td>
                                        <td><?= htmlspecialchars($row['commission']) ?></td>

                                    <?php elseif($menu_aktif === 'organization_profile'): ?>
                                        <td><span class="text-uppercase font-monospace"><?= htmlspecialchars($row['section']) ?></span></td>
                                        <td><?= substr(strip_tags($row['content']), 0, 80) ?>...</td>
                                    <?php endif; ?>

                                    <td class="text-end pe-3">
                                        <a href="admin_crud.php?action=delete&type=<?= $menu_aktif ?>&id=<?= $row['id'] ?>"
                                           class="btn btn-outline-danger btn-sm"
                                           onclick="return confirm('Apakah Anda yakin mau menghapus data ini secara permanen?')">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">
                                        Belum ada catatan data. Silakan klik Tambah Data Baru.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODAL TAMBAH DATA -->
<div class="modal fade" id="modalTambah" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form action="admin_crud.php" method="POST" enctype="multipart/form-data" class="modal-content">
            <input type="hidden" name="action" value="insert">
            <input type="hidden" name="type" value="<?= $menu_aktif ?>">

            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title">Form Isian Baru: <span class="text-capitalize"><?= $menu_aktif ?></span></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <?php if($menu_aktif === 'articles'): ?>
                    <div class="mb-3"><label class="form-label">Judul Artikel</label><input type="text" name="title" class="form-control" required></div>
                    <div class="mb-3"><label class="form-label">Isi Konten Artikel</label><textarea name="content" class="form-control" rows="4"></textarea></div>
                    <div class="mb-3"><label class="form-label">Tagar Tags (pisahkan koma)</label><input type="text" name="tags" class="form-control" placeholder="berita, internal, ormawa"></div>
                    <div class="row mb-3">
                        <div class="col"><label class="form-label">Status Terbit</label>
                            <select name="status" class="form-select">
                                <option value="Draft">Draft</option>
                                <option value="Publik">Publik</option>
                            </select>
                        </div>
                        <div class="col"><label class="form-label">Foto Thumbnail</label><input type="file" name="photo_url" class="form-control" accept="image/*"></div>
                    </div>

                <?php elseif($menu_aktif === 'agenda'): ?>
                    <div class="mb-3"><label class="form-label">Judul Kegiatan</label><input type="text" name="title" class="form-control" required></div>
                    <div class="mb-3"><label class="form-label">Lokasi Acara</label><input type="text" name="location" class="form-control"></div>
                    <div class="row mb-3">
                        <div class="col"><label class="form-label">Tanggal</label><input type="date" name="event_date" class="form-control"></div>
                        <div class="col"><label class="form-label">Waktu Mulai</label><input type="time" name="event_time" class="form-control"></div>
                    </div>
                    <div class="mb-3"><label class="form-label">File Pamflet</label><input type="file" name="pamphlet_url" class="form-control" accept="image/*"></div>

                <?php elseif($menu_aktif === 'gallery'): ?>
                    <div class="mb-3"><label class="form-label">Unggah Foto</label><input type="file" name="photo_url" class="form-control" accept="image/*" required></div>
                    <div class="mb-3"><label class="form-label">Caption Gambar</label><input type="text" name="caption" class="form-control"></div>

                <?php elseif($menu_aktif === 'regulations'): ?>
                    <div class="mb-3"><label class="form-label">Judul Dokumen</label><input type="text" name="title" class="form-control" required></div>
                    <div class="mb-3"><label class="form-label">Kategori</label>
                        <select name="category" class="form-select">
                            <option value="SK">Surat Keputusan (SK)</option>
                            <option value="Peraturan">Peraturan Mahasiswa</option>
                            <option value="Sidang">Ketetapan Sidang</option>
                        </select>
                    </div>
                    <div class="mb-3"><label class="form-label">File PDF</label><input type="file" name="file_url" class="form-control" accept=".pdf" required></div>

                <?php elseif($menu_aktif === 'quotes'): ?>
                    <div class="mb-3"><label class="form-label">Isi Kutipan</label><textarea name="quote_text" class="form-control" rows="3" required></textarea></div>
                    <div class="mb-3"><label class="form-label">Penulis</label><input type="text" name="author" class="form-control" placeholder="Anonim"></div>

                <?php elseif($menu_aktif === 'supervisions'): ?>
                    <div class="mb-3"><label class="form-label">Judul Pengawasan</label><input type="text" name="title" class="form-control" required></div>
                    <div class="mb-3"><label class="form-label">Subtitle</label><input type="text" name="subtitle" class="form-control"></div>
                    <div class="mb-3"><label class="form-label">Komisi</label><input type="text" name="commission" class="form-control" placeholder="Contoh: Komisi I"></div>
                    <div class="mb-3"><label class="form-label">Foto Dokumentasi</label><input type="file" name="photo_url" class="form-control" accept="image/*"></div>

                <?php elseif($menu_aktif === 'organization_profile'): ?>
                    <div class="mb-3"><label class="form-label">Bagian Profil</label><input type="text" name="section" class="form-control" placeholder="visi / misi / sejarah" required></div>
                    <div class="mb-3"><label class="form-label">Isi Konten</label><textarea name="content" class="form-control" rows="4"></textarea></div>
                    <div class="mb-3"><label class="form-label">Foto (Opsional)</label><input type="file" name="photo_url" class="form-control" accept="image/*"></div>
                <?php endif; ?>
            </div>

            <div class="modal-footer">
                <!-- ✅ FIX 4: Typo "class-secondary" → "btn-secondary" -->
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-success">Simpan Data ke SQLite</button>
            </div>
        </form>
    </div>
</div>

<!-- ✅ FIX 2: URL CDN Bootstrap JS yang benar -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>