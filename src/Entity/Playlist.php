<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Playlist
 *
 * @ORM\Table(name="playlist", indexes={@ORM\Index(name="fk_playlist_usuario1_idx", columns={"usuario_id"})})
 * @ORM\Entity
 */
class Playlist
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false, options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Groups({"usuario:read", "dashboard:read","playlist:read","playlistPublicas:read","follow_playlist:read","playlistDetalles:read"})
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="titulo", type="string", length=150, nullable=false)
     * @Groups({"usuario:read", "dashboard:read","playlist:read","playlist:write","playlistPublicas:read","follow_playlist:read","playlistDetalles:read"})
     */
    private $titulo;

    /**
     * @var int|null
     *
     * @ORM\Column(name="numero_canciones", type="integer", nullable=true, options={"unsigned"=true})
     * @Groups({"usuario:read", "dashboard:read","playlist:read","playlist:write","playlistPublicas:read","follow_playlist:read","playlistDetalles:read"})
     */
    private $numeroCanciones;

    /**
     * @Groups({"playlist:read","playlist:write","follow_playlist:read","playlistDetalles:read"})
     * @var \DateTime|null
     *
     * @ORM\Column(name="fecha_creacion", type="date", nullable=true)
     */
    private $fechaCreacion;

    /**
     * @var Usuario
     * @ORM\ManyToOne(targetEntity="Usuario")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="usuario_id", referencedColumnName="id")
     * })
     * @Groups({"follow_playlist:read","playlistDetalles:read"})
     */
    private $usuario;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Usuario", mappedBy="playlist")
     */
    private $usuarioSeguidor = array();

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->usuarioSeguidor = new \Doctrine\Common\Collections\ArrayCollection();
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

    public function getNumeroCanciones(): ?int
    {
        return $this->numeroCanciones;
    }

    public function setNumeroCanciones(?int $numeroCanciones): void
    {
        $this->numeroCanciones = $numeroCanciones;
    }

    public function getFechaCreacion(): ?\DateTime
    {
        return $this->fechaCreacion;
    }

    public function setFechaCreacion(?\DateTime $fechaCreacion): void
    {
        $this->fechaCreacion = $fechaCreacion;
    }

    public function getUsuario(): Usuario
    {
        return $this->usuario;
    }

    public function setUsuario(Usuario $usuario): void
    {
        $this->usuario = $usuario;
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection|\Doctrine\Common\Collections\Collection
     */
    public function getUsuarioSeguidor()
    {
        return $this->usuarioSeguidor;
    }

    /**
     * @param \Doctrine\Common\Collections\ArrayCollection|\Doctrine\Common\Collections\Collection $usuarioSeguidor
     */
    public function setUsuarioSeguidor($usuarioSeguidor): void
    {
        $this->usuarioSeguidor = $usuarioSeguidor;
    }



}
