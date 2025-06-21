<?php

namespace App\Model;

use DateTime;

class MonthlyTravelCompensation {
    public float $distanceTravelledKm;
    public float $compensation;
    public DateTime $date;
    public DateTime $paymentDate;

    public function __construct(float $distanceTravelledKm, float $compensation, DateTime $date, DateTime $paymentDate) {
        $this->distanceTravelledKm = $distanceTravelledKm;
        $this->compensation = $compensation;
        $this->date = $date;
        $this->paymentDate = $paymentDate;
    }
}
