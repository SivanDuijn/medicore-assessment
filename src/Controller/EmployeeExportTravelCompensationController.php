<?php

namespace App\Controller;

use App\Repository\EmployeeRepository;
use App\Service\EmployeeTravelCompensationService;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/export/employees-travel-compensation', name: 'api_employees_travel_costs_')]
class EmployeeExportTravelCompensationController extends AbstractController
{
    #[Route('/csv', name: 'csv', methods: ['GET'])]
    public function exportCSVTravelCompensation(
        EmployeeRepository $employeeRepository,
        EmployeeTravelCompensationService $employeeTravelCompensationService,
    ): Response
    {
        $employees = $employeeRepository->findAll();

        $list = [
            ['Employee', 'Transport', 'Travelled distance (km)', 'Monthly compensation', 'Payment date']
        ];

        $currentYear = (int)date('Y');

        // Loop over all months in a year and calculate the travel compensation for each employee
        for ($month = 0; $month < 12; $month++) {
            // Since the payment date is on the first day of the next month we start in december and end november
            $date = new DateTime("$currentYear-$month-01");
            $paymentDate = (new DateTime("$currentYear-$month-01"))->modify('+1 month')->format('d-m-Y');
            foreach ($employees as $employee) {
                $distanceTravelledKm =
                    $employeeTravelCompensationService->calculateMonthlyDistanceTravelledKm($employee, $date);

                $compensation =
                    $employeeTravelCompensationService->calculateTravelCompensation($employee, $distanceTravelledKm);

                $list[] = [
                    $employee->getName(),
                    $employee->getTransportType()?->getName(),
                    round($distanceTravelledKm, 2),
                    round($compensation, 2),
                    $paymentDate
                ];
            }
        }

        $fp = fopen('php://temp', 'w');
        foreach ($list as $fields) {
            fputcsv($fp, $fields);
        }

        rewind($fp);
        $response = new Response(stream_get_contents($fp));
        fclose($fp);

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', "attachment; filename=employee_travel_composition_$currentYear.csv");

        return $response;
    }
}
