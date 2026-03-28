<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Idioma
 *
 * @ORM\Table(name="idioma", indexes={@ORM\Index(name="nombre_idx", columns={"nombre"})})
 * @ORM\Entity
 */
class Idioma
{
    /**
     * @var int
     * @Groups("configuracion:read","config:write")
     * @ORM\Column(name="id", type="integer", nullable=false, options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=15, nullable=false)
     * @Groups({"usuario:read", "dashboard:read","configuracion:read","config:write"})
     */
    private $nombre;


    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getNombre(): string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): void
    {
        $this->nombre = $nombre;
    }




}
