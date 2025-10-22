<?php

namespace App\Libraries\enums;

enum kehadiran: int
{
  case Hadir = 1;
  case Sakit = 2;
  case Izin = 3;
  case TanpaKeterangan = 4;
}
