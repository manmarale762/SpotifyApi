<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Eliminada
 *
 * @ORM\Table(name="eliminada", indexes={@ORM\Index(name="fk_eliminada_playlist1_idx", columns={"playlist_id"})})
 * @ORM\Entity
 */
class Eliminada
{
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_eliminacion", type="date", nullable=false)
     */
    private $fechaEliminacion;

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

    public function getFechaEliminacion(): \DateTime
    {
        return $this->fechaEliminacion;
    }

    public function setFechaEliminacion(\DateTime $fechaEliminacion): void
    {
        $this->fechaEliminacion = $fechaEliminacion;
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
