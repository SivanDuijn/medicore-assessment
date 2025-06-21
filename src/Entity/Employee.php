<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use ApiPlatform\OpenApi\Model\Operation as OpenApiOperation;
use App\Controller\EmployeeExportTravelCompensationController;
use App\Enum\TransportType;
use App\Repository\EmployeeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EmployeeRepository::class)]
#[ApiResource(
    operations: [
        new Get(),              // GET /api/employees/{id}
        new GetCollection(),    // GET /api/employees
        new Post(),             // POST /api/employees
        new Put(),              // PUT /api/employees/{id}
        new Patch(),            // PATCH /api/employees/{id}
        new Delete(),           // DELETE /api/employees/{id}
        new GetCollection(
            uriTemplate: '/employees/export/travel-compensation',
            controller: EmployeeExportTravelCompensationController::class,
            openapi: new OpenApiOperation(
                responses: [
                    '200' => [
                        'description' => 'CSV file',
                        'content' => [
                            'text/csv' => [
                                'schema' => [
                                    'type' => 'string',
                                    'format' => 'binary'
                                ]
                            ]
                        ]
                    ]
                ],
                summary: 'Export employee travel compensation for the current year',
                description: 'Exports the travel compensation cost for each employee for each month in the current year'
            ),
            paginationEnabled: false,
            output: false,
            read: false,
            name: 'api_employee_export_travel_compensation'
        )
    ]
)]
class Employee
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(enumType: TransportType::class)]
    private ?TransportType $transportType = null;

    #[ORM\Column]
    private ?float $travelDistanceOneWayKm = null;

    #[ORM\Column]
    private ?float $workdaysPerWeek = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getTransportType(): ?TransportType
    {
        return $this->transportType;
    }

    public function setTransportType(TransportType $transportType): static
    {
        $this->transportType = $transportType;

        return $this;
    }

    public function getTravelDistanceOneWayKm(): ?float
    {
        return $this->travelDistanceOneWayKm;
    }

    public function setTravelDistanceOneWayKm(float $travelDistanceOneWayKm): static
    {
        $this->travelDistanceOneWayKm = $travelDistanceOneWayKm;

        return $this;
    }

    public function getWorkdaysPerWeek(): ?float
    {
        return $this->workdaysPerWeek;
    }

    public function setWorkdaysPerWeek(float $workdaysPerWeek): static
    {
        $this->workdaysPerWeek = $workdaysPerWeek;

        return $this;
    }
}
