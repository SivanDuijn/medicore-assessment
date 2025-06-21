<?php

namespace App\Enum;

enum TransportType: int
{
    case CAR   = 0;
    case BIKE  = 1;
    case BUS   = 2;
    case TRAIN = 3;

    public function getName(): string
    {
        return match ($this) {
            self::CAR => 'Car',
            self::BIKE => 'Bike',
            self::BUS => 'Bus',
            self::TRAIN => 'Train'
        };
    }
}
