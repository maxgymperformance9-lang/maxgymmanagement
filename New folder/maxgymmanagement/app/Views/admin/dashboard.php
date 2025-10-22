
<?= $this->extend('templates/admin_page_layout') ?>
<?= $this->section('content') ?>
<div class="content">
    <div class="container-fluid">
        <!-- REKAP JUMLAH DATA -->
        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card card-stats">
                    <div class="card-header card-header-primary card-header-icon">
                        <div class="card-icon">
                            <a href="<?= base_url('admin/petugas'); ?>" class="text-white">
                                <i class="material-icons">person</i>
                            </a>
                        </div>
                        <p class="card-category">Jumlah Petugas</p>
                        <h3 class="card-title"><?= count($petugas); ?></h3>
                    </div>
                    <div class="card-footer">
                        <div class="stats">
                            <i class="material-icons text-primary">check</i>
                            Terdaftar
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card card-stats">
                    <div class="card-header card-header-info card-header-icon">
                        <div class="card-icon">
                            <a href="#" class="text-white">
                                <i class="material-icons">event_available</i>
                            </a>
                        </div>
                        <p class="card-category">Total Bookings</p>
                        <h3 class="card-title"><?= $bookingStats['totalBookings'] ?? 0 ?></h3>
                    </div>
                    <div class="card-footer">
                        <div class="stats">
                            <i class="material-icons text-info">today</i>
                            Today: <?= $bookingStats['todayBookings'] ?? 0 ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card card-stats">
                    <div class="card-header card-header-primary card-header-icon">
                        <div class="card-icon">
                            <a href="<?= base_url('admin/pegawai'); ?>" class="text-white">
                                <i class="material-icons">person_4</i>
                            </a>
                        </div>
                        <p class="card-category">Jumlah Pegawai</p>
                        <h3 class="card-title"><?= count($pegawai); ?></h3>
                    </div>
                    <div class="card-footer">
                        <div class="stats">
                            <i class="material-icons text-success">check</i>
                            Terdaftar
                        </div>
                    </div>
                </div>
            </div>
            <!--<div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card card-stats">
                    <div class="card-header card-header-primary card-header-icon">
                        <div class="card-icon">
                            <a href="<?= base_url('admin/di'); ?>" class="text-white">
                                <i class="material-icons">grade</i>
                            </a>
                        </div>
                        <p class="card-category">Jumlah D.I/WIL</p>
                        <h3 class="card-title"><?= count($di); ?></h3>
                    </div>
                    <div class="card-footer">
                        <div class="stats">
                            <i class="material-icons">home</i>
                            <?= $generalSettings->office_name; ?>
                        </div>
                    </div>
                </div>
            </div>-->

            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card card-stats">
                    <div class="card-header card-header-primary card-header-icon">
                        <div class="card-icon">
                            <a href="<?= base_url('admin/member'); ?>" class="text-white">
                                <i class="material-icons">group</i>
                            </a>
                        </div>
                        <p class="card-category">Jumlah Member</p>
                        <h3 class="card-title"><?= count($member ?? []); ?></h3>
                    </div>
                    <div class="card-footer">
                        <div class="stats">
                            <i class="material-icons text-info">group</i>
                            Terdaftar
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- MEMBERSHIP STATISTICS -->
        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card card-stats">
                    <div class="card-header card-header-success card-header-icon">
                        <div class="card-icon">
                            <a href="<?= base_url('admin/membership-packages'); ?>" class="text-white">
                                <i class="material-icons">card_membership</i>
                            </a>
                        </div>
                        <p class="card-category">Paket Aktif</p>
                        <h3 class="card-title"><?= $membershipStats['totalPackages']; ?></h3>
                    </div>
                    <div class="card-footer">
                        <div class="stats">
                            <i class="material-icons text-success">check_circle</i>
                            Paket Membership
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card card-stats">
                    <div class="card-header card-header-info card-header-icon">
                        <div class="card-icon">
                            <i class="material-icons">verified_user</i>
                        </div>
                        <p class="card-category">Membership Aktif</p>
                        <h3 class="card-title"><?= $membershipStats['activeMemberships']; ?></h3>
                    </div>
                    <div class="card-footer">
                        <div class="stats">
                            <i class="material-icons text-info">verified</i>
                            Member dengan paket
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card card-stats">
                    <div class="card-header card-header-warning card-header-icon">
                        <div class="card-icon">
                            <i class="material-icons">schedule</i>
                        </div>
                        <p class="card-category">Akan Kadaluarsa</p>
                        <h3 class="card-title"><?= $membershipStats['expiringMemberships']; ?></h3>
                    </div>
                    <div class="card-footer">
                        <div class="stats">
                            <i class="material-icons text-warning">warning</i>
                            Dalam 30 hari
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card card-stats">
                    <div class="card-header card-header-danger card-header-icon">
                        <div class="card-icon">
                            <i class="material-icons">cancel</i>
                        </div>
                        <p class="card-category">Membership Expired</p>
                        <h3 class="card-title"><?= $membershipStats['expiredMemberships']; ?></h3>
                    </div>
                    <div class="card-footer">
                        <div class="stats">
                            <i class="material-icons text-danger">error</i>
                            Perlu diperpanjang
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header card-header-primary">
                        <h4 class="card-title"><b>Absensi Pegawai Hari Ini</b></h4>
                        <p class="card-category"><?= $dateNow; ?></p>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-md-3">
                                <h4 class="text-primary"><b>Hadir</b></h4>
                                <h3><?= $jumlahKehadiranPegawai['hadir']; ?></h3>
                            </div>
                            <div class="col-md-3">
                                <h4 class="text-primary"><b>Sakit</b></h4>
                                <h3><?= $jumlahKehadiranPegawai['sakit']; ?></h3>
                            </div>
                            <div class="col-md-3">
                                <h4 class="text-primary"><b>Izin</b></h4>
                                <h3><?= $jumlahKehadiranPegawai['izin']; ?></h3>
                            </div>
                            <div class="col-md-3">
                                <h4 class="text-primary"><b>Alfa</b></h4>
                                <h3><?= $jumlahKehadiranPegawai['alfa']; ?></h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header card-header-success">
                        <h4 class="card-title"><b>Absensi Member Hari Ini</b></h4>
                        <p class="card-category"><?= $dateNow; ?></p>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-md-3">
                                <h4 class="text-success"><b>Hadir</b></h4>
                                <h3><?= $jumlahKehadiranMember['hadir']; ?></h3>
                            </div>
                            <div class="col-md-3">
                                <h4 class="text-success"><b>Sakit</b></h4>
                                <h3><?= $jumlahKehadiranMember['sakit']; ?></h3>
                            </div>
                            <div class="col-md-3">
                                <h4 class="text-success"><b>Izin</b></h4>
                                <h3><?= $jumlahKehadiranMember['izin']; ?></h3>
                            </div>
                            <div class="col-md-3">
                                <h4 class="text-success"><b>Alfa</b></h4>
                                <h3><?= $jumlahKehadiranMember['alfa']; ?></h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- GRAFIK CHART -->
        <div class="row">
            <div class="col-md-6">
                <div class="card card-chart">
                    <div class="card-header card-header-primary">
                        <div class="ct-chart" id="kehadiranPegawai"></div>
                    </div>
                    <div class="card-body">
                        <h4 class="card-title">Tingkat kehadiran Pegawai</h4>
                        <p class="card-category">Jumlah kehadiran pegawai dalam 7 hari terakhir</p>
                    </div>
                    <div class="card-footer">
                        <div class="stats">
                            <i class="material-icons text-success">checklist</i> <a class="text-success" href="<?= base_url('admin/absen-pegawai'); ?>">Lihat data</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card card-chart">
                    <div class="card-header card-header-success">
                        <div class="ct-chart" id="kehadiranMember"></div>
                    </div>
                    <div class="card-body">
                        <h4 class="card-title">Tingkat kehadiran Member</h4>
                        <p class="card-category">Jumlah kehadiran member dalam 7 hari terakhir</p>
                    </div>
                    <div class="card-footer">
                        <div class="stats">
                            <i class="material-icons text-success">checklist</i> <a class="text-success" href="<?= base_url('admin/absen-member'); ?>">Lihat data</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- TOMBOL SCAN FULLSCREEN -->
        <div class="row">
            <div class="col-md-6 text-center">
                <button id="btnScanFullscreen" class="btn btn-success btn-lg">
                    <i class="material-icons">fullscreen</i> Buka Scan Absensi di Layar Kedua
                </button>
            </div>
            <div class="col-md-6 text-center">
                <a href="<?= base_url('admin/email-templates') ?>" class="btn btn-primary btn-lg">
                    <i class="material-icons">email</i> Kelola Email Templates
                </a>
            </div>
        </div>
    </div>
</div>
<!-- Chartist JS -->
<script src="<?= base_url('assets/js/plugins/chartist.min.js') ?>"></script>

<script>
    $(document).ready(function() {
        initDashboardPageCharts();

        // Event listener untuk tombol scan fullscreen
        $('#btnScanFullscreen').on('click', function() {
            openScanOnPrimaryScreen();
        });
    });

    // Fungsi untuk membuka scan di layar utama
    function openScanOnPrimaryScreen() {
        const scanUrl = '<?= base_url("scan"); ?>';

        // Parameter untuk popup di layar utama dengan ukuran normal
        const popupParams = 'width=800,height=600,left=100,top=100,scrollbars=yes,resizable=yes';

        // Buka popup di layar utama
        const popup = window.open(scanUrl, 'scanWindow', popupParams);

        if (popup) {
            console.log('Scan window opened on primary monitor');
        } else {
            console.log('Failed to open scan window - popup blocker may be active');
        }
    }

    function initDashboardPageCharts() {

        if ($('#kehadiranPegawai').length != 0) {
            /* ----------==========     Chart tingkat kehadiran Pegawai    ==========---------- */
            const dataKehadiranPegawai = [<?php foreach ($grafikkKehadiranPegawai as $value) echo "$value,"; ?>];

            const chartKehadiranPegawai = {
                labels: [
                    <?php
                    foreach ($dateRange as  $value) {
                        echo "'$value',";
                    }
                    ?>
                ],
                series: [dataKehadiranPegawai]
            };

            var highestData = 0;

            dataKehadiranPegawai.forEach(e => {
                if (e >= highestData) {
                    highestData = e;
                }
            })

            const optionsChart = {
                lineSmooth: Chartist.Interpolation.cardinal({
                    tension: 0
                }),
                low: 0,
                high: highestData + (highestData / 4),
                chartPadding: {
                    top: 0,
                    right: 0,
                    bottom: 0,
                    left: 0
                }
            }

            var kehadiranPegawaiChart = new Chartist.Line('#kehadiranPegawai', chartKehadiranPegawai, optionsChart);

            md.startAnimationForLineChart(kehadiranPegawaiChart);
        }

        if ($('#kehadiranMember').length != 0) {
            /* ----------==========     Chart tingkat kehadiran Member    ==========---------- */
            const dataKehadiranMember = [<?php foreach ($grafikKehadiranMember as $value) echo "$value,"; ?>];

            const chartKehadiranMember = {
                labels: [
                    <?php
                    foreach ($dateRange as  $value) {
                        echo "'$value',";
                    }
                    ?>
                ],
                series: [dataKehadiranMember]
            };

            var highestDataMember = 0;

            dataKehadiranMember.forEach(e => {
                if (e >= highestDataMember) {
                    highestDataMember = e;
                }
            })

            const optionsChartMember = {
                lineSmooth: Chartist.Interpolation.cardinal({
                    tension: 0
                }),
                low: 0,
                high: highestDataMember + (highestDataMember / 4),
                chartPadding: {
                    top: 0,
                    right: 0,
                    bottom: 0,
                    left: 0
                }
            }

            var kehadiranMemberChart = new Chartist.Line('#kehadiranMember', chartKehadiranMember, optionsChartMember);

            md.startAnimationForLineChart(kehadiranMemberChart);
        }
    }
</script>
<?= $this->endSection() ?>
