-- =====================================================================
--  AUBE MUSIC / "spotify" veritabanı şeması
--  phpMyAdmin veya MySQL istemcisinde çalıştır.
--  Kolon isimleri koddaki kullanımla birebir eşleşir.
-- =====================================================================

CREATE DATABASE IF NOT EXISTS spotify
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE spotify;

-- ---------------------------------------------------------------------
--  Kullanıcılar (login.php, register.php, profil.php)
--  sifre kolonu HASH sakladığı için mutlaka VARCHAR(255).
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS kullanicilar (
    id             INT AUTO_INCREMENT PRIMARY KEY,
    kullanici_adi  VARCHAR(50)  NOT NULL,
    ad             VARCHAR(50)  NOT NULL,
    soyad          VARCHAR(50)  NOT NULL,
    email          VARCHAR(120) NOT NULL,
    sifre          VARCHAR(255) NOT NULL,          -- password_hash() çıktısı
    olusturma      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_kullanici_adi (kullanici_adi),
    UNIQUE KEY uq_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
--  Müzikler (anasayfaveri.php, arama.php, deneme.php, dashboard/*)
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS muzik (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    sarki_adi  VARCHAR(150) NOT NULL,
    sarkici    VARCHAR(150) NOT NULL,
    album      VARCHAR(150) DEFAULT NULL,
    turu       VARCHAR(80)  DEFAULT NULL,
    yol        VARCHAR(255) NOT NULL,              -- mp3 dosya yolu
    kapak      VARCHAR(255) DEFAULT NULL,          -- kapak görseli yolu
    olusturma  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
--  Dinleme geçmişi (deneme.php içine INSERT ediliyor)
--  İleride kullanıcıya bağlamak için kullanici_id eklendi (opsiyonel).
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS gecmis (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    kullanici_id  INT DEFAULT NULL,
    sarki_adi     VARCHAR(150) NOT NULL,
    sarkici       VARCHAR(150) NOT NULL,
    yol           VARCHAR(255) NOT NULL,
    kapak         VARCHAR(255) DEFAULT NULL,
    dinlenme      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    KEY idx_kullanici (kullanici_id),
    CONSTRAINT fk_gecmis_kullanici
        FOREIGN KEY (kullanici_id) REFERENCES kullanicilar(id)
        ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
--  (Opsiyonel) Beğenilenler — begenilenler.html'i veritabanına
--  bağlamak istersen hazır dursun.
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS begeniler (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    kullanici_id  INT NOT NULL,
    muzik_id      INT NOT NULL,
    olusturma     TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_begeni (kullanici_id, muzik_id),
    CONSTRAINT fk_begeni_kullanici
        FOREIGN KEY (kullanici_id) REFERENCES kullanicilar(id) ON DELETE CASCADE,
    CONSTRAINT fk_begeni_muzik
        FOREIGN KEY (muzik_id) REFERENCES muzik(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
