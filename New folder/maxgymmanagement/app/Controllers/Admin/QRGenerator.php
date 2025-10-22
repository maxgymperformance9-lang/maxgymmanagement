<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PegawaiModel;
use App\Models\DiModel;
use App\Models\PenjagaModel;
use App\Models\MemberModel;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Endroid\QrCode\Label\Font\Font;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Label\Label;
use Endroid\QrCode\Logo\Logo;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Writer\SvgWriter;
use Endroid\QrCode\Writer\WriterInterface;

class QRGenerator extends BaseController
{
   protected QrCode $qrCode;
   protected WriterInterface $writer;
   protected ?Logo $logo = null;
   protected Label $label;
   protected Font $labelFont;
   protected Color $foregroundColor;
   protected Color $foregroundColor2;
   protected Color $backgroundColor;

   protected string $qrCodeFilePath;

   const UPLOADS_PATH = FCPATH . 'uploads' . DIRECTORY_SEPARATOR;

   public function __construct()
   {
      $this->setQrCodeFilePath(self::UPLOADS_PATH);

      $this->writer = new PngWriter();

      $this->labelFont = new Font(FCPATH . 'assets/fonts/Roboto-Medium.ttf', 14);

      $this->foregroundColor = new Color(44, 73, 162);
      $this->foregroundColor2 = new Color(28, 101, 90);
      $this->backgroundColor = new Color(255, 255, 255);

      if (boolval(env('QR_LOGO'))) {
         // Create logo
         $logo = (new \Config\office)::$generalSettings->logo;
         if (empty($logo) || !file_exists(FCPATH . $logo)) {
            $logo = 'assets/img/max13.png';
         }
         if (file_exists(FCPATH . $logo)) {
            $fileExtension = pathinfo(FCPATH . $logo, PATHINFO_EXTENSION);
            if ($fileExtension === 'svg') {
               $this->writer = new SvgWriter();
               $this->logo = Logo::create(FCPATH . $logo)
                  ->setResizeToWidth(75)
                  ->setResizeToHeight(75);
            } else {
               $this->logo = Logo::create(FCPATH . $logo)
                  ->setResizeToWidth(75);
            }
         }
      }

      $this->label = Label::create('')
         ->setFont($this->labelFont)
         ->setTextColor($this->foregroundColor);

      // Create QR code
      $this->qrCode = QrCode::create('')
         ->setEncoding(new Encoding('UTF-8'))
         ->setErrorCorrectionLevel(new ErrorCorrectionLevelHigh())
         ->setSize(500)
         ->setMargin(15)
         ->setRoundBlockSizeMode(new RoundBlockSizeModeMargin())
         ->setForegroundColor(new Color(0, 0, 0)) // Pure black for better contrast
         ->setBackgroundColor(new Color(255, 255, 255)); // Pure white background
   }

   public function setQrCodeFilePath(string $qrCodeFilePath)
   {
      $this->qrCodeFilePath = $qrCodeFilePath;
      if (!file_exists($this->qrCodeFilePath)) mkdir($this->qrCodeFilePath, recursive: true);
   }

   public function generateQrSiswa()
   {
      $di = $this->getKelasJurusanSlug($this->request->getVar('id_di'));
      if (!$di) {
         return $this->response->setJSON(false);
      }

      $this->qrCodeFilePath .= "qr-penjaga/$di/";

      if (!file_exists($this->qrCodeFilePath)) {
         mkdir($this->qrCodeFilePath, recursive: true);
      }

      $this->generate(
         unique_code: $this->request->getVar('unique_code'),
         nama: $this->request->getVar('nama'),
         nomor: $this->request->getVar('nomor')
      );

      return $this->response->setJSON(true);
   }

   public function generateQrGuru()
   {
      // Use pure black for better scanning
      $this->qrCode->setForegroundColor(new Color(0, 0, 0));
      $this->label->setTextColor(new Color(0, 0, 0));

      $this->qrCodeFilePath .= 'qr-pegawai/';

      if (!file_exists($this->qrCodeFilePath)) {
         mkdir($this->qrCodeFilePath, recursive: true);
      }

      $this->generate(
         unique_code: $this->request->getVar('unique_code'),
         nama: $this->request->getVar('nama'),
         nomor: $this->request->getVar('nomor')
      );

      return $this->response->setJSON(true);
   }

   public function generateQrMember()
   {
      // Use pure black for better scanning
      $this->qrCode->setForegroundColor(new Color(0, 0, 0));
      $this->label->setTextColor(new Color(0, 0, 0));

      $this->qrCodeFilePath .= 'qr-member/';

      if (!file_exists($this->qrCodeFilePath)) {
         mkdir($this->qrCodeFilePath, recursive: true);
      }

      $this->generate(
         unique_code: $this->request->getVar('unique_code'),
         nama: $this->request->getVar('nama'),
         nomor: $this->request->getVar('nomor')
      );

      return $this->response->setJSON(true);
   }

   public function generate($nama, $nomor, $unique_code)
   {
      $fileExt = $this->writer instanceof SvgWriter ? 'svg' : 'png';
      $filename = url_title($nama, lowercase: true) . "_" . url_title($nomor, lowercase: true) . ".$fileExt";

      // set qr code data
      $this->qrCode->setData($unique_code);

      $this->label->setText($nama);

      // Save it to a file
      $this->writer
         ->write(
            qrCode: $this->qrCode,
            logo: $this->logo,
            label: $this->label
         )
         ->saveToFile(
            path: $this->qrCodeFilePath . $filename
         );

      return $this->qrCodeFilePath . $filename;
   }

   public function downloadQrSiswa($idPenjaga = null)
   {
      $penjaga = (new PenjagaModel)->find($idPenjaga);
      if (!$penjaga) {
         session()->setFlashdata([
            'msg' => 'petugas tidak ditemukan',
            'error' => true
         ]);
         return redirect()->back();
      }
      
      try {
         $di = $this->getKelasJurusanSlug($penjaga['id_di']) ?? 'tmp';
         $this->qrCodeFilePath .= "qr-petugas/$di/";

         if (!file_exists($this->qrCodeFilePath)) {
            mkdir($this->qrCodeFilePath, recursive: true);
         }

         return $this->response->download(
            $this->generate(
               nama: $penjaga['nama_penjaga'],
               nomor: $penjaga['nip'],
               unique_code: $penjaga['unique_code'],
            ),
            null,
            true,
         );
      } catch (\Throwable $th) {
         session()->setFlashdata([
            'msg' => $th->getMessage(),
            'error' => true
         ]);
         return redirect()->back();
      }
   }

   public function downloadQrGuru($idPegawai = null)
   {
      $pegawai = (new PegawaiModel)->find($idPegawai);
      if (!$pegawai) {
         session()->setFlashdata([
            'msg' => 'Data tidak ditemukan',
            'error' => true
         ]);
         return redirect()->back();
      }
      try {
         // Use pure black for better scanning
         $this->qrCode->setForegroundColor(new Color(0, 0, 0));
         $this->label->setTextColor(new Color(0, 0, 0));

         $this->qrCodeFilePath .= 'qr-pegawai/';

         if (!file_exists($this->qrCodeFilePath)) {
            mkdir($this->qrCodeFilePath, recursive: true);
         }

         return $this->response->download(
            $this->generate(
               nama: $pegawai['nama_pegawai'],
               nomor: $pegawai['nip'],
               unique_code: $pegawai['unique_code'],
            ),
            null,
            true,
         );
      } catch (\Throwable $th) {
         session()->setFlashdata([
            'msg' => $th->getMessage(),
            'error' => true
         ]);
         return redirect()->back();
      }
   }

   public function downloadQrMember($idMember = null)
   {
      $member = (new MemberModel)->find($idMember);
      if (!$member) {
         session()->setFlashdata([
            'msg' => 'Member tidak ditemukan',
            'error' => true
         ]);
         return redirect()->back();
      }
      try {
         // Use pure black for better scanning
         $this->qrCode->setForegroundColor(new Color(0, 0, 0));
         $this->label->setTextColor(new Color(0, 0, 0));

         $this->qrCodeFilePath .= 'qr-member/';

         if (!file_exists($this->qrCodeFilePath)) {
            mkdir($this->qrCodeFilePath, recursive: true);
         }

         return $this->response->download(
            $this->generate(
               nama: $member['nama_member'],
               nomor: $member['no_hp'],
               unique_code: $member['unique_code'],
            ),
            null,
            true,
         );
      } catch (\Throwable $th) {
         session()->setFlashdata([
            'msg' => $th->getMessage(),
            'error' => true
         ]);
         return redirect()->back();
      }
   }

   public function downloadAllQrMember()
   {
      $this->qrCodeFilePath .= 'qr-member/';

      if (!file_exists($this->qrCodeFilePath) || count(glob($this->qrCodeFilePath . '*')) === 0) {
         session()->setFlashdata([
            'msg' => 'QR Code tidak ditemukan, generate qr terlebih dahulu',
            'error' => true
         ]);
         return redirect()->back();
      }

      try {
         $output = self::UPLOADS_PATH . DIRECTORY_SEPARATOR . 'qrcode-member.zip';

         $this->zipFolder($this->qrCodeFilePath, $output);

         return $this->response->download($output, null,  true);
      } catch (\Throwable $th) {
         session()->setFlashdata([
            'msg' => $th->getMessage(),
            'error' => true
         ]);
         return redirect()->back();
      }
   }

   public function downloadAllQrSiswa()
   {
      $di = null;
      if ($idDi = $this->request->getVar('id_di')) {
         $di = $this->getKelasJurusanSlug($idDi);
         if (!$di) {
            session()->setFlashdata([
               'msg' => 'D.I tidak ditemukan',
               'error' => true
            ]);
            return redirect()->back();
         }
      }

      $this->qrCodeFilePath .= "qr-petugas/" . ($di ? "{$di}/" : '');

      if (!file_exists($this->qrCodeFilePath) || count(glob($this->qrCodeFilePath . '*')) === 0) {
         session()->setFlashdata([
            'msg' => 'QR Code tidak ditemukan, generate qr terlebih dahulu',
            'error' => true
         ]);
         return redirect()->back();
      }

      try {
         $output = self::UPLOADS_PATH . 'qrcode-petugas' . ($di ? "_{$di}.zip" : '.zip');

         $this->zipFolder($this->qrCodeFilePath, $output);

         return $this->response->download($output, null,  true);
      } catch (\Throwable $th) {
         session()->setFlashdata([
            'msg' => $th->getMessage(),
            'error' => true
         ]);
         return redirect()->back();
      }
   }

   public function downloadAllQrGuru()
   {
      $this->qrCodeFilePath .= 'qr-pegawai/';

      if (!file_exists($this->qrCodeFilePath) || count(glob($this->qrCodeFilePath . '*')) === 0) {
         session()->setFlashdata([
            'msg' => 'QR Code tidak ditemukan, generate qr terlebih dahulu',
            'error' => true
         ]);
         return redirect()->back();
      }

      try {
         $output = self::UPLOADS_PATH . DIRECTORY_SEPARATOR . 'qrcode-pegawai.zip';

         $this->zipFolder($this->qrCodeFilePath, $output);

         return $this->response->download($output, null,  true);
      } catch (\Throwable $th) {
         session()->setFlashdata([
            'msg' => $th->getMessage(),
            'error' => true
         ]);
         return redirect()->back();
      }
   }

   private function zipFolder(string $folder, string $output)
   {
      $zip = new \ZipArchive;
      $zip->open($output, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

      // Create recursive directory iterator
      /** @var \SplFileInfo[] $files */
      $files = new \RecursiveIteratorIterator(
         new \RecursiveDirectoryIterator($folder),
         \RecursiveIteratorIterator::LEAVES_ONLY
      );

      foreach ($files as $file) {
         // Skip directories (they would be added automatically)
         if (!$file->isDir()) {
            // Get real and relative path for current file
            $filePath = $file->getRealPath();
            $folderLength = strlen($folder);
            if ($folder[$folderLength - 1] === DIRECTORY_SEPARATOR) {
               $relativePath = substr($filePath, $folderLength);
            } else {
               $relativePath = substr($filePath, $folderLength + 1);
            }

            // Add current file to archive
            $zip->addFile($filePath, $relativePath);
         }
      }
      $zip->close();
   }

   protected function di(string $unique_code)
   {
      return self::UPLOADS_PATH . DIRECTORY_SEPARATOR . "qr-petugas/{$unique_code}.png";
   }

   protected function getKelasJurusanSlug(string $idDi)
   {
      $di = (new DiModel)->getDi($idDi);;
      if ($di) {
         return url_title($di->di . ' ' . $di->wilayah, lowercase: true);
      } else {
         return false;
      }
   }
}
