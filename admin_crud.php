<?php
// admin_crud.php
require_once 'config.php';
proteksi_halaman(); // Wajib login

$target_dir = 'uploads/';
$action = $_POST['action'] ?? $_GET['action'] ?? '';
$type = $_POST['type'] ?? $_GET['type'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // --- PROSES TAMBAH DATA (CREATE) ---
    if ($action === 'insert') {
        switch ($type) {
            case 'articles':
                $photo = upload_file('photo_url', $target_dir);
                $stmt = $db->prepare("INSERT INTO articles (title, content, photo_url, tags, status) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$_POST['title'], $_POST['content'], $photo, $_POST['tags'], $_POST['status']]);
                break;

            case 'agenda':
                $pamphlet = upload_file('pamphlet_url', $target_dir);
                $stmt = $db->prepare("INSERT INTO agenda (title, event_date, event_time, location, pamphlet_url) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$_POST['title'], $_POST['event_date'], $_POST['event_time'], $_POST['location'], $pamphlet]);
                break;

            case 'gallery':
                $photo = upload_file('photo_url', $target_dir);
                if ($photo) {
                    $stmt = $db->prepare("INSERT INTO gallery (photo_url, caption) VALUES (?, ?)");
                    $stmt->execute([$photo, $_POST['caption']]);
                }
                break;

            case 'regulations':
                $file = upload_file('file_url', $target_dir);
                $stmt = $db->prepare("INSERT INTO regulations (title, file_url, category) VALUES (?, ?, ?)");
                $stmt->execute([$_POST['title'], $file, $_POST['category']]);
                break;

            case 'quotes':
                $stmt = $db->prepare("INSERT INTO quotes (quote_text, author) VALUES (?, ?)");
                $stmt->execute([$_POST['quote_text'], $_POST['author']]);
                break;

            case 'supervisions':
                $photo = upload_file('photo_url', $target_dir);
                $stmt = $db->prepare("INSERT INTO supervisions (title, subtitle, photo_url, commission) VALUES (?, ?, ?, ?)");
                $stmt->execute([$_POST['title'], $_POST['subtitle'], $photo, $_POST['commission']]);
                break;

            case 'organization_profile':
                $photo = upload_file('photo_url', $target_dir);
                $stmt = $db->prepare("INSERT INTO organization_profile (section, content, photo_url) VALUES (?, ?, ?)");
                $stmt->execute([$_POST['section'], $_POST['content'], $photo]);
                break;
        }
        header("Location: admin_dashboard.php?menu=$type&status=success_insert");
        exit;
    }

    // --- PROSES UBAH DATA (UPDATE) ---
    if ($action === 'update') {
        $id = $_POST['id'];
        switch ($type) {
            case 'articles':
                $photo = upload_file('photo_url', $target_dir) ?: $_POST['old_photo'];
                $stmt = $db->prepare("UPDATE articles SET title = ?, content = ?, photo_url = ?, tags = ?, status = ? WHERE id = ?");
                $stmt->execute([$_POST['title'], $_POST['content'], $photo, $_POST['tags'], $_POST['status'], $id]);
                break;

            case 'agenda':
                $pamphlet = upload_file('pamphlet_url', $target_dir) ?: $_POST['old_pamphlet'];
                $stmt = $db->prepare("UPDATE agenda SET title = ?, event_date = ?, event_time = ?, location = ?, pamphlet_url = ? WHERE id = ?");
                $stmt->execute([$_POST['title'], $_POST['event_date'], $_POST['event_time'], $_POST['location'], $pamphlet, $id]);
                break;

            case 'gallery':
                $photo = upload_file('photo_url', $target_dir) ?: $_POST['old_photo'];
                $stmt = $db->prepare("UPDATE gallery SET photo_url = ?, caption = ? WHERE id = ?");
                $stmt->execute([$photo, $_POST['caption'], $id]);
                break;

            case 'regulations':
                $file = upload_file('file_url', $target_dir) ?: $_POST['old_file'];
                $stmt = $db->prepare("UPDATE regulations SET title = ?, file_url = ?, category = ? WHERE id = ?");
                $stmt->execute([$_POST['title'], $file, $_POST['category'], $id]);
                break;

            case 'quotes':
                $stmt = $db->prepare("UPDATE quotes SET quote_text = ?, author = ? WHERE id = ?");
                $stmt->execute([$_POST['quote_text'], $_POST['author'], $id]);
                break;

            case 'supervisions':
                $photo = upload_file('photo_url', $target_dir) ?: $_POST['old_photo'];
                $stmt = $db->prepare("UPDATE supervisions SET title = ?, subtitle = ?, photo_url = ?, commission = ? WHERE id = ?");
                $stmt->execute([$_POST['title'], $_POST['subtitle'], $photo, $_POST['commission'], $id]);
                break;

            case 'organization_profile':
                $photo = upload_file('photo_url', $target_dir) ?: $_POST['old_photo'];
                $stmt = $db->prepare("UPDATE organization_profile SET section = ?, content = ?, photo_url = ? WHERE id = ?");
                $stmt->execute([$_POST['section'], $_POST['content'], $photo, $id]);
                break;
        }
        header("Location: admin_dashboard.php?menu=$type&status=success_update");
        exit;
    }

} else if ($_SERVER['REQUEST_METHOD'] === 'GET' && $action === 'delete') {
    
    // --- PROSES HAPUS DATA (DELETE) ---
    $id = $_GET['id'];
    
    // Hapus file fisik dari folder uploads jika kolom penyimpan file terdeteksi
    $kolom_file = ['articles' => 'photo_url', 'agenda' => 'pamphlet_url', 'gallery' => 'photo_url', 'regulations' => 'file_url', 'supervisions' => 'photo_url', 'organization_profile' => 'photo_url'];
    
    if (array_key_exists($type, $kolom_file)) {
        $kolom = $kolom_file[$type];
        $stmt_file = $db->prepare("SELECT $kolom FROM $type WHERE id = ?");
        $stmt_file->execute([$id]);
        $path_lama = $stmt_file->fetchColumn();
        if ($path_lama && file_exists($path_lama)) {
            unlink($path_lama);
        }
    }

    $stmt = $db->prepare("DELETE FROM $type WHERE id = ?");
    $stmt->execute([$id]);

    header("Location: admin_dashboard.php?menu=$type&status=success_delete");
    exit;
}
?>
