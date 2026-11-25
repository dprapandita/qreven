<?php

namespace App\Enums;

enum BoothBookingStatus: string
{
    case Pending = 'menunggu';
    case Approved = 'disetujui';
    case Rejected = 'ditolak';
    case Cancelled = 'dibatalkan';
}
