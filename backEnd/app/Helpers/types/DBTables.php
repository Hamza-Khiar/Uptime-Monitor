<?php

namespace App\Helpers\Types;

enum DBTables: string
{
    case Checks='checks';
    case Incidents='incidents';
}
