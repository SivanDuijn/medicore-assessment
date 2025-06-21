<?php

namespace App\Service;

use App\Entity\Employee;
use App\Enum\TransportType;
use App\Model\MonthlyTravelCompensation;
use DateTime;

class EmployeeTravelCompensationService
{

    /** @return MonthlyTravelCompensation[] */
    public function calculateYearlyTravelCompensation(Employee $employee, int $year): array
    {
        $months = [];

        // Loop over all months in a year and calculate the travel compensation for each employee
        for ($month = 0; $month < 12; $month++) {
            // Since the payment date is on the first day of the next month we start in december and end november
            $date = new DateTime("$year-$month-01");
            $paymentDate = (new DateTime("$year-$month-01"))->modify('+1 month');
            $distanceTravelledKm =
                $this->calculateMonthlyDistanceTravelledKm($employee, $date);

            $compensation =
                $this->calculateTravelCompensation($employee, $distanceTravelledKm);

            $months[] = new MonthlyTravelCompensation(
                round($distanceTravelledKm, 2),
                round($compensation, 2),
                $date,
                $paymentDate
            );
        }

        return $months;
    }

    /** @return float The compensation in euro's for the specified distance the employee has travelled. */
    public function calculateTravelCompensation(Employee $employee, float $distanceTraveledKm): float
    {
        $compensationPerKm =
            $this->getCompensationPerKm($employee->getTransportType(), $employee->getTravelDistanceOneWayKm());

        return $distanceTraveledKm * $compensationPerKm;
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
