<?php

namespace App\Enums;

enum BookingStatus: string
{
    case CONFIRMED = 'confirmed';
    case CANCELLED = 'cancelled';
}
