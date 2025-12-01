<?php

namespace App\Enums;

enum TipoPartida: string
{
    case INICIAL = 'Partida Inicial';
    case NORMAL = 'Partida Normal';
    case CIERRE = 'Partida Cierre';
}
