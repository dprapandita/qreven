<?php

namespace App\Enums;

enum EventRole: string
{
    case Administrator = 'administrator';
    case Finance = 'finance';
    case CheckIn = 'check_in';
}
