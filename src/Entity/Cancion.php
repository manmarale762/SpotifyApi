<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Cancion
 *
 * @ORM\Table(name="cancion", indexes={@ORM\Index(name="fk_cancion_album1_idx", columns={"album_id"}), @ORM\Index(name="titulo_idx", columns={"titulo"})})
 * @ORM\Entity
 */
class Cancion
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false, options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Groups({"usuario:read", "dashboard:read","cancion:read","albumCanciones:read","usuario_cancion:read","Artistacancion:read"})
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="titulo", type="string", length=255, nullable=false)
     * @Groups({"usuario:read", "dashboard:read","cancion:read","cancion:write","albumCanciones:read","usuario_cancion:read","Artistacancion:read"})
     */
    private $titulo;

    /**
     * @var int
     *
     * @ORM\Column(name="duracion", type="integer", nullable=false)
     * @Groups({"usuario:read", "dashboard:read","cancion:read","cancion:write","albumCanciones:read","usuario_cancion:read","Artistacancion:read"})
     */
    private $duracion;

    /**
     * @var string|null
     *
     * @ORM\Column(name="ruta", type="string", length=255, nullable=true)
     */
    private $ruta;

    /**
     * @var int
     *
     * @ORM\Column(name="numero_reproducciones", type="integer", nullable=false)
     * @Groups({"usuario:read", "dashboard:read","cancion:read","albumCanciones:read","usuario_cancion:read","Artistacancion:read"})
     */
    private $numeroReproducciones;

    /**
     * @var Album
     * @Groups({"usuario:read","cancion:read","albumCanciones:read"})
     * @ORM\ManyToOne(targetEntity="Album")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="album_id", referencedColumnName="id")
     * })
     */
    private $album;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Usuario", mappedBy="cancion")
     */
    private $usuario = array();

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Premium", inversedBy="cancion")
     * @ORM\JoinTable(name="usuario_descarga_cancion",
     *   joinColumns={
     *     @ORM\JoinColumn(name="cancion_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="premium_usuario_id", referencedColumnName="usuario_id")
     *   }
     * )
     */
    private $premiumUsuario = array();

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->usuario = new \Doctrine\Common\Collections\ArrayCollection();
        $this->premiumUsuario = new \Doctrine\Common\Collections\ArrayCollection();
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

    public function getDuracion(): int
    {
        return $this->duracion;
    }

    public function setDuracion(int $duracion): void
    {
        $this->duracion = $duracion;
    }

    public function getRuta(): ?string
    {
        return $this->ruta;
    }

    public function setRuta(?string $ruta): void
    {
        $this->ruta = $ruta;
    }

    public function getNumeroReproducciones(): int
    {
        return $this->numeroReproducciones;
    }

    public function setNumeroReproducciones(int $numeroReproducciones): void
    {
        $this->numeroReproducciones = $numeroReproducciones;
    }

    public function getAlbum(): Album
    {
        return $this->album;
    }

    public function setAlbum(Album $album): void
    {
        $this->album = $album;
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

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection|\Doctrine\Common\Collections\Collection
     */
    public function getPremiumUsuario()
    {
        return $this->premiumUsuario;
    }

    /**
     * @param \Doctrine\Common\Collections\ArrayCollection|\Doctrine\Common\Collections\Collection $premiumUsuario
     */
    public function setPremiumUsuario($premiumUsuario): void
    {
        $this->premiumUsuario = $premiumUsuario;
    }


}
