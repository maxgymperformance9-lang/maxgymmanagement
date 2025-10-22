<?php
$db = mysqli_connect('localhost', 'root', '', 'db_absensi');
$result = mysqli_query($db, 'SELECT id_member, nama_member, id_package, tanggal_bergabung, tanggal_kadaluarsa, status_membership, sisa_pt_sessions FROM tb_members WHERE id_member = 6');
$row = mysqli_fetch_assoc($result);
print_r($row);
