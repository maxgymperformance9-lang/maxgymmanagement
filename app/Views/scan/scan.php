<?= $this->extend('templates/starting_page_layout'); ?>

<?= $this->section('navaction') ?>
<?php if ((!isset($_GET['fullscreen']) || $_GET['fullscreen'] != 'true') && $mode !== 'member'): ?>
<a href="<?= base_url('/admin'); ?>" class="btn btn-primary pull-right pl-3">
  <i class="material-icons mr-2">dashboard</i> Dashboard
</a>
<?php endif; ?>
<?= $this->endSection() ?>

<?= $this->section('content'); ?>
<?php $oppBtn = ($waktu == 'Masuk') ? 'pulang' : 'masuk'; ?>

<style>
/* ===== ANIMASI SCAN LINE HIJAU ===== */
.scanner-container {
  position: relative;
  max-width: 300px;
  width: 100%;
  height: 300px;
  margin: 0 auto;
  overflow: hidden;
  border-radius: 10px;
  border: 3px solid #4caf50;
  box-shadow: 0 0 20px rgba(0, 0, 0, 0.4);
  background: #000;
}

/* Fullscreen styles untuk layar kedua */
body.fullscreen-active {
  background: #000 !important;
  margin: 0 !important;
  padding: 0 !important;
  height: 100vh !important;
  display: flex !important;
  align-items: center !important;
  justify-content: center !important;
}

body.fullscreen-active .bg {
  display: none !important;
}

body.fullscreen-active nav.navbar {
  display: none !important;
}

body.fullscreen-active .main-panel {
  width: 100% !important;
  height: 100vh !important;
  margin: 0 !important;
  padding: 0 !important;
  background: #000 !important;
}

body.fullscreen-active .content {
  padding: 0 !important;
}

body.fullscreen-active .container-fluid {
  padding: 0 !important;
}

body.fullscreen-active .row {
  margin: 0 !important;
}

body.fullscreen-active .col-lg-3,
body.fullscreen-active .col-xl-4 {
  display: none !important;
}

body.fullscreen-active .col-lg-6,
body.fullscreen-active .col-xl-4 {
  flex: 1 !important;
  max-width: 100% !important;
}

body.fullscreen-active .card {
  height: 100vh !important;
  border-radius: 0 !important;
  box-shadow: none !important;
  background: #000 !important;
}

body.fullscreen-active .card-body {
  height: calc(100vh - 80px) !important;
  display: flex !important;
  flex-direction: column !important;
  justify-content: center !important;
  align-items: center !important;
}

body.fullscreen-active .scanner-container {
  max-width: 400px !important;
  height: 400px !important;
}

body.fullscreen-active h3,
body.fullscreen-active h4,
body.fullscreen-active h5,
body.fullscreen-active p,
body.fullscreen-active .form-text {
  color: #fff !important;
}

body.fullscreen-active .btn {
  background: rgba(255, 255, 255, 0.1) !important;
  border: 1px solid #fff !important;
  color: #fff !important;
}

body.fullscreen-active .btn:hover {
  background: rgba(255, 255, 255, 0.2) !important;
}

body.fullscreen-active .alert {
  background: rgba(0, 0, 0, 0.8) !important;
  border: 1px solid #4caf50 !important;
  color: #fff !important;
}

body.fullscreen-active .card-header {
  background: rgba(0, 0, 0, 0.8) !important;
  border-bottom: 1px solid #4caf50 !important;
}

body.fullscreen-active .card-header-primary {
  background: rgba(0, 0, 0, 0.8) !important;
}

.scan-line {
  position: absolute;
  top: 0;
  left: 0;
  height: 3px;
  width: 100%;
  background: limegreen;
  box-shadow: 0 0 20px limegreen;
  animation: scanMove 2.2s infinite;
  display: none;
}

@keyframes scanMove {
  0% { top: 0%; }
  50% { top: 95%; }
  100% { top: 0%; }
}

#previewKamera {
  width: 100%;
  border-radius: 10px;
  display: none;
}

#previewGambar {
  max-width: 100%;
  display: none;
  margin: 10px auto;
  border-radius: 10px;
}

#hasilScan {
  transition: all 0.4s ease;
}
</style>

<?php if ($mode !== 'member'): ?>
<div class="main-panel">
  <div class="content">
    <div class="container-fluid">
      <div class="row mx-auto">

        <!-- TIPS -->
        <div class="col-lg-3 col-xl-4">
          <div class="card">
            <div class="card-body">
              <h3 class="mt-2"><b>Tips</b></h3>
              <ul class="pl-3">
                <li>Pastikan kamera jelas dan stabil</li>
                <li>Gunakan pencahayaan cukup</li>
                <li>QR dari gambar sebaiknya fokus dan tajam</li>
              </ul>
            </div>
          </div>

          <!-- DATA MEMBER -->
          <div class="card mt-3">
            <div class="card-body">
              <h3 class="mt-2"><b>Data Member</b></h3>
              <div id="userData" class="text-center">
                <p class="text-muted">Data akan muncul setelah scan QR</p>
              </div>
            </div>
          </div>

          <script>
          // Auto redirect back to scan page after 5 seconds - only when scan is successful
          let scanSuccessful = false;

          function startAutoRedirect() {
             if (scanSuccessful) {
                setTimeout(function() {
                   window.location.href = '<?= base_url("scan/" . strtolower($waktu)); ?>';
                }, 5000);
             }
          }

          // Function to mark scan as successful
          function markScanSuccessful() {
             scanSuccessful = true;
             startAutoRedirect();
          }
          </script>
        </div>
<?php else: ?>
<!-- MEMBER MODE: Full screen centered layout -->
<div class="main-panel" style="background: #000; min-height: 100vh;">
  <div class="content" style="padding: 0;">
    <div class="container-fluid" style="padding: 0;">
      <div class="row mx-auto justify-content-center align-items-center" style="min-height: 100vh;">
<?php endif; ?>

        <!-- SCANNER -->
        <div class="col-lg-6 col-xl-4">
          <div class="card">
            <div class="col-10 mx-auto card-header card-header-primary">
              <div class="row">
                <div class="col">
                  <h4 class="card-title"><b>Absen <?= $waktu; ?></b></h4>
                  <p class="card-category">Tunjukkan QR ke kamera / upload gambar</p>
                </div>
                <div class="col-md-auto">
                  <a href="<?= base_url("scan/$oppBtn"); ?>" 
                     class="btn btn-<?= $oppBtn == 'masuk' ? 'success' : 'warning'; ?>">
                    Absen <?= $oppBtn; ?>
                  </a>
                </div>
              </div>
            </div>

            <div class="card-body my-auto px-4">
              
              <!-- PILIH KAMERA -->
              <div class="mb-3">
                <h5 class="d-inline">Pilih kamera</h5>
                <select id="pilihKamera" class="custom-select w-50 ml-2" style="height:35px;"></select>
                <button id="refreshKamera" class="btn btn-sm btn-outline-primary ml-2">
                  <i class="material-icons" style="font-size:16px;">refresh</i>
                </button>
              </div>

              <!-- AREA SCANNER -->
              <div class="scanner-container mb-3">
                <video id="previewKamera" autoplay playsinline></video>
                <img id="previewGambar" alt="Preview Gambar QR">
                <div class="scan-line" id="scanLine"></div>
              </div>

              <!-- PILIH METODE SCAN -->
              <div class="mt-3">
                <h5>Pilih metode scan:</h5>
                <div class="btn-group btn-group-toggle d-flex" data-toggle="buttons">
                  <label class="btn btn-outline-secondary" id="btnScanner">
                    <input type="radio" name="scanMethod" value="scanner" checked> 📱 Scanner USB
                  </label>
                  <label class="btn btn-outline-primary" id="btnKamera">
                    <input type="radio" name="scanMethod" value="camera"> 📷 Kamera
                  </label>
                </div>
              </div>

              <!-- AREA KAMERA -->
              <div id="cameraSection" class="mt-3">
                <!-- Scanner container di atas digunakan untuk kamera -->
              </div>




              <!-- UPLOAD GAMBAR -->
              <div class="mt-3">
                <h5>Atau upload gambar QR:</h5>
                <input type="file" id="uploadGambar" accept="image/*" class="form-control-file">
                <small class="form-text text-muted">Pilih file berisi QR code (PNG/JPG).</small>
              </div>

              <div class="text-center mt-3">
                <button id="kembaliKamera" class="btn btn-outline-success btn-sm" style="display:none;">
                  <i class="material-icons" style="font-size:16px;">camera</i> Kembali ke Kamera
                </button>
              </div>

              <div id="hasilScan" class="mt-4"></div>
            </div>
          </div>
        </div>

<?php if ($mode !== 'member'): ?>
        <!-- PETUNJUK -->
        <div class="col-lg-3 col-xl-4">
          <div class="card">
            <div class="card-body">
              <h3 class="mt-2"><b>Petunjuk</b></h3>
              <ul class="pl-3">
                <li>Jika QR berhasil dibaca, data akan muncul di bawah.</li>
                <li>Gunakan Dashboard untuk melihat hasil absensi.</li>
              </ul>
            </div>
          </div>

          <!-- FOTO MEMBER -->
          <div class="card mt-3">
            <div class="card-body">
              <h3 class="mt-2"><b>Foto Member</b></h3>
              <div id="userPhoto" class="text-center">
                <?php
                $defaultPhoto = 'assets/img/default-avatar.png';
                if (file_exists(FCPATH . $defaultPhoto)): ?>
                  <img src="<?= base_url($defaultPhoto); ?>"
                       alt="Default Photo"
                       class="img-fluid rounded mx-auto d-block"
                       style="width: auto; height: 300px; max-width: 100%; object-fit: contain; border-radius: 10px;">
                <?php else: ?>
                  <div class="bg-secondary rounded mx-auto d-flex align-items-center justify-content-center"
                       style="width: 300px; height: 300px; border-radius: 10px;">
                    <i class="material-icons text-white" style="font-size: 100px;">person</i>
                  </div>
                <?php endif; ?>
              </div>
            </div>
          </div>
        </div>
<?php endif; ?>

      </div>
    </div>
  </div>
</div>

<!-- LIBRARY -->
<script src="<?= base_url('assets/js/core/jquery-3.5.1.min.js'); ?>"></script>
<script src="<?= base_url('assets/js/plugins/zxing/zxing.min.js'); ?>"></script>
<script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.min.js"></script>

<script>
let selectedDeviceId = null;
let soundPlayed = false; // Flag to prevent sound from playing multiple times

// Function to play audio files
function playAudio(filename) {
  if (soundPlayed) return; // Prevent multiple plays

  const audio = new Audio('<?= base_url('assets/audio/'); ?>' + filename);
  audio.play().catch(e => {
    console.log('Audio play failed:', e);
  });
  soundPlayed = true;

  // Reset flag after audio ends
  audio.onended = function() {
    soundPlayed = false;
  };
}

const codeReader = new ZXing.BrowserMultiFormatReader();
const sourceSelect = $('#pilihKamera');
const video = document.getElementById('previewKamera');
const imgPreview = document.getElementById('previewGambar');
const scanLine = document.getElementById('scanLine');
const kembaliBtn = document.getElementById('kembaliKamera');

/* === INISIALISASI KAMERA === */
function initScanner() {
  console.log('Initializing camera scanner...');

  // Check if camera section is visible
  if ($('#cameraSection').is(':hidden')) {
    console.log('Camera section is hidden, skipping camera initialization');
    return;
  }

  // Clear any previous messages
  $('#hasilScan').html('');

  // Request camera permission first
  navigator.mediaDevices.getUserMedia({ video: true })
    .then(stream => {
      console.log('Camera permission granted');
      // Stop the test stream
      stream.getTracks().forEach(track => track.stop());

      // Now list devices
      return codeReader.listVideoInputDevices();
    })
    .then(videoInputDevices => {
      console.log('Available video devices:', videoInputDevices);

      sourceSelect.html('');
      if (videoInputDevices.length === 0) {
        $('#hasilScan').html('<div class="alert alert-warning">⚠️ Tidak ada kamera yang terdeteksi. Pastikan kamera terhubung dan browser memiliki izin akses kamera.</div>');
        return;
      }

      videoInputDevices.forEach((device, index) => {
        const option = new Option(device.label || `Camera ${index + 1}`, device.deviceId);
        sourceSelect.append(option);
      });

      selectedDeviceId = selectedDeviceId || (videoInputDevices[0] ? videoInputDevices[0].deviceId : null);
      if (!selectedDeviceId) {
        $('#hasilScan').html('<div class="alert alert-danger">❌ Kamera tidak dapat dipilih.</div>');
        return;
      }

      console.log('Using camera device:', selectedDeviceId);

      video.style.display = 'block';
      imgPreview.style.display = 'none';
      scanLine.style.display = 'none';
      kembaliBtn.style.display = 'none';

      $('#hasilScan').html('<div class="alert alert-info">📷 Memulai kamera...</div>');

      codeReader.decodeFromVideoDevice(selectedDeviceId, 'previewKamera', (result, err) => {
        if (result) {
          console.log('QR code detected from camera:', result.text);
          $('#hasilScan').html('<div class="alert alert-success">✅ QR Code terdeteksi!</div>');
          // Audio will be played by the result view
          cekData(result.text);
        }
        if (err && !(err instanceof ZXing.NotFoundException)) {
          console.error('Camera error:', err);
          $('#hasilScan').html('<div class="alert alert-danger">Error kamera: ' + err.message + '</div>');
        }
      })
      .then(() => {
        console.log('Camera started successfully');
        $('#hasilScan').html('<div class="alert alert-success">📷 Kamera aktif - tunjukkan QR code ke kamera</div>');
      })
      .catch(decodeErr => {
        console.error('Decode error:', decodeErr);
        $('#hasilScan').html('<div class="alert alert-danger">Gagal memulai pemindaian kamera: ' + decodeErr.message + '</div>');
      });
    })
    .catch(err => {
      console.error('Camera access error:', err);
      if (err.name === 'NotAllowedError') {
        $('#hasilScan').html('<div class="alert alert-warning">⚠️ Izin akses kamera ditolak. Klik ikon kamera di address bar browser dan izinkan akses kamera.</div>');
      } else if (err.name === 'NotFoundError') {
        $('#hasilScan').html('<div class="alert alert-warning">⚠️ Kamera tidak ditemukan. Pastikan kamera terhubung ke komputer.</div>');
      } else {
        $('#hasilScan').html('<div class="alert alert-danger">Tidak dapat mengakses kamera: ' + err.message + '. Coba refresh halaman atau gunakan scanner USB.</div>');
      }
    });
}

/* === EVENT GANTI KAMERA === */
$('#pilihKamera').on('change', function() {
  selectedDeviceId = $(this).val();
  codeReader.reset();
  initScanner();
});

$('#refreshKamera').on('click', function() {
  codeReader.reset();
  selectedDeviceId = null;
  initScanner();
});

/* === SCAN DARI GAMBAR === */
$('#uploadGambar').on('change', function(e) {
  const file = e.target.files[0];
  if (!file) return;

  codeReader.reset();

  const reader = new FileReader();
  reader.onload = function(evt) {
    imgPreview.src = evt.target.result;
    imgPreview.style.display = 'block';
    video.style.display = 'none';
    scanLine.style.display = 'block';
    kembaliBtn.style.display = 'inline-block';
    $('#hasilScan').html('<div class="alert alert-info">⏳ Memindai gambar...</div>');

    const img = new Image();
    img.onload = function() {
      const canvas = document.createElement('canvas');
      const ctx = canvas.getContext('2d');
      canvas.width = img.width;
      canvas.height = img.height;
      ctx.drawImage(img, 0, 0);

      const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
      const code = jsQR(imageData.data, canvas.width, canvas.height, { inversionAttempts: "attemptBoth" });

      scanLine.style.display = 'none';

      if (code) {
        $('#hasilScan').html('<div class="alert alert-success">✅ QR Berhasil Dibaca!</div>');
        cekData(code.data);
      } else {
        $('#hasilScan').html('<div class="alert alert-danger">❌ Gagal mendeteksi QR. Pastikan gambar jelas & fokus.</div>');
      }
    };
    img.src = evt.target.result;
  };
  reader.readAsDataURL(file);
});

/* === KEMBALI KE KAMERA === */
$('#kembaliKamera').on('click', function() {
  $('#uploadGambar').val('');
  codeReader.reset();
  initScanner();
});

/* === INPUT DARI SCANNER EKSTERNAL === */
$('#scannerInput').on('focus', function() {
  $('#scannerStatus').html('🔍 Siap menerima input dari scanner...').show();
});

$('#scannerInput').on('blur', function() {
  $('#scannerStatus').hide();
});

// Buffer untuk mengumpulkan input scanner
let scannerBuffer = '';
let scannerTimeout;

// Multiple event listeners untuk scanner USB (EPPO EP5770)
$('#scannerInput').on('input change paste keyup', function(e) {
  const inputValue = $(this).val().trim();

  // Jika ada input, tambahkan ke buffer
  if (inputValue) {
    scannerBuffer += inputValue;
    console.log('Event:', e.type, 'Current buffer:', JSON.stringify(scannerBuffer));

    // Clear timeout sebelumnya
    clearTimeout(scannerTimeout);

    // Set timeout untuk memproses buffer setelah 500ms tidak ada input baru
    scannerTimeout = setTimeout(() => {
      processScannerBuffer();
    }, 500);

    // Clear input field untuk input berikutnya
    $(this).val('');
  } else {
    // Reset sound flag jika input kosong
    soundPlayed = false;
  }
});

// Fungsi untuk memproses buffer scanner
function processScannerBuffer() {
  if (scannerBuffer.length === 0) return;

  console.log('Processing buffer:', JSON.stringify(scannerBuffer));

  // Bersihkan input dari karakter non-alphanumeric
  const cleanInput = scannerBuffer.replace(/[^a-zA-Z0-9]/g, '');
  console.log('Cleaned buffer:', JSON.stringify(cleanInput));

  // Reset buffer
  scannerBuffer = '';

  if (cleanInput.length >= 16) { // Minimal panjang unique_code
    $('#scannerStatus').html('✅ Kode QR terdeteksi - memproses...').show();

    // Proses data seperti kamera
    cekData(cleanInput);

    // Sembunyikan status setelah 2 detik
    setTimeout(() => {
      $('#scannerStatus').fadeOut();
    }, 2000);
  } else {
    console.log('Buffer too short, ignoring');
  }
}

// Fallback untuk Enter jika diperlukan
$('#scannerInput').on('keydown', function(e) {
  if (e.key === 'Enter' || e.keyCode === 13) {
    e.preventDefault();
    const code = $(this).val().trim();
    if (code) {
      $('#scannerStatus').html('✅ Kode QR diproses').show();
      cekData(code);
      $(this).val('');
      setTimeout(() => {
        $('#scannerStatus').fadeOut();
      }, 2000);
    }
  }
});

/* === KIRIM DATA QR KE SERVER === */
function cekData(code) {
  console.log('Sending code to server:', code); // Debug log
  $.ajax({
    url: "<?= base_url('scan/cek'); ?>",
    type: 'POST',
    data: {
      'unique_code': code,
      'waktu': '<?= strtolower($waktu); ?>'
    },
    success: function(response) {
      console.log('Server response:', response); // Debug log
      $('#hasilScan').html(response);
      $('html, body').animate({ scrollTop: $("#hasilScan").offset().top }, 400);

      // Extract user data from response and display in sidebar
      updateUserDisplay(response);
    },
    error: function(xhr, status, error) {
      console.error('AJAX Error:', error);
      console.error('Status:', status);
      console.error('XHR:', xhr);
      $('#hasilScan').html('<div class="alert alert-danger">Terjadi kesalahan koneksi ke server.</div>');
    }
  });
}

/* === UPDATE USER DISPLAY IN SIDEBAR === */
function updateUserDisplay(response) {
  // Parse the inserted HTML in #hasilScan instead of re-parsing response
  const hasilScanDiv = document.getElementById('hasilScan');

  // Check if this is a successful scan (has success message) or error
  const successMessage = hasilScanDiv.querySelector('h2.text-success');
  const errorMessage = hasilScanDiv.querySelector('h3.text-danger');

  if (successMessage) {
    // This is a successful scan - play success sound and mark as successful for auto redirect
    playAudio('check in success.mp3');
    markScanSuccessful();
  } else if (errorMessage) {
    // This is an error - play appropriate error sound
    const errorText = errorMessage.textContent;
    if (errorText.includes('Anda sudah absen hari ini')) {
      playAudio('anda sudah chek in.mp3');
    } else if (errorText.includes('Masa member telah habis')) {
      playAudio('beep.mp3');
      setTimeout(() => playAudio('member habis.mp3'), 1000); // Play second audio after 1 second
    } else {
      playAudio('beep.mp3');
    }
  }

  // Only update sidebar if not in member mode
  <?php if ($mode !== 'member'): ?>
  // Try to find user data in the hidden div within the inserted content
  const hiddenDiv = hasilScanDiv.querySelector('div[style*="display: none"]');
  if (hiddenDiv) {
    console.log('Found hidden div with user data'); // Debug log

    // Extract user information from the hidden div
    const userDataDiv = hiddenDiv.querySelector('.row.w-100');
    if (userDataDiv) {
      console.log('Found user data div'); // Debug log
      const paragraphs = userDataDiv.querySelectorAll('p');
      let userName = '', userNip = '', userNoHp = '', userNoMember = '', userType = '', jamMasuk = '-', jamPulang = '-', userCategory = '';

      paragraphs.forEach(p => {
        const text = p.textContent.trim();
        console.log('Processing paragraph:', text); // Debug log

        if (text.includes('Nama :')) {
          userName = p.querySelector('b') ? p.querySelector('b').textContent : '';
          console.log('Extracted name:', userName);
        } else if (text.includes('No Member :')) {
          userNoMember = p.querySelector('b') ? p.querySelector('b').textContent : '';
          userCategory = 'member';
          console.log('Extracted member number:', userNoMember);
        } else if (text.includes('NIP :')) {
          userNip = p.querySelector('b') ? p.querySelector('b').textContent : '';
          userCategory = 'pegawai';
          console.log('Extracted NIP:', userNip);
        } else if (text.includes('No HP :')) {
          userNoHp = p.querySelector('b') ? p.querySelector('b').textContent : '';
          console.log('Extracted phone:', userNoHp);
        } else if (text.includes('Tipe Member :')) {
          userType = p.querySelector('b') ? p.querySelector('b').textContent : '';
          console.log('Extracted member type:', userType);
        } else if (text.includes('D.I/Wilayah :')) {
          userType = p.querySelector('b') ? p.querySelector('b').textContent : '';
          userCategory = 'penjaga';
          console.log('Extracted employee region:', userType);
        } else if (text.includes('Jam masuk :')) {
          jamMasuk = p.querySelector('b') ? p.querySelector('b').textContent : '-';
          console.log('Extracted check-in time:', jamMasuk);
        } else if (text.includes('Jam pulang :')) {
          jamPulang = p.querySelector('b') ? p.querySelector('b').textContent : '-';
          console.log('Extracted check-out time:', jamPulang);
        }
      });

      if (userName) {
        console.log('Updating user data display'); // Debug log
        // Update user data display based on user category
        let displayHtml = '';

        if (userCategory === 'member') {
          // For members: show Nama, No Member, No HP, Tipe Member, Jam Masuk, Jam Pulang
          displayHtml = `
            <p><strong>Nama:</strong> ${userName}</p>
            <p><strong>No Member:</strong> ${userNoMember}</p>
            <p><strong>No HP:</strong> ${userNoHp}</p>
            <p><strong>Tipe Member:</strong> ${userType}</p>
            <p><strong>Jam Masuk:</strong> ${jamMasuk}</p>
            <p><strong>Jam Pulang:</strong> ${jamPulang}</p>
          `;
        } else if (userCategory === 'pegawai') {
          // For employees: show Nama, NIP, No HP, Jam Masuk, Jam Pulang
          displayHtml = `
            <p><strong>Nama:</strong> ${userName}</p>
            <p><strong>NIP:</strong> ${userNip}</p>
            <p><strong>No HP:</strong> ${userNoHp}</p>
            <p><strong>Jam Masuk:</strong> ${jamMasuk}</p>
            <p><strong>Jam Pulang:</strong> ${jamPulang}</p>
          `;
        } else if (userCategory === 'penjaga') {
          // For penjaga: show Nama, NIP, D.I/Wilayah, Jam Masuk, Jam Pulang
          displayHtml = `
            <p><strong>Nama:</strong> ${userName}</p>
            <p><strong>NIP:</strong> ${userNip}</p>
            <p><strong>D.I/Wilayah:</strong> ${userType}</p>
            <p><strong>Jam Masuk:</strong> ${jamMasuk}</p>
            <p><strong>Jam Pulang:</strong> ${jamPulang}</p>
          `;
        } else {
          // Default display
          displayHtml = `
            <p><strong>Nama:</strong> ${userName}</p>
            <p><strong>No Member:</strong> ${userNoMember}</p>
            <p><strong>NIP/No HP:</strong> ${userNip || userNoHp}</p>
            <p><strong>Tipe:</strong> ${userType}</p>
            <p><strong>Jam Masuk:</strong> ${jamMasuk}</p>
            <p><strong>Jam Pulang:</strong> ${jamPulang}</p>
          `;
        }

        $('#userData').html(displayHtml);
      } else {
        console.log('No user name found, not updating display');
      }
    } else {
      console.log('No user data div found');
    }

    // Try to find photo in the hidden div
    const photoElement = hiddenDiv.querySelector('#userPhotoData');
    if (photoElement) {
      console.log('Found photo element'); // Debug log
      if (photoElement.tagName === 'IMG') {
        const photoSrc = photoElement.getAttribute('src');
        console.log('Photo src:', photoSrc);
        $('#userPhoto').html(`
          <img src="${photoSrc}" alt="User Photo"
               class="img-fluid rounded mx-auto d-block"
               style="width: auto; height: 300px; max-width: 100%; object-fit: contain; border-radius: 10px;">
        `);
      } else {
        // Default photo placeholder
        console.log('Photo element is not an IMG tag');
        $('#userPhoto').html(`
          <div class="bg-secondary rounded mx-auto d-flex align-items-center justify-content-center"
               style="width: 300px; height: 300px; border-radius: 10px;">
            <i class="material-icons text-white" style="font-size: 100px;">person</i>
          </div>
        `);
      }
    } else {
      console.log('No photo element found');
      // Default photo placeholder
      $('#userPhoto').html(`
        <div class="bg-secondary rounded mx-auto d-flex align-items-center justify-content-center"
             style="width: 300px; height: 300px; border-radius: 10px;">
          <i class="material-icons text-white" style="font-size: 100px;">person</i>
        </div>
      `);
    }
  } else {
    console.log('No hidden div found in response');
    // Reset to default if no user data found
    $('#userData').html('<p class="text-muted">Data akan muncul setelah scan QR</p>');
    $('#userPhoto').html(`
      <?php
      $defaultPhoto = 'assets/img/default-avatar.png';
      if (file_exists(FCPATH . $defaultPhoto)): ?>
        <img src="<?= base_url($defaultPhoto); ?>"
             alt="Default Photo"
             class="img-fluid rounded mx-auto d-block"
             style="width: auto; height: 300px; max-width: 100%; object-fit: contain; border-radius: 10px;">
      <?php else: ?>
        <div class="bg-secondary rounded mx-auto d-flex align-items-center justify-content-center"
             style="width: 300px; height: 300px; border-radius: 10px;">
          <i class="material-icons text-white" style="font-size: 100px;">person</i>
        </div>
      <?php endif; ?>
    `);
  }
  <?php endif; ?>
}

/* === TOGGLE SCAN METHOD === */
$('#btnKamera').on('click', function() {
  $('#btnKamera').addClass('active');
  $('#btnScanner').removeClass('active');
  $('#cameraSection').show();
  $('#scannerSection').hide();
  $('#scannerInput').blur(); // Remove focus from scanner input

  // Reset and reinitialize camera
  if (codeReader) {
    codeReader.reset();
  }
  setTimeout(initScanner, 100); // Small delay to ensure DOM is ready
});

$('#btnScanner').on('click', function() {
  $('#btnScanner').addClass('active');
  $('#btnKamera').removeClass('active');
  $('#scannerSection').show();
  $('#cameraSection').hide();

  // Stop camera completely
  if (codeReader) {
    codeReader.reset();
  }

  // Tampilkan status scanner aktif
  $('#scannerStatus').html('🔍 Scanner USB aktif - langsung scan QR code sekarang...').show();

  // Mulai auto-detection untuk scanner
  startScannerDetection();
});

/* === SCANNER AUTO-DETECTION === */
function startScannerDetection() {
  let scannerBuffer = '';
  let lastInputTime = Date.now();

  // Pastikan input scanner mendapat fokus
  $('#scannerInput').focus();

  $('#scannerInput').on('keydown input', function(e) {
    const currentTime = Date.now();
    const inputValue = $(this).val();

    console.log('Scanner input detected:', inputValue, 'Time diff:', currentTime - lastInputTime);

    // Jika input baru dalam waktu singkat (scanner input), tambahkan ke buffer
    if (currentTime - lastInputTime < 200) { // 200ms threshold untuk scanner
      scannerBuffer += inputValue.slice(-1); // Ambil karakter terakhir
      console.log('Added to buffer:', scannerBuffer);
    } else {
      // Input manual, reset buffer
      scannerBuffer = inputValue;
      console.log('Manual input, reset buffer:', scannerBuffer);
    }

    lastInputTime = currentTime;

    // Clear input field setelah buffer
    $(this).val('');

    // Jika buffer memiliki panjang yang masuk akal untuk QR code (biasanya 20-50 karakter)
    if (scannerBuffer.length >= 20) {
      console.log('Buffer ready for processing:', scannerBuffer);
      // Delay sedikit untuk memastikan semua karakter diterima
      setTimeout(() => {
        if (scannerBuffer.length >= 20) {
          console.log('Processing scanner buffer:', scannerBuffer);
          $('#scannerStatus').html('✅ QR Code terdeteksi! Memproses...').show();
          cekData(scannerBuffer);
          scannerBuffer = ''; // Reset buffer

          // Reset fokus setelah proses
          setTimeout(() => {
            $('#scannerInput').focus();
          }, 100);
        }
      }, 300);
    }
  });

  // Event listener untuk mendeteksi input scanner langsung dari keyboard
  $(document).on('keydown', function(e) {
    // Jika input field scanner tidak fokus, scanner mungkin mengirim input ke document
    if (!$('#scannerInput').is(':focus')) {
      console.log('Document keydown detected:', e.key, 'KeyCode:', e.keyCode);

      // Jika karakter alphanumeric, mungkin dari scanner
      if (e.key && e.key.length === 1 && /[a-zA-Z0-9\-_]/.test(e.key)) {
        scannerBuffer += e.key;
        console.log('Added to buffer from document:', scannerBuffer);

        // Jika buffer cukup panjang, proses
        if (scannerBuffer.length >= 20) {
          console.log('Processing buffer from document:', scannerBuffer);
          $('#scannerStatus').html('✅ QR Code terdeteksi! Memproses...').show();
          cekData(scannerBuffer);
          scannerBuffer = ''; // Reset buffer
        }
      }

      // Jika Enter ditekan dan buffer ada isinya, proses juga
      if (e.key === 'Enter' && scannerBuffer.length > 0) {
        console.log('Enter pressed, processing buffer:', scannerBuffer);
        $('#scannerStatus').html('✅ QR Code terdeteksi! Memproses...').show();
        cekData(scannerBuffer);
        scannerBuffer = ''; // Reset buffer
      }
    }
  });
}

/* === DETEKSI FULLSCREEN === */
function checkFullscreen() {
  const isFullscreen = document.fullscreenElement ||
                       document.webkitFullscreenElement ||
                       document.mozFullScreenElement ||
                       document.msFullscreenElement;

  if (isFullscreen) {
    $('body').addClass('fullscreen-active');
    console.log('Fullscreen mode detected');
  } else {
    $('body').removeClass('fullscreen-active');
    console.log('Normal mode detected');
  }
}

// Listen for fullscreen changes
document.addEventListener('fullscreenchange', checkFullscreen);
document.addEventListener('webkitfullscreenchange', checkFullscreen);
document.addEventListener('mozfullscreenchange', checkFullscreen);
document.addEventListener('MSFullscreenChange', checkFullscreen);

// Check on page load
$(document).ready(function() {
  checkFullscreen();

  // Default mode: scanner USB
  $('#btnScanner').click();

  if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
    // Check if we're in camera mode initially
    if ($('#cameraSection').is(':visible')) {
      setTimeout(initScanner, 500); // Delay to ensure page is fully loaded
    }
  } else {
    $('#hasilScan').html('<div class="alert alert-danger">Browser tidak mendukung akses kamera. Silakan gunakan scanner USB.</div>');
  }
});

// Reset sound flag when page is refreshed or navigated back
$(window).on('beforeunload', function() {
  soundPlayed = false;
});
</script>

<?= $this->endSection(); ?>
