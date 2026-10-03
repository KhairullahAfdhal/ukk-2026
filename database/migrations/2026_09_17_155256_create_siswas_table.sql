-- create_siswas_table

CREATE TABLE IF NOT EXISTS `siswa` (
    id_siswa   INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_user    INT UNSIGNED NOT NULL,
    nama_siswa VARCHAR(255) NOT NULL,
    nis        VARCHAR(20) NOT NULL,
    kelas      VARCHAR(10) NOT NULL,
    created_at DATETIME NULL,
    updated_at DATETIME NULL,
    CONSTRAINT fk_siswa_user FOREIGN KEY (id_user) REFERENCES user(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
