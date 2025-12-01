<?php

namespace App\Enums;

enum TipoMovimiento: string
{
    case DEBE = 'Debe';
    case HABER = 'Haber';
}
