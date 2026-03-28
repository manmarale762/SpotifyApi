<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TarjetaCredito
 *
 * @ORM\Table(name="tarjeta_credito", uniqueConstraints={@ORM\UniqueConstraint(name="numero_tarjeta_UNIQUE", columns={"numero_tarjeta"})}, indexes={@ORM\Index(name="fk_tarjeta_credito_forma_pago1_idx", columns={"forma_pago_id"})})
 * @ORM\Entity
 */
class TarjetaCredito
{
    /**
     * @var string
     *
     * @ORM\Column(name="numero_tarjeta", type="string", length=20, nullable=false)
     */
    private $numeroTarjeta;

    /**
     * @var bool
     *
     * @ORM\Column(name="mes_caducidad", type="boolean", nullable=false)
     */
    private $mesCaducidad;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="anyo_caducidad", type="date", nullable=false)
     */
    private $anyoCaducidad;

    /**
     * @var int
     *
     * @ORM\Column(name="codigo_seguridad", type="smallint", nullable=false, options={"unsigned"=true})
     */
    private $codigoSeguridad;

    /**
     * @var FormaPago
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="FormaPago")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="forma_pago_id", referencedColumnName="id")
     * })
     */
    private $formaPago;


}
