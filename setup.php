<?php
// setup.php

// 1. Atur lokasi folder privat dan file database SQLite di dalam web_dpm
$folder_db = __DIR__ . '/database_privat';
$path_db = $folder_db . '/dpm_fmipa-unesa.sqlite';

// 2. Buat folder 'database_privat' otomatis jika belum ada
if (!is_dir($folder_db)) {
    mkdir($folder_db, 0777, true);
    echo "✔ Folder 'database_privat/' berhasil dibuat.<br>";
}

try {
    // 3. Membuat atau membuka file database SQLite
    $db = new PDO("sqlite:" . $path_db);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✔ Koneksi database 'dpm_fmipa-unesa.sqlite' di folder web_dpm berhasil terjalin.<br><br>";

    // --- PEMBUATAN TABEL-TABEL ---

    // Tabel 1: users
    $db->exec("CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        username TEXT NOT NULL UNIQUE,
        password TEXT NOT NULL,
        role TEXT DEFAULT 'admin'
    )");
    echo "✔ Tabel 'users' siap.<br>";

    // Tabel 2: articles
    $db->exec("CREATE TABLE IF NOT EXISTS articles (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        title TEXT NOT NULL,
        content TEXT,
        photo_url TEXT,
        tags TEXT,
        status TEXT DEFAULT 'Draft',
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )");
    echo "✔ Tabel 'articles' siap.<br>";

    // Tabel 3: agenda
    $db->exec("CREATE TABLE IF NOT EXISTS agenda (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        title TEXT NOT NULL,
        event_date DATE,
        event_time TIME,
        location TEXT,
        pamphlet_url TEXT
    )");
    echo "✔ Tabel 'agenda' siap.<br>";

    // Tabel 4: gallery
    $db->exec("CREATE TABLE IF NOT EXISTS gallery (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        photo_url TEXT NOT NULL,
        caption TEXT,
        uploaded_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )");
    echo "✔ Tabel 'gallery' siap.<br>";

    // Tabel 5: regulations
    $db->exec("CREATE TABLE IF NOT EXISTS regulations (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        title TEXT NOT NULL,
        file_url TEXT,
        category TEXT,
        uploaded_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )");
    echo "✔ Tabel 'regulations' siap.<br>";

    // Tabel 6: quotes
    $db->exec("CREATE TABLE IF NOT EXISTS quotes (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        quote_text TEXT NOT NULL,
        author TEXT
    )");
    echo "✔ Tabel 'quotes' siap.<br>";

    // Tabel 7: supervisions
    $db->exec("CREATE TABLE IF NOT EXISTS supervisions (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        title TEXT NOT NULL,
        subtitle TEXT,
        photo_url TEXT,
        commission TEXT
    )");
    echo "✔ Tabel 'supervisions' siap.<br>";

    // Tabel 8: organization_profile
    $db->exec("CREATE TABLE IF NOT EXISTS organization_profile (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        section TEXT NOT NULL,
        content TEXT,
        photo_url TEXT
    )");
    echo "✔ Tabel 'organization_profile' siap.<br><br>";


    // --- INSERT DATA AKUN SEBAGAI MASTER ADMIN AWAL ---
    $checkUsers = $db->query("SELECT COUNT(*) FROM users")->fetchColumn();
    
    if ($checkUsers == 0) {
        $username_awal = 'admin_dpm';
        $password_mentah = 'DpmFmipa2026'; 
        
        // Hash bcrypt
        $password_hash = password_hash($password_mentah, PASSWORD_BCRYPT);
        $role_awal = 'superadmin';

        $stmt = $db->prepare("INSERT INTO users (username, password, role) VALUES (:username, :password, :role)");
        $stmt->bindParam(':username', $username_awal);
        $stmt->bindParam(':password', $password_hash);
        $stmt->bindParam(':role', $role_awal);
        $stmt->execute();

        echo "<div style='color: green; font-weight: bold;'>🎉 SELESAI! Seluruh tabel berhasil dibuat di proyek web_dpm.</div>";
        echo "<strong>Akun Admin Pertama Berhasil Dibuat:</strong><br>";
        echo "Username: <code>" . $username_awal . "</code><br>";
        echo "Password: <code>" . $password_mentah . "</code><br>";
    } else {
        echo "<div style='color: orange; font-weight: bold;'>⚠ Tabel sudah siap dan akun admin sudah ada sebelumnya.</div>";
    }

} catch (PDOException $e) {
    die("<div style='color: red; font-weight: bold;'>❌ Eror Pembuatan Database: " . $e->getMessage() . "</div>");
}
?>
