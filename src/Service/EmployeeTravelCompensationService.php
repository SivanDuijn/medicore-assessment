<?php

namespace App\Service;

use App\Entity\Employee;
use App\Enum\TransportType;
use DateTime;

class EmployeeTravelCompensationService
{
    /** @return float The compensation in euro's for the specified distance the employee has travelled. */
    public function calculateTravelCompensation(Employee $employee, float $distanceTraveledKm): float
    {
        return $distanceTraveledKm * $this->getCompensationPerKm($employee);
    }

    /** @return float The total distance an employee has had to travel to reach work for current month in the provided date. */
    public function calculateMonthlyDistanceTravelledKm(Employee $employee, DateTime $date): float
    {
        $daysInMonth = (float)$date->format('t');
        $weeksInMonth = $daysInMonth / 7;
        $workdays = $weeksInMonth * $employee->getWorkdaysPerWeek();
        return $workdays * $employee->getTravelDistanceOneWayKm() * 2;
    }

    /**
     * Business logic for calculating the compensation per km based on the transport type
     * and one way distance the employee has to travel
     * @param Employee $employee
     * @return float The compensation in euro's per kilometer
     */
    private function getCompensationPerKm(Employee $employee): float
    {
        $transportType = $employee->getTransportType();
        $compensation = match ($transportType) {
            TransportType::CAR => 0.10,
            TransportType::BIKE => 0.50,
            TransportType::BUS, TransportType::TRAIN => 0.25,
        };

        // Double the compensation when commuting by bike and the travel distance is between 5 and 10 km
        $oneWayTravelDistanceKm = $employee->getTravelDistanceOneWayKm();
        if ($transportType === TransportType::BIKE &&
            $oneWayTravelDistanceKm > 5 &&
            $oneWayTravelDistanceKm < 10) {
            $compensation *= 2;
        }

        return $compensation;
    }
}
