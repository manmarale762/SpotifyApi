<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Suscripcion
 *
 * @ORM\Table(name="suscripcion", indexes={@ORM\Index(name="fecha_fin_idx", columns={"fecha_fin"}), @ORM\Index(name="fecha_inicio_idx", columns={"fecha_inicio"}), @ORM\Index(name="fk_suscripcion_premium1_idx", columns={"premium_usuario_id"})})
 * @ORM\Entity
 */
class Suscripcion
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false, options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Groups({"suscripciones:read"})
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_inicio", type="date", nullable=false)
     * @Groups({"suscripciones:read"})
     */
    private $fechaInicio;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_fin", type="date", nullable=false)
     * @Groups({"suscripciones:read"})
     */
    private $fechaFin;

    /**
     * @var Premium
     *
     * @ORM\ManyToOne(targetEntity="Premium")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="premium_usuario_id", referencedColumnName="usuario_id")
     * })
     */
    private $premiumUsuario;

    public function getId(): int
    {
        return $this->id;
    }

    public function getFechaInicio(): \DateTime
    {
        return $this->fechaInicio;
    }

    public function setFechaInicio(\DateTime $fechaInicio): void
    {
        $this->fechaInicio = $fechaInicio;
    }

    public function getFechaFin(): \DateTime
    {
        return $this->fechaFin;
    }

    public function setFechaFin(\DateTime $fechaFin): void
    {
        $this->fechaFin = $fechaFin;
    }

    public function getPremiumUsuario(): Premium
    {
        return $this->premiumUsuario;
    }

    public function setPremiumUsuario(Premium $premiumUsuario): void
    {
        $this->premiumUsuario = $premiumUsuario;
    }




}
