<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Enum\TransportType;
use App\Repository\EmployeeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EmployeeRepository::class)]
#[ApiResource]
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
