<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Patrocinada
 *
 * @ORM\Table(name="patrocinada", indexes={@ORM\Index(name="fk_patrocinada_playlist1_idx", columns={"playlist_id"})})
 * @ORM\Entity
 */
class Patrocinada
{
    /**
     * @var bool
     *
     * @ORM\Column(name="patrocinada", type="boolean", nullable=false, options={"default"="1"})
     */
    private $patrocinada = true;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_inicio", type="date", nullable=false)
     */
    private $fechaInicio;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="fecha_fin", type="date", nullable=true)
     */
    private $fechaFin;

    /**
     * @var Playlist
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="Playlist")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="playlist_id", referencedColumnName="id")
     * })
     */
    private $playlist;

    public function isPatrocinada(): bool
    {
        return $this->patrocinada;
    }

    public function setPatrocinada(bool $patrocinada): void
    {
        $this->patrocinada = $patrocinada;
    }

    public function getFechaInicio(): \DateTime
    {
        return $this->fechaInicio;
    }

    public function setFechaInicio(\DateTime $fechaInicio): void
    {
        $this->fechaInicio = $fechaInicio;
    }

    public function getFechaFin(): ?\DateTime
    {
        return $this->fechaFin;
    }

    public function setFechaFin(?\DateTime $fechaFin): void
    {
        $this->fechaFin = $fechaFin;
    }

    public function getPlaylist(): Playlist
    {
        return $this->playlist;
    }

    public function setPlaylist(Playlist $playlist): void
    {
        $this->playlist = $playlist;
    }




}
