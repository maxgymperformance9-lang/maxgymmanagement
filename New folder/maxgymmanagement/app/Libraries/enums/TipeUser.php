<?php

namespace App\Libraries\enums;

enum TipeUser: string
{
  case penjaga = 'id_penjaga';
  case pegawai = 'id_pegawai';
  case member = 'id_member';
}
