<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Letra
 *
 * @ORM\Table(name="letra", uniqueConstraints={@ORM\UniqueConstraint(name="cancion_id_UNIQUE", columns={"cancion_id"})})
 * @ORM\Entity
 */
class Letra
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false, options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="ruta", type="string", length=255, nullable=false)
     */
    private $ruta;

    /**
     * @var Cancion
     *
     * @ORM\ManyToOne(targetEntity="Cancion")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="cancion_id", referencedColumnName="id")
     * })
     */
    private $cancion;

    public function getId(): int
    {
        return $this->id;
    }

    public function getRuta(): string
    {
        return $this->ruta;
    }

    public function setRuta(string $ruta): void
    {
        $this->ruta = $ruta;
    }

    public function getCancion(): Cancion
    {
        return $this->cancion;
    }

    public function setCancion(Cancion $cancion): void
    {
        $this->cancion = $cancion;
    }




}
