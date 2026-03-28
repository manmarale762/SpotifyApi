<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Usuario
 *
 * @ORM\Table(name="usuario", uniqueConstraints={@ORM\UniqueConstraint(name="email_UNIQUE", columns={"email"}), @ORM\UniqueConstraint(name="username_UNIQUE", columns={"username"})})
 * @ORM\Entity
 */
class Usuario
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false, options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Groups({"usuario:read","dashboard:read","follow_playlist:read","playlistDetalles:read","artistaFollow:read","albumFollow:read"})
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=45, nullable=false)
     * @Groups({"usuario:read", "usuario:write","dashboard:read","follow_playlist:read","playlistDetalles:read","artistaFollow:read","albumFollow:read"})
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=150, nullable=false)
     *
     * @Groups("usuario:write")
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=150, nullable=false)
     * @Groups({"usuario:read", "usuario:write","dashboard:read"})
     */
    private $email;

    /**
     * @var string|null
     *
     * @ORM\Column(name="genero", type="string", length=1, nullable=true)
     * @Groups({"usuario:read", "usuario:write","dashboard:read"})
     */
    private $genero;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_nacimiento", type="date", nullable=false)
     * @Groups({"usuario:read", "usuario:write","dashboard:read"})
     */
    private $fechaNacimiento;

    /**
     * @var string|null
     *
     * @ORM\Column(name="pais", type="string", length=45, nullable=true)
     * @Groups({"usuario:read", "usuario:write","dashboard:read"})
     */
    private $pais;

    /**
     * @var string|null
     *
     * @ORM\Column(name="codigo_postal", type="string", length=20, nullable=true)
     * @Groups({"usuario:read", "usuario:write","dashboard:read"})
     */
    private $codigoPostal;

    /**
     * @var \Doctrine\Common\Collections\Collection
     * @ORM\ManyToMany(targetEntity="Cancion", inversedBy="usuario")
     * @ORM\JoinTable(name="guarda_cancion",
     *   joinColumns={
     *     @ORM\JoinColumn(name="usuario_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="cancion_id", referencedColumnName="id")
     *   }
     * )
     * @Groups({"usuario_cancion:read"})
     */
    private $cancion = array();

    /**
     * @var \Doctrine\Common\Collections\Collection
     * @Groups("dashboard:read")
     * @ORM\ManyToMany(targetEntity="Podcast", inversedBy="usuario")
     * @ORM\JoinTable(name="podcast_usuario",
     *   joinColumns={
     *     @ORM\JoinColumn(name="usuario_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="podcast_id", referencedColumnName="id")
     *   }
     * )
     */
    private $podcast = array();

    /**
     * @var \Doctrine\Common\Collections\Collection
     *@Groups("dashboard:read","albumFollow:read")
     * @ORM\ManyToMany(targetEntity="Album", inversedBy="usuario")
     * @ORM\JoinTable(name="sigue_album",
     *   joinColumns={
     *     @ORM\JoinColumn(name="usuario_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="album_id", referencedColumnName="id")
     *   }
     * )
     */
    private $album = array();

    /**
     * @var \Doctrine\Common\Collections\Collection
     *@Groups("dashboard:read", "artistaFollow:read")
     * @ORM\ManyToMany(targetEntity="Artista", inversedBy="usuario")
     * @ORM\JoinTable(name="sigue_artista",
     *   joinColumns={
     *     @ORM\JoinColumn(name="usuario_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="artista_id", referencedColumnName="id")
     *   }
     * )
     */
    private $artista = array();

    /**
     * @var \Doctrine\Common\Collections\Collection
     *@Groups("dashboard:read","playlistDetalles:read")
     * @ORM\ManyToMany(targetEntity="Playlist", inversedBy="usuarioSeguidor")
     * @ORM\JoinTable(name="sigue_playlist",
     *   joinColumns={
     *     @ORM\JoinColumn(name="usuario_seguidor_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="playlist_id", referencedColumnName="id")
     *   }
     * )
     */
    private $playlist = array();

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->cancion = new \Doctrine\Common\Collections\ArrayCollection();
        $this->podcast = new \Doctrine\Common\Collections\ArrayCollection();
        $this->album = new \Doctrine\Common\Collections\ArrayCollection();
        $this->artista = new \Doctrine\Common\Collections\ArrayCollection();
        $this->playlist = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getGenero(): ?string
    {
        return $this->genero;
    }

    public function setGenero(?string $genero): void
    {
        $this->genero = $genero;
    }

    public function getFechaNacimiento(): \DateTime
    {
        return $this->fechaNacimiento;
    }

    public function setFechaNacimiento(\DateTime $fechaNacimiento): void
    {
        $this->fechaNacimiento = $fechaNacimiento;
    }

    public function getPais(): ?string
    {
        return $this->pais;
    }

    public function setPais(?string $pais): void
    {
        $this->pais = $pais;
    }

    public function getCodigoPostal(): ?string
    {
        return $this->codigoPostal;
    }

    public function setCodigoPostal(?string $codigoPostal): void
    {
        $this->codigoPostal = $codigoPostal;
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection|\Doctrine\Common\Collections\Collection
     */
    public function getCancion()
    {
        return $this->cancion;
    }

    /**
     * @param \Doctrine\Common\Collections\ArrayCollection|\Doctrine\Common\Collections\Collection $cancion
     */
    public function setCancion($cancion): void
    {
        $this->cancion = $cancion;
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection|\Doctrine\Common\Collections\Collection
     */
    public function getPodcast()
    {
        return $this->podcast;
    }

    /**
     * @param \Doctrine\Common\Collections\ArrayCollection|\Doctrine\Common\Collections\Collection $podcast
     */
    public function setPodcast($podcast): void
    {
        $this->podcast = $podcast;
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection|\Doctrine\Common\Collections\Collection
     */
    public function getAlbum()
    {
        return $this->album;
    }

    /**
     * @param \Doctrine\Common\Collections\ArrayCollection|\Doctrine\Common\Collections\Collection $album
     */
    public function setAlbum($album): void
    {
        $this->album = $album;
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection|\Doctrine\Common\Collections\Collection
     */
    public function getArtista()
    {
        return $this->artista;
    }

    /**
     * @param \Doctrine\Common\Collections\ArrayCollection|\Doctrine\Common\Collections\Collection $artista
     */
    public function setArtista($artista): void
    {
        $this->artista = $artista;
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection|\Doctrine\Common\Collections\Collection
     */
    public function getPlaylist()
    {
        return $this->playlist;
    }

    /**
     * @param \Doctrine\Common\Collections\ArrayCollection|\Doctrine\Common\Collections\Collection $playlist
     */
    public function setPlaylist($playlist): void
    {
        $this->playlist = $playlist;
    }



}
