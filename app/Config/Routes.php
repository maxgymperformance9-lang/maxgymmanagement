<?php

namespace Config;

use CodeIgniter\Router\RouteCollection;
use Config\Services;

/**
 * --------------------------------------------------------------------
 * Create a new instance of our RouteCollection class
 * --------------------------------------------------------------------
 */
$routes = Services::routes();

/**
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(false); // 🚫 Hindari Auto Routing Legacy untuk keamanan

/**
 * --------------------------------------------------------------------
 * Route Definitions
 * -------------------------------------------------------------------- */

// Default route
$routes->get('/', 'Scan::index');

// Test Email Route
$routes->get('test-email', 'TestEmail::index');
$routes->get('test-email/send-test', 'TestEmail::sendTest');

/**
 * --------------------------------------------------------------------
 * SCAN ROUTES
 * --------------------------------------------------------------------
 */
$routes->group('scan', static function (RouteCollection $routes) {
    $routes->get('', 'Scan::index');
    $routes->get('masuk', 'Scan::masuk');
    $routes->get('pulang', 'Scan::pulang');
    $routes->get('member', 'Scan::member');
    $routes->post('cek', 'Scan::cekKode');
    $routes->post('from-file', 'Scan::scanFromFile');
});

/**
 * --------------------------------------------------------------------
 * API ROUTES
 * --------------------------------------------------------------------
 */
$routes->group('api', ['namespace' => 'App\Controllers\Api'], static function (RouteCollection $routes) {
    $routes->post('open-door', 'DoorController::openDoor');
    $routes->post('access-door', 'DoorController::accessDoor');
    $routes->get('test-door', 'DoorController::testConnection');
});

/**
 * --------------------------------------------------------------------
 * ADMIN ROUTES
 * --------------------------------------------------------------------
 */
$routes->group('admin', ['namespace' => 'App\Controllers\Admin'], static function (RouteCollection $routes) {

    // Dashboard
    $routes->get('/', 'Dashboard::index');
    $routes->get('dashboard', 'Dashboard::index');

    /**
     * -------------------------
     * DATA INDUK (di)
     * -------------------------
     */
    $routes->group('di', static function ($routes) {
        $routes->get('/', 'DiController::index');
        $routes->get('tambah', 'DiController::tambahDi');
        $routes->post('tambahDiPost', 'DiController::tambahDiPost');
        $routes->get('edit/(:any)', 'DiController::editDi/$1');
        $routes->post('editDiPost', 'DiController::editDiPost');
        $routes->post('deleteDiPost', 'DiController::deleteDiPost');
        $routes->post('list-data', 'DiController::listData');
    });

    /**
     * -------------------------
     * WILAYAH
     * -------------------------
     */
    $routes->group('wilayah', static function ($routes) {
        $routes->get('/', 'WilayahController::index');
        $routes->get('tambah', 'WilayahController::tambahWilayah');
        $routes->post('tambahJurusanPost', 'WilayahController::tambahJurusanPost');
        $routes->get('edit/(:any)', 'WilayahController::editWilayah/$1');
        $routes->post('editWilayahPost', 'WilayahController::editWilayahPost');
        $routes->post('deleteWilayahPost', 'WilayahController::deleteWilayahPost');
        $routes->post('list-data', 'WilayahController::listData');
    });

    /**
     * -------------------------
     * PENJAGA
     * -------------------------
     */
    $routes->group('penjaga', static function ($routes) {
        $routes->get('/', 'DataPenjaga::index');
        $routes->post('/', 'DataPenjaga::ambilDataPenjaga');
        $routes->get('create', 'DataPenjaga::formTambahSiswa');
        $routes->post('create', 'DataPenjaga::saveSiswa');
        $routes->get('edit/(:any)', 'DataPenjaga::formEditSiswa/$1');
        $routes->post('edit', 'DataPenjaga::updatePenjaga');
        $routes->delete('delete/(:any)', 'DataPenjaga::delete/$1');
        $routes->get('bulk', 'DataPenjaga::bulkPostSiswa');

        // POST utilities
        $routes->post('downloadCSVFilePost', 'DataPenjaga::downloadCSVFilePost');
        $routes->post('generateCSVObjectPost', 'DataPenjaga::generateCSVObjectPost');
        $routes->post('importCSVItemPost', 'DataPenjaga::importCSVItemPost');
        $routes->post('deleteSelectedPenjaga', 'DataPenjaga::deleteSelectedPenjaga');
    });

    /**
     * -------------------------
     * PEGAWAI
     * -------------------------
     */
    $routes->group('pegawai', static function ($routes) {
        $routes->get('/', 'DataPegawai::index');
        $routes->post('/', 'DataPegawai::ambilDataPegawai');
        $routes->get('create', 'DataPegawai::formTambahPegawai');
        $routes->post('create', 'DataPegawai::savePegawai');
        $routes->get('edit/(:any)', 'DataPegawai::formEditGuru/$1');
        $routes->post('edit', 'DataPegawai::updatePegawai');
        $routes->delete('delete/(:any)', 'DataPegawai::delete/$1');
        $routes->post('upload-foto/(:any)', 'DataPegawai::uploadFoto/$1');
    });

    /**
     * -------------------------
     * MEMBER
     * -------------------------
     */
    $routes->group('member', static function ($routes) {
        $routes->get('/', 'DataMember::index');
        $routes->post('/', 'DataMember::ambilDataMember');
        $routes->get('create', 'DataMember::formTambahMember');
        $routes->post('create', 'DataMember::saveMember');
        $routes->get('edit/(:any)', 'DataMember::formEditMember/$1');
        $routes->post('edit', 'DataMember::updateMember');
        $routes->delete('delete/(:any)', 'DataMember::delete/$1');
        $routes->post('upload-foto/(:any)', 'DataMember::uploadFoto/$1');
        $routes->post('send-welcome-email/(:any)', 'DataMember::sendWelcomeEmail/$1');
        $routes->post('send-welcome-whatsapp/(:any)', 'DataMember::sendWelcomeWhatsApp/$1');
    });

    /**
     * -------------------------
     * ABSENSI (Penjaga, Pegawai, Member)
     * -------------------------
     */
    $routes->group('absen-penjaga', static function ($routes) {
        $routes->get('/', 'DataAbsenPenjaga::index');
        $routes->post('/', 'DataAbsenPenjaga::ambilDataPenjaga');
        $routes->post('kehadiran', 'DataAbsenPenjaga::ambilKehadiran');
        $routes->post('edit', 'DataAbsenPenjaga::ubahKehadiran');
        $routes->post('delete', 'DataAbsenPenjaga::deletePresensi');
    });

    $routes->group('absen-pegawai', static function ($routes) {
        $routes->get('/', 'DataAbsenPegawai::index');
        $routes->post('/', 'DataAbsenPegawai::ambilDataPegawai');
        $routes->post('kehadiran', 'DataAbsenPegawai::ambilKehadiran');
        $routes->post('edit', 'DataAbsenPegawai::ubahKehadiran');
        $routes->post('delete', 'DataAbsenPegawai::deletePresensi');
    });

    $routes->group('absen-member', static function ($routes) {
        $routes->get('/', 'DataAbsenMember::index');
        $routes->post('/', 'DataAbsenMember::ambilDataMember');
        $routes->post('kehadiran', 'DataAbsenMember::ambilKehadiran');
        $routes->post('edit', 'DataAbsenMember::ubahKehadiran');
        $routes->post('delete', 'DataAbsenMember::deletePresensi');
    });

    /**
     * -------------------------
     * QR GENERATOR
     * -------------------------
     */
    $routes->group('generate', static function ($routes) {
        $routes->get('/', 'GenerateQR::index');
        $routes->post('penjaga-by-di', 'GenerateQR::getPenjagaByDi');
        $routes->post('penjaga', 'QRGenerator::generateQrSiswa');
        $routes->post('pegawai', 'QRGenerator::generateQrGuru');
        $routes->post('member', 'QRGenerator::generateQrMember');
    });

    $routes->group('qr', static function ($routes) {
        $routes->get('penjaga/download', 'QRGenerator::downloadAllQrSiswa');
        $routes->get('penjaga/(:any)/download', 'QRGenerator::downloadQrSiswa/$1');
        $routes->get('pegawai/download', 'QRGenerator::downloadAllQrGuru');
        $routes->get('pegawai/(:any)/download', 'QRGenerator::downloadQrGuru/$1');
        $routes->get('member/download', 'QRGenerator::downloadAllQrMember');
        $routes->get('member/(:any)/download', 'QRGenerator::downloadQrMember/$1');
    });

    /**
     * -------------------------
     * LAPORAN
     * -------------------------
     */
    $routes->group('laporan', static function ($routes) {
        $routes->get('/', 'GenerateLaporan::index');
        $routes->post('penjaga', 'GenerateLaporan::generateLaporanPenjaga');
        $routes->post('pegawai', 'GenerateLaporan::generateLaporanPegawai');
        $routes->post('absensiMember', 'GenerateLaporan::generateLaporanAbsensiMember');
        $routes->post('absensiMemberPT', 'GenerateLaporan::generateLaporanAbsensiMemberPT');
        $routes->post('dataMember', 'GenerateLaporan::generateLaporanDataMember');
        $routes->post('transaksi', 'GenerateLaporan::generateLaporanTransaksi');
        $routes->post('keuangan', 'GenerateLaporan::generateLaporanKeuangan');
        $routes->post('stock', 'GenerateLaporan::generateLaporanStock');
    });

    /**
     * -------------------------
     * PENGELUARAN
     * -------------------------
     */
    $routes->group('pengeluaran', static function ($routes) {
        $routes->get('/', 'ExpenseController::index');
        $routes->post('data', 'ExpenseController::ambilDataPengeluaran');
        $routes->get('create', 'ExpenseController::create');
        $routes->post('store', 'ExpenseController::store');
        $routes->get('edit/(:segment)', 'ExpenseController::edit/$1');
        $routes->post('update/(:segment)', 'ExpenseController::update/$1');
        $routes->post('delete/(:segment)', 'ExpenseController::delete/$1');
    });

    /**
     * -------------------------
     * PETUGAS
     * -------------------------
     */
    $routes->group('petugas', static function ($routes) {
        $routes->get('/', 'DataPetugas::index');
        $routes->post('/', 'DataPetugas::ambilDataPetugas');
        $routes->get('register', 'DataPetugas::registerPetugas');
        $routes->get('edit/(:any)', 'DataPetugas::formEditPetugas/$1');
        $routes->post('edit', 'DataPetugas::updatePetugas');
        $routes->delete('delete/(:any)', 'DataPetugas::delete/$1');
    });

    /**
     * -------------------------
     * SETTINGS
     * -------------------------
     */
    $routes->group('general-settings', static function ($routes) {
        $routes->get('/', 'GeneralSettings::index');
        $routes->post('update', 'GeneralSettings::generalSettingsPost');
    });

    /**
     * --------------------------
     * PRODUK & KASIR
     * -------------------------
     */
    $routes->group('produk', static function ($routes) {
        $routes->get('/', 'KasirController::index');
        $routes->post('/', 'KasirController::getProducts');
        $routes->get('create', 'KasirController::createProduct');
        $routes->post('create', 'KasirController::storeProduct');
        $routes->get('edit/(:any)', 'KasirController::editProduct/$1');
        $routes->post('edit/(:any)', 'KasirController::updateProduct/$1');
        $routes->delete('delete/(:any)', 'KasirController::deleteProduct/$1');
    });

    $routes->group('kasir', static function ($routes) {
        $routes->get('/', 'KasirController::kasir');
        $routes->post('checkout', 'KasirController::checkout');
        $routes->get('transaksi', 'KasirController::transaksi');
        $routes->post('transaksi', 'KasirController::getTransactions');
        $routes->get('transaksi/view/(:any)', 'KasirController::viewTransaction/$1');
        $routes->get('receipt/(:any)', 'KasirController::printReceipt/$1');
    });

    /**
     * --------------------------
     * WAREHOUSE & STOCK MANAGEMENT
     * -------------------------
     */
    $routes->group('warehouse', static function ($routes) {
        $routes->get('/', 'WarehouseController::index');
        $routes->post('/', 'WarehouseController::getWarehouses');
        $routes->get('create', 'WarehouseController::create');
        $routes->post('store', 'WarehouseController::store');
        $routes->get('edit/(:num)', 'WarehouseController::edit/$1');
        $routes->post('update/(:num)', 'WarehouseController::update/$1');
        $routes->delete('delete/(:num)', 'WarehouseController::delete/$1');
        $routes->post('toggle-status/(:num)', 'WarehouseController::toggleStatus/$1');
        $routes->post('get-warehouses', 'WarehouseController::getWarehouses');
    });

    $routes->group('stock', static function ($routes) {
        $routes->get('/', 'StockController::index');
        $routes->post('/', 'StockController::getStocks');
        $routes->get('manage', 'StockController::manage');
        $routes->get('in', 'StockController::stockIn');
        $routes->post('in', 'StockController::stockIn');
        $routes->get('out', 'StockController::stockOut');
        $routes->post('out', 'StockController::stockOut');
        $routes->get('transfer', 'StockController::transfer');
        $routes->post('transfer', 'StockController::transfer');
        $routes->get('movements', 'StockController::movements');
        $routes->post('movements', 'StockController::getMovements');
        $routes->post('by-warehouse', 'StockController::getStockByWarehouse');
        $routes->post('low-stock-alerts', 'StockController::getLowStockAlerts');
        $routes->get('get-warehouses', 'StockController::getWarehouses');
        $routes->get('get-products', 'StockController::getProducts');
    });

    /**
     * --------------------------
     * RFID MANAGEMENT
     * -------------------------
     */
    $routes->group('rfid', static function ($routes) {
        $routes->get('/', 'RfidController::index');
        $routes->get('create', 'RfidController::create');
        $routes->post('store', 'RfidController::store');
        $routes->get('edit/(:num)', 'RfidController::edit/$1');
        $routes->post('update/(:num)', 'RfidController::update/$1');
        $routes->delete('delete/(:num)', 'RfidController::delete/$1');
        $routes->post('assign-member/(:num)', 'RfidController::assignMember/$1');
        $routes->post('unassign-member/(:num)', 'RfidController::unassignMember/$1');
        $routes->post('topup/(:num)', 'RfidController::topup/$1');
        $routes->post('change-status/(:num)', 'RfidController::changeStatus/$1');
        $routes->get('transactions', 'RfidController::transactions');
        $routes->get('transactions/(:num)', 'RfidController::transactions/$1');

        // API routes for RFID operations
        $routes->get('api/card-info/(:any)', 'RfidController::getCardInfo/$1');
        $routes->post('api/process-payment', 'RfidController::processPayment');
    });



    $routes->group('fitness-classes', static function ($routes) {
        $routes->get('/', 'FitnessClassController::index');
        $routes->get('create', 'FitnessClassController::create');
        $routes->post('store', 'FitnessClassController::store');
        $routes->get('edit/(:num)', 'FitnessClassController::edit/$1');
        $routes->post('update/(:num)', 'FitnessClassController::update/$1');
        $routes->delete('delete/(:num)', 'FitnessClassController::delete/$1');
        $routes->post('ajax-list', 'FitnessClassController::ajaxList');
        $routes->post('toggle-status/(:num)', 'FitnessClassController::toggleStatus/$1');
    });

    $routes->group('class-schedules', static function ($routes) {
        $routes->get('/', 'ClassScheduleController::index');
        $routes->get('create', 'ClassScheduleController::create');
        $routes->post('store', 'ClassScheduleController::store');
        $routes->get('edit/(:num)', 'ClassScheduleController::edit/$1');
        $routes->post('update/(:num)', 'ClassScheduleController::update/$1');
        $routes->delete('delete/(:num)', 'ClassScheduleController::delete/$1');
        $routes->get('view-bookings/(:num)', 'ClassScheduleController::viewBookings/$1');
        $routes->post('update-status/(:num)', 'ClassScheduleController::updateStatus/$1');
        $routes->get('calendar', 'ClassScheduleController::calendar');
        $routes->get('calendar-data', 'ClassScheduleController::getCalendarData');
    });

    /**
     * -------------------------
     * EMAIL TEMPLATES
     * -------------------------
     */
    $routes->group('email-templates', static function ($routes) {
        $routes->get('/', 'EmailTemplateController::index');
        $routes->get('preview/(:segment)', 'EmailTemplateController::preview/$1');
    });
});

/**
 * --------------------------------------------------------------------
 * AUTH ROUTES (Password Reset)
 * --------------------------------------------------------------------
 */
$routes->group('', static function (RouteCollection $routes) {
    $routes->get('forgot-password', 'AuthController::forgotPassword');
    $routes->post('forgot-password', 'AuthController::attemptForgot');
    $routes->get('reset-password', 'AuthController::resetPassword');
    $routes->post('reset-password', 'AuthController::attemptReset');
    $routes->get('reset-password/(:segment)', 'AuthController::resetPassword/$1');
});

/**
 * --------------------------------------------------------------------
 * MEMBER ROUTES
 * --------------------------------------------------------------------
 */
$routes->group('member', static function (RouteCollection $routes) {
    // Direct member dashboard route
    $routes->get('dashboard', 'MemberBookingController::index');

    $routes->group('booking', static function ($routes) {
        $routes->get('dashboard', 'MemberBookingController::index');
        $routes->get('available-classes', 'MemberBookingController::availableClasses');
        $routes->post('book', 'MemberBookingController::bookClass');
        $routes->post('cancel', 'MemberBookingController::cancelBooking');
        $routes->get('details/(:num)', 'MemberBookingController::bookingDetails/$1');
        $routes->get('stats', 'MemberBookingController::bookingStats');
    });
});

/**
 * --------------------------------------------------------------------
 * Include Environment Specific Routes
 * --------------------------------------------------------------------
 */
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
