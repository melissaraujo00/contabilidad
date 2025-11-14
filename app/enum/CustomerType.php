<?php

namespace App\Enums;

enum CustomerType: string
{
    case Consumidor = 'Consumidor';
    case Juridico = 'juridico';
    case Proveedor = 'proveedor';
}
