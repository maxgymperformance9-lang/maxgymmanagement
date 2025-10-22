public function validate($cardUid)
   {
      // Import RFID models
      $rfidCardModel = new \App\Models\RfidCardModel();
      $rfidBalanceModel = new \App\Models\RfidBalanceModel();

      // Cari kartu RFID berdasarkan UID
      $card = $rfidCardModel->getCardByUid($cardUid);

      if (!$card) {
         return $this->response->setJSON([
            'success' => false,
            'message' => 'Kartu RFID tidak ditemukan'
         ]);
      }

      // Cek status kartu
      if ($card['card_status'] !== 'active') {
         return $this->response->setJSON([
            'success' => false,
            'message' => 'Kartu RFID tidak aktif'
         ]);
      }

      // Cek masa berlaku kartu
      if ($card['expiry_date'] && strtotime($card['expiry_date']) < time()) {
         return $this->response->setJSON([
            'success' => false,
            'message' => 'Kartu RFID sudah kadaluarsa'
         ]);
      }

      // Cek saldo kartu
      $balance = $rfidBalanceModel->getBalanceByCardId($card['id_rfid']);
      $currentBalance = $balance ? $balance['balance'] : 0;

      if ($currentBalance <= 0) {
         return $this->response->setJSON([
            'success' => false,
            'message' => 'Saldo kartu tidak mencukupi'
         ]);
      }

      // Jika semua validasi lolos, buka pintu
      $this->sendCommandToESP32('open');

      // Log akses pintu
      $this->doorAccessModel->logDoorAccess($card['id_member'] ?? null, 'rfid_card', 'success');

      return $this->response->setJSON([
         'success' => true,
         'message' => 'Akses diterima, pintu dibuka',
         'card_info' => [
            'uid' => $card['rfid_uid'],
            'member_name' => $card['nama_member'] ?? 'Unassigned',
            'balance' => $currentBalance
         ]
      ]);
   }
