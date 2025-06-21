<?php

namespace App\Controller;

use App\Repository\EmployeeRepository;
use App\Service\EmployeeTravelCompensationService;
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

        foreach ($employees as $employee) {
            $months = $employeeTravelCompensationService->calculateYearlyTravelCompensation($employee, $currentYear);
            foreach ($months as $month) {
                $list[] = [
                    $employee->getName(),
                    $employee->getTransportType()?->getName(),
                    $month->distanceTravelledKm,
                    $month->compensation,
                    $month->paymentDate->format('d-m-Y')
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
