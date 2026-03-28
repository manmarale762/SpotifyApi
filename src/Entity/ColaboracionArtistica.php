<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ColaboracionArtistica
 *
 * @ORM\Table(name="colaboracion_artistica", indexes={@ORM\Index(name="fk_colaboracion_artistica_artista1_idx", columns={"artista_id"}), @ORM\Index(name="fk_colaboracion_artistica_artista2_idx", columns={"artista_colaborador_id"}), @ORM\Index(name="fk_colaboracion_artistica_cancion1_idx", columns={"cancion_id"})})
 * @ORM\Entity
 */
class ColaboracionArtistica
{
    /**
     * @var Cancion
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="Cancion")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="cancion_id", referencedColumnName="id")
     * })
     */
    private $cancion;

    /**
     * @var Artista
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="Artista")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="artista_colaborador_id", referencedColumnName="id")
     * })
     */
    private $artistaColaborador;

    /**
     * @var Artista
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="Artista")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="artista_id", referencedColumnName="id")
     * })
     */
    private $artista;

    public function getCancion(): Cancion
    {
        return $this->cancion;
    }

    public function setCancion(Cancion $cancion): void
    {
        $this->cancion = $cancion;
    }

    public function getArtistaColaborador(): Artista
    {
        return $this->artistaColaborador;
    }

    public function setArtistaColaborador(Artista $artistaColaborador): void
    {
        $this->artistaColaborador = $artistaColaborador;
    }

    public function getArtista(): Artista
    {
        return $this->artista;
    }

    public function setArtista(Artista $artista): void
    {
        $this->artista = $artista;
    }



}
