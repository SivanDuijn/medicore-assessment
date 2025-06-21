<?php

namespace App\Service;

use App\Entity\Employee;
use App\Enum\TransportType;
use DateTime;

class EmployeeTravelCompensationService
{
    /**
     * @param Employee $employee
     * @param int $year The year for which to calculate the monthly cost
     * @param int $month The month for which to calculate the costs, starting at 1 (january)
     * @return float The compensation in euro's for the specified month
     */
    public function calculateTravelCompensation(Employee $employee, float $distanceTraveledKm): float
    {
        $compensationPerKm =
            $this->getCompensationPerKm($employee->getTransportType(), $employee->getTravelDistanceOneWayKm());

        return $distanceTraveledKm * $compensationPerKm;
    }

    public function calculateMonthlyDistanceTravelledKm($employee, DateTime $date): float
    {
        $daysInMonth = (float)$date->format('t');
        $weeksInMonth = $daysInMonth / 7;
        $workdays = $weeksInMonth * $employee->getWorkdaysPerWeek();
        return $workdays * $employee->getTravelDistanceOneWayKm() * 2;
    }

    /**
     * Business logic for calculating the compensation per km based on the transport type
     * and one way distance the employee has to travel
     * @param TransportType $transportType
     * @param float $oneWayTravelDistanceKm
     * @return float The compensation in euro's per kilometer
     */
    private function getCompensationPerKm(TransportType $transportType, float $oneWayTravelDistanceKm): float
    {
        return match ($transportType) {
            TransportType::CAR => 0.10,
            TransportType::BIKE =>
                $oneWayTravelDistanceKm > 5 && $oneWayTravelDistanceKm < 10 ? 1 : 0.50,
            TransportType::BUS, TransportType::TRAIN => 0.50,
        };
    }
}
