<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Capitulo
 *
 * @ORM\Table(name="capitulo", indexes={@ORM\Index(name="fecha", columns={"fecha"}), @ORM\Index(name="fk_capitulo_podcast1_idx", columns={"podcast_id"}), @ORM\Index(name="titulo", columns={"titulo"})})
 * @ORM\Entity
 */
class Capitulo
{
    /**
     * @var int
     * @Groups({"podcastCaps:read"})
     * @ORM\Column(name="id", type="integer", nullable=false, options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     * @Groups({"podcastCaps:read"})
     *
     * @ORM\Column(name="titulo", type="string", length=100, nullable=false)
     */
    private $titulo;

    /**
     * @var string|null
     * @Groups({"podcastCaps:read"})
     *
     * @ORM\Column(name="descripcion", type="text", length=65535, nullable=true)
     */
    private $descripcion;

    /**
     * @var int
     * @Groups({"podcastCaps:read"})
     *
     * @ORM\Column(name="duracion", type="integer", nullable=false, options={"unsigned"=true})
     */
    private $duracion;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha", type="date", nullable=false)
     */
    private $fecha;

    /**
     * @var int
     *
     * @ORM\Column(name="numero_reproducciones", type="integer", nullable=false, options={"unsigned"=true})
     */
    private $numeroReproducciones;

    /**
     * @var Podcast
     *
     * @ORM\ManyToOne(targetEntity="Podcast")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="podcast_id", referencedColumnName="id")
     * })
     */
    private $podcast;

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

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(?string $descripcion): void
    {
        $this->descripcion = $descripcion;
    }

    public function getDuracion(): int
    {
        return $this->duracion;
    }

    public function setDuracion(int $duracion): void
    {
        $this->duracion = $duracion;
    }

    public function getFecha(): \DateTime
    {
        return $this->fecha;
    }

    public function setFecha(\DateTime $fecha): void
    {
        $this->fecha = $fecha;
    }

    public function getNumeroReproducciones(): int
    {
        return $this->numeroReproducciones;
    }

    public function setNumeroReproducciones(int $numeroReproducciones): void
    {
        $this->numeroReproducciones = $numeroReproducciones;
    }

    public function getPodcast(): Podcast
    {
        return $this->podcast;
    }

    public function setPodcast(Podcast $podcast): void
    {
        $this->podcast = $podcast;
    }



}
