<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Podcast
 *
 * @ORM\Table(name="podcast", indexes={@ORM\Index(name="anyo_idx", columns={"anyo"}), @ORM\Index(name="titulo_idx", columns={"titulo"})})
 * @ORM\Entity
 */
class Podcast
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false, options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Groups({"usuario:read", "dashboard:read","podcast:read","podcastFollow:read"})
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="titulo", type="string", length=100, nullable=false)
     * @Groups({"usuario:read", "dashboard:read","podcast:read","podcastFollow:read"})
     */
    private $titulo;

    /**
     * @var string|null
     * @Groups({"podcastFollow:read"})
     * @ORM\Column(name="imagen", type="string", length=255, nullable=true)
     */
    private $imagen;

    /**
     * @var string|null
     *
     * @ORM\Column(name="descripcion", type="text", length=65535, nullable=true)
     * @Groups({"usuario:read", "dashboard:read","podcast:read","podcastFollow:read"})
     */
    private $descripcion;

    /**
     * @var \DateTime|null
     * @Groups({"podcastFollow:read"})
     * @ORM\Column(name="anyo", type="datetime", nullable=true)
     *
     */
    private $anyo;

    /**
     *
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Usuario", mappedBy="podcast")
     */
    private $usuario = array();

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->usuario = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitulo(): string
    {
        return $this->titulo;
    }

    public function setTitulo(string $titulo): void
    {
        $this->titulo = $titulo;
    }

    public function getImagen(): ?string
    {
        return $this->imagen;
    }

    public function setImagen(?string $imagen): void
    {
        $this->imagen = $imagen;
    }

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(?string $descripcion): void
    {
        $this->descripcion = $descripcion;
    }

    public function getAnyo(): ?\DateTime
    {
        return $this->anyo;
    }

    public function setAnyo(?\DateTime $anyo): void
    {
        $this->anyo = $anyo;
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection|\Doctrine\Common\Collections\Collection
     */
    public function getUsuario()
    {
        return $this->usuario;
    }

    /**
     * @param \Doctrine\Common\Collections\ArrayCollection|\Doctrine\Common\Collections\Collection $usuario
     */
    public function setUsuario($usuario): void
    {
        $this->usuario = $usuario;
    }


}
