-- =====================================================================
--  Çalma listeleri için ek tablolar. phpMyAdmin > SQL'de çalıştır.
-- =====================================================================
USE spotify;

CREATE TABLE IF NOT EXISTS calma_listeleri (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    kullanici_id  INT NOT NULL,
    ad            VARCHAR(120) NOT NULL,
    olusturma     TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    KEY idx_kullanici (kullanici_id),
    CONSTRAINT fk_liste_kullanici
        FOREIGN KEY (kullanici_id) REFERENCES kullanicilar(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS liste_sarkilari (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    liste_id   INT NOT NULL,
    muzik_id   INT NOT NULL,
    eklenme    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_liste_muzik (liste_id, muzik_id),
    CONSTRAINT fk_ls_liste FOREIGN KEY (liste_id) REFERENCES calma_listeleri(id) ON DELETE CASCADE,
    CONSTRAINT fk_ls_muzik FOREIGN KEY (muzik_id) REFERENCES muzik(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
