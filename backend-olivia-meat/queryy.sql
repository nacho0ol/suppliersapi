-- Tabel User
CREATE TABLE user (
    id_user INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(50) NOT NULL,
    username VARCHAR(20) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    CONSTRAINT chk_nama CHECK (LENGTH(nama) >= 3 AND nama REGEXP '^[a-zA-Z][a-zA-Z ]*$'),
    CONSTRAINT chk_username CHECK (username REGEXP '^[a-zA-Z_]+$')
);

-- Tabel Pelanggan
CREATE TABLE pelanggan (
    id_pelanggan INT AUTO_INCREMENT PRIMARY KEY,
    nama_pelanggan VARCHAR(100) NOT NULL,
    no_telp VARCHAR(15) NOT NULL,
    alamat TEXT NOT NULL,
    is_deleted TINYINT(1) DEFAULT 0,
    CONSTRAINT chk_nama_pelanggan CHECK (LENGTH(nama_pelanggan) >= 3 AND nama_pelanggan REGEXP '^[a-zA-Z][a-zA-Z ]*$'),
    CONSTRAINT chk_no_telp CHECK (no_telp REGEXP '^08[0-9]{8,13}$'),
    CONSTRAINT chk_alamat CHECK (LENGTH(alamat) >= 5)
);

-- Tabel Produk
CREATE TABLE produk (
    id_produk INT AUTO_INCREMENT PRIMARY KEY,
    nama_produk VARCHAR(50) NOT NULL,
    kategori ENUM('Daging', 'Tulang', 'Buntut', 'Jeroan', 'Kaki') NOT NULL,
    harga_awal INT NOT NULL,
    harga_jual INT NOT NULL,
    nama_jagal VARCHAR(50) NOT NULL,
    is_deleted TINYINT(1) DEFAULT 0,
    CONSTRAINT chk_nama_produk CHECK (LENGTH(nama_produk) >= 3 AND nama_produk REGEXP '^[a-zA-Z0-9 ]+$'),
    CONSTRAINT chk_harga_awal CHECK (harga_awal > 1000 AND harga_awal <= 1000000),
    CONSTRAINT chk_harga_jual CHECK (harga_jual >= harga_awal AND harga_jual <= 1000000),
    CONSTRAINT chk_nama_jagal CHECK (LENGTH(nama_jagal) >= 3 AND nama_jagal REGEXP '^[a-zA-Z ]+$')
);

-- Tabel Pesanan
CREATE TABLE pesanan (
    id_pesanan VARCHAR(20) PRIMARY KEY,
    id_pelanggan INT NOT NULL,
    id_user INT NOT NULL,
    tgl_order DATE NOT NULL,
    metode_bayar ENUM('Tunai', 'Transfer', 'Tempo') NOT NULL,
    status_bayar ENUM('Unpaid', 'DP', 'Lunas') NOT NULL,
    tgl_antar DATE NOT NULL,
    status_pemesanan ENUM('Diterima', 'Diproses', 'Dikirim', 'Ditolak', 'Selesai') NOT NULL,
    is_deleted TINYINT(1) DEFAULT 0,
    CONSTRAINT fk_pesanan_pelanggan FOREIGN KEY (id_pelanggan) REFERENCES pelanggan(id_pelanggan),
    CONSTRAINT fk_pesanan_user FOREIGN KEY (id_user) REFERENCES user(id_user),
    CONSTRAINT chk_id_pesanan CHECK (id_pesanan LIKE 'ORD-%'),
    CONSTRAINT chk_tgl_order CHECK (YEAR(tgl_order) >= 2015),
    CONSTRAINT chk_tgl_antar CHECK (YEAR(tgl_antar) >= 2015)
);

-- Tabel Pesanan Detail
CREATE TABLE pesanan_detail (
    id_detail INT(11) AUTO_INCREMENT PRIMARY KEY,
    id_pesanan VARCHAR(20) NOT NULL,
    id_produk INT(11) NOT NULL,
    qty DECIMAL(3,2) NOT NULL,
    harga_jual_saat_ini INT NOT NULL,
    subtotal INT GENERATED ALWAYS AS (qty * harga_jual_saat_ini) STORED,
    is_deleted TINYINT(1) DEFAULT 0,
    CONSTRAINT fk_detail_pesanan FOREIGN KEY (id_pesanan) REFERENCES pesanan(id_pesanan),
    CONSTRAINT fk_detail_produk FOREIGN KEY (id_produk) REFERENCES produk(id_produk),
    CONSTRAINT chk_qty CHECK (qty > 0),
    CONSTRAINT chk_harga_jual_saat_ini CHECK (harga_jual_saat_ini > 1000 AND harga_jual_saat_ini <= 1000000)
);

-- Tabel Piutang
CREATE TABLE piutang (
    id_pesanan VARCHAR(20) PRIMARY KEY,
    tgl_jatuh_tempo DATE NOT NULL,
    total_tagihan INT NOT NULL,
    jumlah_terbayar INT DEFAULT 0,
    status_piutang ENUM('Belum Lunas', 'Sebagian', 'Lunas') NOT NULL,
    is_deleted TINYINT(1) DEFAULT 0,
    CONSTRAINT fk_piutang_pesanan FOREIGN KEY (id_pesanan) REFERENCES pesanan(id_pesanan),
    CONSTRAINT chk_total_tagihan CHECK (total_tagihan > 0 AND total_tagihan <= 100000000),
    CONSTRAINT chk_jumlah_terbayar CHECK (jumlah_terbayar >= 0 AND jumlah_terbayar <= total_tagihan)
);

-- Tabel Pengeluaran Harian
CREATE TABLE pengeluaran_harian (
    id_pengeluaran INT AUTO_INCREMENT PRIMARY KEY,
    tgl_pengeluaran DATE NOT NULL,
    kategori_pengeluaran ENUM('Makan', 'Pembelian Daging', 'Operasional', 'Transportasi', 'Lain-lain') NOT NULL,
    id_user INT NOT NULL,
    nominal INT NOT NULL,
    keterangan TEXT NOT NULL,
    is_deleted TINYINT(1) DEFAULT 0,
    CONSTRAINT fk_pengeluaran_user FOREIGN KEY (id_user) REFERENCES user(id_user),
    CONSTRAINT chk_nominal CHECK (nominal > 1000 AND nominal <= 100000000),
    CONSTRAINT chk_keterangan CHECK (LENGTH(keterangan) >= 5)
);

ALTER TABLE user
ADD COLUMN is_deleted TINYINT(1) DEFAULT 0;

ALTER TABLE pesanan
CHANGE COLUMN is_deleted is_dibatalkan TINYINT(1) DEFAULT 0;


ALTER TABLE pesanan
MODIFY COLUMN status_pemesanan ENUM('Diterima', 'Diproses', 'Dikirim', 'Ditolak', 'Selesai', 'Dibatalkan') NOT NULL;

-- 1. Insert User (Admin)
INSERT INTO user (nama, username, password) 
VALUES ('Budi Santoso', 'budiadmin', 'passwordhash123');

-- 2. Insert Pelanggan (Ingat no telp harus '08...')
INSERT INTO pelanggan (nama_pelanggan, no_telp, alamat) 
VALUES ('Warung Sate Pak Eko', '081234567890', 'Jl. Ringroad Selatan No 12');

-- 3. Insert Produk (Harga Jual harus >= Harga Awal)
INSERT INTO produk (nama_produk, kategori, harga_awal, harga_jual, nama_jagal) 
VALUES 
('Daging Sirloin', 'Daging', 85000, 115000, 'RPH Giwangan'),
('Buntut Sapi', 'Buntut', 70000, 90000, 'RPH Giwangan');

-- 4. Insert Pesanan (Header)
-- Asumsi ID user = 1, ID pelanggan = 1
INSERT INTO pesanan (id_pesanan, id_pelanggan, id_user, tgl_order, metode_bayar, status_bayar, tgl_antar, status_pemesanan) 
VALUES ('ORD-20260809-01', 1, 1, '2026-08-09', 'Tempo', 'Unpaid', '2026-08-10', 'Diproses');

-- 5. Insert Pesanan Detail (Isi Keranjang)
-- Subtotal otomatis dihitung DB 
INSERT INTO pesanan_detail (id_pesanan, id_produk, qty, harga_jual_saat_ini) 
VALUES 
('ORD-20260809-01', 1, 5, 115000), -- Beli 5kg Sirloin
('ORD-20260809-01', 2, 2, 90000);  -- Beli 2kg Buntut

-- 6. Insert Piutang (Karena metode bayarnya Tempo)
-- Total tagihan = (5 * 115k) + (2 * 90k) = 755.000
INSERT INTO piutang (id_pesanan, tgl_jatuh_tempo, total_tagihan, jumlah_terbayar, status_piutang) 
VALUES ('ORD-20260809-01', '2026-08-16', 755000, 0, 'Belum Lunas');

INSERT INTO pelanggan (nama_pelanggan, no_telp, alamat) 
VALUES ('Soto Ayam Pak Min', '0912345678', 'Jl. Gejayan');

INSERT INTO produk (nama_produk, kategori, harga_awal, harga_jual, nama_jagal) 
VALUES ('Daging Tetelan', 'Daging', 50000, 45000, 'RPH Segar');

DELETE FROM produk WHERE id_produk = 1;
UPDATE produk SET is_deleted = 1 WHERE id_produk = 1;

DELETE FROM pelanggan WHERE id_pelanggan = 1;
UPDATE pelanggan SET is_deleted = 1 WHERE id_pelanggan = 1;
UPDATE pesanan SET id_pesanan = 'ORD-REVISI-01' WHERE id_pesanan = 'ORD-20260809-01';
UPDATE pesanan SET status_pemesanan = 'Dibatalkan' WHERE id_pesanan = 'ORD-20260809-01';


-- Tabel Pengaturan Sistem (Untuk Notifikasi & Template)
CREATE TABLE pengaturan_sistem (
    id_pengaturan INT AUTO_INCREMENT PRIMARY KEY,
    h_minus_notifikasi INT NOT NULL DEFAULT 1, -- Contoh: 3 untuk H-3
    template_tagihan TEXT NOT NULL,
    is_notif_aktif TINYINT(1) DEFAULT 1, -- Fitur on/off bot Telegram
    CONSTRAINT chk_h_minus CHECK (h_minus_notifikasi >= 0 AND h_minus_notifikasi <= 30),
    CONSTRAINT chk_template CHECK (LENGTH(template_tagihan) > 10)
);

-- Insert Data Default 
INSERT INTO pengaturan_sistem (h_minus_notifikasi, template_tagihan, is_notif_aktif)
VALUES (
    3,
    'Halo [NAMA_PELANGGAN], ini pengingat dari Olivia Meat N Fresh. Tagihan Anda untuk pesanan [ID_PESANAN] sebesar Rp[TOTAL_TAGIHAN] akan jatuh tempo pada [TGL_JATUH_TEMPO]. Mohon segera diselesaikan ya!', 
    1
);