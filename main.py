import os
import sqlite3
from datetime import datetime

# 1. Pastikan folder 'database/' sudah ada, jika belum maka buat otomatis
folder_db = "database"
if not os.path.exists(folder_db):
    os.makedirs(folder_db)
    print(f"Folder '{folder_db}/' berhasil dibuat.")

# Path lengkap ke file database
path_db = os.path.join(folder_db, "dpm_fmipa_unesa.sqlite")

# 2. Membuat koneksi ke database lokal
conn = sqlite3.connect(path_db)
cursor = conn.cursor()

# 3. Membuat Tabel: articles
cursor.execute('''
    CREATE TABLE IF NOT EXISTS articles (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        title TEXT,
        slug TEXT,
        category TEXT,
        author TEXT,
        thumbnail TEXT,
        content TEXT,
        created_at DATETIME
    )
''')

# 4. Membuat Tabel: announcements
cursor.execute('''
    CREATE TABLE IF NOT EXISTS announcements (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        title TEXT,
        content TEXT,
        created_at DATETIME
    )
''')

# 5. Membuat Tabel: agendas
cursor.execute('''
    CREATE TABLE IF NOT EXISTS agendas (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        title TEXT,
        description TEXT,
        event_date DATETIME,
        status TEXT
    )
''')

# 6. Membuat Tabel: regulations
cursor.execute('''
    CREATE TABLE IF NOT EXISTS regulations (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        title TEXT,
        description TEXT,
        file_path TEXT
    )
''')

# 7. Memasukkan contoh data dummy (opsional, agar database tidak kosong)
waktu_sekarang = datetime.now().strftime('%Y-%m-%d %H:%M:%S')

# Contoh isi artikel
cursor.execute('''
    INSERT INTO articles (title, slug, category, author, thumbnail, content, created_at)
    VALUES (?, ?, ?, ?, ?, ?, ?)
''', (
    'Muskerwil DPM FMIPA UNESA 2026', 
    'muskerwil-dpm-fmipa-unesa-2026', 
    'Berita', 
    'Admin DPM', 
    'muskerwil.jpg', 
    'Isi konten berita muskerwil...', 
    waktu_sekarang
))

# Contoh isi pengumuman
cursor.execute('''
    INSERT INTO announcements (title, content, created_at)
    VALUES (?, ?, ?)
''', ('Open Recruitment Anggota', 'Pendaftaran dibuka mulai besok...', waktu_sekarang))

# Simpan perubahan (COMMIT) dan tutup koneksi
conn.commit()
conn.close()

print(f"Sukses! Database berhasil dibuat di: {path_db}")

