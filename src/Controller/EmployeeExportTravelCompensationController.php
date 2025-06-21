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
    public function __invoke(
        EmployeeRepository $employeeRepository,
        EmployeeTravelCompensationService $employeeTravelCompensationService,
    ): Response
    {
        $employees = $employeeRepository->findAll();

        $list = [
            ['Employee', 'Transport', 'Travelled distance (km)', 'Monthly compensation (€)', 'Payment date']
        ];

        $currentYear = (int)date('Y');

        // Loop over all months in a year and calculate the travel compensation for each employee
        for ($month = 0; $month < 12; $month++) {
            $date = new DateTime("$currentYear-$month-01");
            $paymentDate = (new DateTime("$currentYear-$month-01"))->modify('+1 month');
            foreach ($employees as $employee) {
                // Since the payment date is on the first day of the next month we start in december and end november
                $distanceTravelledKm =
                    $employeeTravelCompensationService->calculateMonthlyDistanceTravelledKm($employee, $date);

                $compensation =
                    $employeeTravelCompensationService->calculateTravelCompensation($employee, $distanceTravelledKm);

                $list[] = [
                    $employee->getName(),
                    $employee->getTransportType()?->getName(),
                    round($distanceTravelledKm, 2),
                    round($compensation, 2),
                    $paymentDate->format('d-m-Y')
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
