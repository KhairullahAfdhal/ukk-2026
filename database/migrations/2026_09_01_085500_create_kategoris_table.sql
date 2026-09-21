-- create_kategoris_table

CREATE TABLE IF NOT EXISTS `kategoris` (
    id_kategori  INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    keterangan  VARCHAR(255) NOT NULL,
    created_at DATETIME NULL,
    updated_at DATETIME NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
