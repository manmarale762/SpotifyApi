<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Pago
 *
 * @ORM\Table(name="pago", uniqueConstraints={@ORM\UniqueConstraint(name="suscripcion_id_UNIQUE", columns={"suscripcion_id"})}, indexes={@ORM\Index(name="fecha_idx", columns={"fecha"}), @ORM\Index(name="fk_pago_forma_pago1_idx", columns={"forma_pago_id"})})
 * @ORM\Entity
 */
class Pago
{
    /**
     * @var int
     *
     * @ORM\Column(name="numero_orden", type="integer", nullable=false, options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $numeroOrden;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha", type="date", nullable=false)
     */
    private $fecha;

    /**
     * @var float
     *
     * @ORM\Column(name="total", type="float", precision=10, scale=0, nullable=false)
     */
    private $total;

    /**
     * @var FormaPago
     *
     * @ORM\ManyToOne(targetEntity="FormaPago")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="forma_pago_id", referencedColumnName="id")
     * })
     */
    private $formaPago;

    /**
     * @var Suscripcion
     *
     * @ORM\ManyToOne(targetEntity="Suscripcion")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="suscripcion_id", referencedColumnName="id")
     * })
     */
    private $suscripcion;

    public function getNumeroOrden(): int
    {
        return $this->numeroOrden;
    }

    public function setNumeroOrden(int $numeroOrden): void
    {
        $this->numeroOrden = $numeroOrden;
    }

    public function getFecha(): \DateTime
    {
        return $this->fecha;
    }

    public function setFecha(\DateTime $fecha): void
    {
        $this->fecha = $fecha;
    }

    public function getTotal(): float
    {
        return $this->total;
    }

    public function setTotal(float $total): void
    {
        $this->total = $total;
    }

    public function getFormaPago(): FormaPago
    {
        return $this->formaPago;
    }

    public function setFormaPago(FormaPago $formaPago): void
    {
        $this->formaPago = $formaPago;
    }

    public function getSuscripcion(): Suscripcion
    {
        return $this->suscripcion;
    }

    public function setSuscripcion(Suscripcion $suscripcion): void
    {
        $this->suscripcion = $suscripcion;
    }




}
