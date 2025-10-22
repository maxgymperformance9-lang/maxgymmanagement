<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <link rel="apple-touch-icon" sizes="76x76" href="<?= base_url('assets/img/apple-icon.png') ?>">
    <link rel="icon" type="image/png" href="<?= base_url('assets/img/favicon.png') ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>
        <?= $title ?? 'MAXGYM Member Portal' ?> | MAXGYM
    </title>
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />

    <!-- Fonts and icons -->
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">

    <!-- CSS Files -->
    <link href="<?= base_url('assets/css/material-dashboard.css?v=2.1.0') ?>" rel="stylesheet" />
    <link href="<?= base_url('assets/css/custom.css') ?>" rel="stylesheet" />

    <!-- CSRF Token -->
    <meta name="csrf-token" content="<?= csrf_hash() ?>">
</head>

<body class="">
    <div class="wrapper ">
        <!-- Sidebar -->
        <div class="sidebar" data-color="purple" data-background-color="white">
            <div class="logo">
                <a href="#" class="simple-text logo-mini">
                    MG
                </a>
                <a href="#" class="simple-text logo-normal">
                    MAXGYM
                </a>
            </div>
            <div class="sidebar-wrapper">
                <ul class="nav">
                    <li class="nav-item <?= uri_string() == 'member/booking/dashboard' ? 'active' : '' ?>">
                        <a class="nav-link" href="<?= base_url('member/booking/dashboard') ?>">
                            <i class="material-icons">dashboard</i>
                            <p>Dashboard</p>
                        </a>
                    </li>
                    <li class="nav-item <?= uri_string() == 'member/booking/dashboard' ? 'active' : '' ?>">
                        <a class="nav-link" href="<?= base_url('member/booking/dashboard') ?>">
                            <i class="material-icons">event_available</i>
                            <p>My Bookings</p>
                        </a>
                    </li>
                    <li class="nav-item <?= strpos(uri_string(), 'member/booking/available-classes') !== false ? 'active' : '' ?>">
                        <a class="nav-link" href="<?= base_url('member/booking/available-classes') ?>">
                            <i class="material-icons">fitness_center</i>
                            <p>Book Classes</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="material-icons">schedule</i>
                            <p>Class Schedule</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="material-icons">account_circle</i>
                            <p>My Profile</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="material-icons">payment</i>
                            <p>Payment History</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="material-icons">lock</i>
                            <p>Locker Access</p>
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Main Panel -->
        <div class="main-panel">
            <!-- Navbar -->
            <nav class="navbar navbar-expand-lg navbar-transparent navbar-absolute fixed-top ">
                <div class="container-fluid">
                    <div class="navbar-wrapper">
                        <div class="navbar-minimize">
                            <button id="minimizeSidebar" class="btn btn-just-icon btn-white btn-fab btn-round">
                                <i class="material-icons text_align-center">more_vert</i>
                            </button>
                        </div>
                        <a class="navbar-brand" href="#pablo"><?= $title ?? 'Dashboard' ?></a>
                    </div>
                    <button class="navbar-toggler" type="button" data-toggle="collapse" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="navbar-toggler-icon icon-bar"></span>
                        <span class="navbar-toggler-icon icon-bar"></span>
                        <span class="navbar-toggler-icon icon-bar"></span>
                    </button>
                    <div class="collapse navbar-collapse justify-content-end">
                        <ul class="navbar-nav">
                            <li class="nav-item dropdown">
                                <a class="nav-link" href="#" id="navbarDropdownProfile" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="material-icons">person</i>
                                    <p class="d-lg-none d-md-block">
                                        Account
                                    </p>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownProfile">
                                    <a class="dropdown-item" href="#">Profile</a>
                                    <a class="dropdown-item" href="#">Settings</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="#">Log out</a>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>

            <!-- Main Content -->
            <div class="content">
                <?= $this->renderSection('content') ?>
            </div>

            <!-- Footer -->
            <footer class="footer">
                <div class="container-fluid">
                    <nav class="float-left">
                        <ul>
                            <li>
                                <a href="#">
                                    MAXGYM
                                </a>
                            </li>
                        </ul>
                    </nav>
                    <div class="copyright float-right">
                        &copy; <?= date('Y') ?> MAXGYM Management System
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <!-- Core JS Files -->
    <script src="<?= base_url('assets/js/core/jquery.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/core/popper.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/core/bootstrap-material-design.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/plugins/perfect-scrollbar.jquery.min.js') ?>"></script>

    <!-- Plugin for the momentJs  -->
    <script src="<?= base_url('assets/js/plugins/moment.min.js') ?>"></script>

    <!-- Plugin for Sweet Alert -->
    <script src="<?= base_url('assets/js/plugins/sweetalert2.js') ?>"></script>

    <!-- Forms Validations Plugin -->
    <script src="<?= base_url('assets/js/plugins/jquery.validate.min.js') ?>"></script>

    <!-- Plugin for the Wizard, full documentation here: https://github.com/VinceG/twitter-bootstrap-wizard -->
    <script src="<?= base_url('assets/js/plugins/jquery.bootstrap-wizard.js') ?>"></script>

    <!-- Plugin for Select, full documentation here: http://silviomoreto.github.io/bootstrap-select -->
    <script src="<?= base_url('assets/js/plugins/bootstrap-selectpicker.js') ?>"></script>

    <!-- Plugin for the DateTimePicker, full documentation here: https://eonasdan.github.io/bootstrap-datetimepicker/ -->
    <script src="<?= base_url('assets/js/plugins/bootstrap-datetimepicker.min.js') ?>"></script>

    <!-- DataTables.net Plugin, full documentation here: https://datatables.net/  -->
    <script src="<?= base_url('assets/js/plugins/jquery.dataTables.min.js') ?>"></script>

    <!-- Plugin for Tags, full documentation here: https://github.com/bootstrap-tagsinput/bootstrap-tagsinput  -->
    <script src="<?= base_url('assets/js/plugins/bootstrap-tagsinput.js') ?>"></script>

    <!-- Plugin for Fileupload, full documentation here: http://www.jasny.net/bootstrap/javascript/#fileinput -->
    <script src="<?= base_url('assets/js/plugins/jasny-bootstrap.min.js') ?>"></script>

    <!-- Full Calendar Plugin, full documentation here: https://github.com/fullcalendar/fullcalendar    -->
    <script src="<?= base_url('assets/js/plugins/fullcalendar.min.js') ?>"></script>

    <!-- Vector Map plugin, full documentation here: http://jvectormap.com/documentation/ -->
    <script src="<?= base_url('assets/js/plugins/jquery-jvectormap.js') ?>"></script>

    <!-- Plugin for the Sliders, full documentation here: http://refreshless.com/nouislider/ -->
    <script src="<?= base_url('assets/js/plugins/nouislider.min.js') ?>"></script>

    <!-- Include a polyfill for ES6 Promises (optional) for IE11, UC Browser and Android browser support SweetAlert -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/core-js/2.4.1/core.js"></script>

    <!-- Library for adding dinamically elements -->
    <script src="<?= base_url('assets/js/plugins/arrive.min.js') ?>"></script>

    <!-- Chartist JS -->
    <script src="<?= base_url('assets/js/plugins/chartist.min.js') ?>"></script>

    <!-- Notifications Plugin -->
    <script src="<?= base_url('assets/js/plugins/bootstrap-notify.js') ?>"></script>

    <!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
    <script src="<?= base_url('assets/js/material-dashboard.js?v=2.1.0') ?>" type="text/javascript"></script>

    <!-- Material Dashboard DEMO methods, don't include it in your project! -->
    <script src="<?= base_url('assets/js/demo.js') ?>"></script>
</body>
</html>
