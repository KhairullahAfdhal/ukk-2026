-- create_peminjaman_table

CREATE TABLE IF NOT EXISTS `peminjaman` (
  `id_peminjaman` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `id_user` INT UNSIGNED NOT NULL,
  `id_buku` INT UNSIGNED NOT NULL,
  `jumlah` INT NOT NULL,
  `tanggal_pinjam` DATE NOT NULL,
  `tanggal_kembali` DATE DEFAULT NULL,
  `status` ENUM('Pending','Disetujui','Ditolak','Dipinjam','Di Kembalikan') DEFAULT 'Pending',
  `denda` INT DEFAULT 0,
  `created_at` DATETIME DEFAULT NULL,
  `updated_at` DATETIME DEFAULT NULL,
  
  -- Foreign Key ke tabel users (pastikan nama tabelnya 'users')
  CONSTRAINT `fk_peminjaman_user` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  
  -- Foreign Key ke tabel buku (tipe data harus INT UNSIGNED)
  CONSTRAINT `fk_peminjaman_buku` FOREIGN KEY (`id_buku`) REFERENCES `buku` (`id_buku`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;