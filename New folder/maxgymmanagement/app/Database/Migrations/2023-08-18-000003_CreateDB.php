<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDB extends Migration
{
    public function up()
    {
        $this->forge->getConnection()->query("CREATE TABLE tb_kehadiran (
            id_kehadiran int(11) NOT NULL,
            kehadiran ENUM('Hadir', 'Sakit', 'Izin', 'Tanpa keterangan') NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

        $this->forge->getConnection()->query("INSERT INTO tb_kehadiran (id_kehadiran, kehadiran) VALUES
            (1, 'Hadir'),
            (2, 'Sakit'),
            (3, 'Izin'),
            (4, 'Tanpa keterangan');");

        $this->forge->getConnection()->query("INSERT INTO tb_wilayah (wilayah) VALUES
            ('OTKP'),
            ('BDP'),
            ('AKL'),
            ('RPL');");

        $this->forge->getConnection()->query("INSERT INTO tb_di (di, id_wilayah) VALUES
            ('X', 1),
            ('X', 2),
            ('X', 3),
            ('X', 4),
            ('XI', 1),
            ('XI', 2),
            ('XI', 3),
            ('XI', 4),
            ('XII', 1),
            ('XII', 2),
            ('XII', 3),
            ('XII', 4);");

        $this->forge->getConnection()->query("CREATE TABLE tb_pegawai (
            id_pegawai int(11) NOT NULL,
            nip varchar(24) NOT NULL,
            nama_pegawai varchar(255) NOT NULL,
            jenis_kelamin ENUM('Laki-laki','Perempuan') NOT NULL,
            alamat text NOT NULL,
            no_hp varchar(32) NOT NULL,
            unique_code varchar(64) NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

        $this->forge->getConnection()->query("CREATE TABLE tb_presensi_pegawai (
            id_presensi int(11) NOT NULL,
            id_pegawai int(11) DEFAULT NULL,
            tanggal date NOT NULL,
            jam_masuk time DEFAULT NULL,
            jam_keluar time DEFAULT NULL,
            id_kehadiran int(11) NOT NULL,
            keterangan varchar(255) NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
                        ");

        $this->forge->getConnection()->query("CREATE TABLE tb_penjaga (
            id_penjaga int(11) NOT NULL,
            nip varchar(16) NOT NULL,
            nama_penjaga varchar(255) NOT NULL,
            id_di int(11) UNSIGNED NOT NULL,
            jenis_kelamin ENUM('Laki-laki','Perempuan') NOT NULL,
            no_hp varchar(32) NOT NULL,
            unique_code varchar(64) NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

        $this->forge->getConnection()->query("CREATE TABLE tb_presensi_penjaga (
            id_presensi int(11) NOT NULL,
            id_penjaga int(11) NOT NULL,
            id_di int(11) UNSIGNED DEFAULT NULL,
            tanggal date NOT NULL,
            jam_masuk time DEFAULT NULL,
            jam_keluar time DEFAULT NULL,
            id_kehadiran int(11) NOT NULL,
            keterangan varchar(255) NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

        $this->forge->getConnection()->query("ALTER TABLE tb_pegawai
            ADD PRIMARY KEY (id_pegawai),
            ADD UNIQUE KEY unique_code (unique_code);");

        $this->forge->getConnection()->query("ALTER TABLE tb_kehadiran
            ADD PRIMARY KEY (id_kehadiran);");

        $this->forge->getConnection()->query("ALTER TABLE tb_presensi_pegawai
            ADD PRIMARY KEY (id_presensi),
            ADD KEY id_kehadiran (id_kehadiran),
            ADD KEY id_pegawai (id_pegawai);");

        $this->forge->getConnection()->query("ALTER TABLE tb_presensi_penjaga
            ADD PRIMARY KEY (id_presensi),
            ADD KEY id_penjaga (id_penjaga),
            ADD KEY id_kehadiran (id_kehadiran),
            ADD KEY id_di (id_di);");

        $this->forge->getConnection()->query("ALTER TABLE tb_penjaga
            ADD PRIMARY KEY (id_penjaga),
            ADD UNIQUE KEY unique_code (unique_code),
            ADD KEY id_di (id_di);");

        $this->forge->getConnection()->query("ALTER TABLE tb_pegawai
            MODIFY id_pegawai int(11) NOT NULL AUTO_INCREMENT;");

        $this->forge->getConnection()->query("ALTER TABLE tb_kehadiran
            MODIFY id_kehadiran int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;");

        $this->forge->getConnection()->query("ALTER TABLE tb_presensi_pegawai
            MODIFY id_presensi int(11) NOT NULL AUTO_INCREMENT;");

        $this->forge->getConnection()->query("ALTER TABLE tb_presensi_penjaga
            MODIFY id_presensi int(11) NOT NULL AUTO_INCREMENT;");

        $this->forge->getConnection()->query("ALTER TABLE tb_penjaga
            MODIFY id_penjaga int(11) NOT NULL AUTO_INCREMENT;");

        $this->forge->getConnection()->query("ALTER TABLE tb_presensi_pegawai
            ADD CONSTRAINT tb_presensi_guru_ibfk_2 FOREIGN KEY (id_kehadiran) REFERENCES tb_kehadiran (id_kehadiran),
            ADD CONSTRAINT tb_presensi_guru_ibfk_3 FOREIGN KEY (id_pegawai) REFERENCES tb_pegawai (id_pegawai) ON DELETE SET NULL;");

        $this->forge->getConnection()->query("ALTER TABLE tb_presensi_penjaga
            ADD CONSTRAINT tb_presensi_siswa_ibfk_2 FOREIGN KEY (id_kehadiran) REFERENCES tb_kehadiran (id_kehadiran),
            ADD CONSTRAINT tb_presensi_siswa_ibfk_3 FOREIGN KEY (id_penjaga) REFERENCES tb_penjaga (id_penjaga) ON DELETE CASCADE,
            ADD CONSTRAINT tb_presensi_siswa_ibfk_4 FOREIGN KEY (id_di) REFERENCES tb_di (id_di) ON DELETE SET NULL ON UPDATE CASCADE;");

        $this->forge->getConnection()->query("ALTER TABLE tb_penjaga
            ADD CONSTRAINT tb_siswa_ibfk_1 FOREIGN KEY (id_di) REFERENCES tb_di (id_di);");
    }

    public function down()
    {
        $tables = [
            'tb_presensi_penjaga',
            'tb_presensi_pegawai',
            'tb_penjaga',
            'tb_pegawai',
            'tb_kehadiran',
        ];

        foreach ($tables as $table) {
            $this->forge->dropTable($table);
        }
    }
}
