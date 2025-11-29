<?php

namespace App\Enums;

enum TipoEmpresa: string
{
    case EMPRESA = 'Empresa';
    case SOCIEDAD = 'Sociedad';
    case SOCIEDAD_ANONIMA = 'Sociedad Anónima';
    case SOCIEDAD_VARIABLE = 'Sociedades Anónimas de Capital Variable';
    case SOCIEDAD_COLECTIVO = 'Sociedad en Nombre Colectivo';
}
