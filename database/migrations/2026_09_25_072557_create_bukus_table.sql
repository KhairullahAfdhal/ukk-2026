-- create_bukus_table

CREATE TABLE IF NOT EXISTS `buku` (
    id_buku      INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nama_buku    VARCHAR(255) NOT NULL,
    kode_buku    VARCHAR(255) NOT NULL, 
    id_kategori  INT UNSIGNED NOT NULL, 
    created_at DATETIME NULL,
    updated_at DATETIME NULL,
    CONSTRAINT fk_buku_kategori FOREIGN KEY (id_kategori) REFERENCES kategori(id_kategori) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
