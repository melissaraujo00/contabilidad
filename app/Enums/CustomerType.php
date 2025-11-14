<?php

namespace App\Enums;

enum CustomerType: string
{
    case Consumidor = 'consumidor';
    case Juridico   = 'juridico';
    case Proveedor  = 'proveedor';
}
