<?php

namespace App\Enums;

enum TicketStatus: string
{
    case Valid = 'belum_checkin';
    case CheckedIn = 'sudah_checkin';
    case Cancelled = 'dibatalkan';
}
