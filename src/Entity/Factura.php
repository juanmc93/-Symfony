<?php

namespace App\Entity;

use App\Repository\FacturaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FacturaRepository::class)
 */
class Factura
{

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $establecimiento;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $punto_emision;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $sec_factura;

    /**
     * @ORM\Column(type="date")
     * @Assert\NotBlank
     */
    private $fecha;

    /**
     * @ORM\ManyToOne(targetEntity=Empresa::class, inversedBy="facturas")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank
     */
    private $empresa;

    /**
     * @ORM\ManyToOne(targetEntity=Clientes::class, inversedBy="facturas")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank
     */
    private $clientes;

    /**
     * @ORM\Column(type="float")
     * @Assert\NotBlank
     */
    private $impuestos;

    /**
     * @ORM\Column(type="float")
     * @Assert\NotBlank
     */
    private $total;

    /**
     * @ORM\Column(type="float")
     */
    private $subtotal;

    /**
     * @ORM\OneToMany(targetEntity=FacturaDetalle::class, mappedBy="facturas",cascade={"remove"})
     */
    private $factura_detalle;

    public function __construct()
    {
        $this->fecha = new \DateTime();
        $this->factura_detalle = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEstablecimiento(): ?string
    {
        return $this->establecimiento;
    }

    public function setEstablecimiento(string $establecimiento): self
    {
        $this->establecimiento = $establecimiento;

        return $this;
    }

    public function getPuntoEmision(): ?string
    {
        return $this->punto_emision;
    }

    public function setPuntoEmision(string $punto_emision): self
    {
        $this->punto_emision = $punto_emision;

        return $this;
    }

    public function getSecFactura(): ?string
    {
        return $this->sec_factura;
    }

    public function setSecFactura(string $sec_factura): self
    {
        $this->sec_factura = $sec_factura;

        return $this;
    }

    public function getFecha(): ?\DateTime
    {
        return $this->fecha;
    }

    public function setFecha(\DateTime $fecha): self
    {
        $this->fecha = $fecha;

        return $this;
    }

    public function getEmpresa(): ?Empresa
    {
        return $this->empresa;
    }

    public function setEmpresa(?Empresa $empresa): self
    {
        $this->empresa = $empresa;

        return $this;
    }

    public function getClientes(): ?Clientes
    {
        return $this->clientes;
    }

    public function setClientes(?Clientes $clientes): self
    {
        $this->clientes = $clientes;

        return $this;
    }

    public function getImpuestos(): ?float
    {
        return $this->impuestos;
    }

    public function setImpuestos(float $impuestos): self
    {
        $this->impuestos = $impuestos;

        return $this;
    }

    public function getTotal(): ?float
    {
        return $this->total;
    }

    public function setTotal(float $total): self
    {
        $this->total = $total;
        return $this;
    }

    public function getSubtotal(): ?float
    {
        return $this->subtotal;
    }

    public function setSubtotal(float $subtotal): self
    {
        $this->subtotal = $subtotal;

        return $this;
    }

    /**
     * @return Collection|FacturaDetalle[]
     */
    public function getFacturaDetalle(): Collection
    {
        return $this->factura_detalle;
    }

    public function addFacturaDetalle(FacturaDetalle $facturaDetalle): self
    {
        if (!$this->factura_detalle->contains($facturaDetalle)) {
            $this->factura_detalle[] = $facturaDetalle;
            $facturaDetalle->setFacturas($this);
        }

        return $this;
    }

    public function removeFacturaDetalle(FacturaDetalle $facturaDetalle): self
    {
        if ($this->factura_detalle->contains($facturaDetalle)) {
            $this->factura_detalle->removeElement($facturaDetalle);
            // set the owning side to null (unless already changed)
            if ($facturaDetalle->getFacturas() === $this) {
                $facturaDetalle->setFacturas(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->getSecFactura();
    }
}
