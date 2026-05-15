CREATE TABLE kendaraan (
    id_kendaraan SERIAL PRIMARY KEY,
    jenis VARCHAR(50) NOT NULL,
    merk VARCHAR(50),
    tahun INT CHECK (tahun >= 2000 AND tahun <= EXTRACT(YEAR FROM CURRENT_DATE)),
    harga_sewaPerHari INT NOT NULL CHECK (harga_sewaPerHari > 0),
    status_tersedia BOOLEAN NOT NULL DEFAULT TRUE
);

CREATE TABLE pelanggan (
    id_pelanggan SERIAL PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    no_telp VARCHAR(20) NOT NULL CHECK (no_telp ~ '^[0-9+]+$'),
    alamat TEXT
);

CREATE TABLE rental (
    id_rental SERIAL PRIMARY KEY,
    id_kendaraan INT REFERENCES kendaraan(id_kendaraan) ON DELETE CASCADE,
    id_pelanggan INT REFERENCES pelanggan(id_pelanggan) ON DELETE CASCADE,
    tanggal_mulai DATE NOT NULL CHECK (tanggal_selesai IS NULL OR tanggal_selesai >= tanggal_mulai),
    tanggal_selesai DATE,
    total_biaya INT NOT NULL DEFAULT 0 CHECK (total_biaya >= 0),
    status_rental VARCHAR(20) NOT NULL CHECK (status_rental IN ('berlangsung', 'selesai')),
    keterangan TEXT
);



INSERT INTO kendaraan VALUES
(DEFAULT, 'Mobil', 'Toyota Fortuner', 2022, 650000, DEFAULT),
(DEFAULT, 'Mobil', 'Honda CRV', 2020, 600000, DEFAULT),
(DEFAULT, 'Mobil', 'Tesla Model 3', 2023, 1200000, DEFAULT),
(DEFAULT, 'Motor', 'Kawasaki Ninja 250', 2019, 250000, DEFAULT),
(DEFAULT, 'Motor', 'Yamaha R25', 2021, 230000, DEFAULT),
(DEFAULT, 'Mobil', 'Mitsubishi Pajero', 2021, 700000, FALSE),
(DEFAULT, 'Motor', 'Honda PCX', 2022, 150000, DEFAULT),
(DEFAULT, 'Motor', 'Yamaha XSR 155', 2020, 200000, DEFAULT),
(DEFAULT, 'Mobil', 'BMW X1', 2023, 1500000, DEFAULT),
(DEFAULT, 'Motor', 'Ducati Scrambler', 2023, 350000, DEFAULT);

INSERT INTO pelanggan (nama, no_telp, alamat) VALUES
('Fadhil Mahesa', '081290001110', 'Bandung'),
('Rizky Ramadhan', '081288877766', 'Jakarta Timur'),
('Naila Fauziyyah', '082111554433', 'Yogyakarta'),
('Rendra Saputra', '081233445566', 'Depok'),
('Siska Amelia', '082233449900', 'Bekasi'),
('Wulandari Prameswari', '085712345678', 'Bogor'),
('Adit Prakoso', '085677889900', 'Cikarang');

INSERT INTO rental (id_kendaraan, id_pelanggan, tanggal_mulai, tanggal_selesai, status_rental, keterangan) VALUES
(1, 1, '2024-02-10', '2024-02-12', 'selesai', 'Sewa lama'),
(4, 6, '2024-05-01', '2024-05-03', 'selesai', 'Dipakai touring'),
(2, 2, '2025-01-10', '2025-01-13', 'selesai', 'Paket 3 hari'),
(7, 3, '2025-01-15', NULL, 'berlangsung', 'Masih digunakan'),
(9, 4, '2025-01-20', '2025-01-22', 'selesai', 'Luxury rental'),
(5, 1, '2025-01-25', NULL, 'berlangsung', 'Motor sport'),
(3, 1, '2025-01-28', '2025-01-29', 'selesai', 'Sewa Tesla'),
(10, 1, '2025-02-01', NULL, 'berlangsung', 'Ducati premium');

SELECT * FROM kendaraan;
SELECT * FROM pelanggan;
SELECT * FROM rental;

SELECT 
    r.id_rental,
    p.nama AS nama_pelanggan,
    k.jenis AS jenis_kendaraan,
    k.merk AS merk_kendaraan,
    r.tanggal_mulai,
    r.tanggal_selesai,
    r.status_rental,
    r.keterangan,
    r.total_biaya
FROM rental r
JOIN pelanggan p ON r.id_pelanggan = p.id_pelanggan
JOIN kendaraan k ON r.id_kendaraan = k.id_kendaraan
ORDER BY r.id_rental;