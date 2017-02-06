<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Utente
 *
 * @ORM\Table(name="utente")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UtenteRepository")
 */
class Utente
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=255)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="ruolo", type="string", length=255)
     */
    private $ruolo;

    /**
     * @var string
     *
     * @ORM\Column(name="nome", type="string", length=255)
     */
    private $nome;

    /**
     * @var string
     *
     * @ORM\Column(name="cognome", type="string", length=255)
     */
    private $cognome;

    /**
     * @var string
     *
     * @ORM\Column(name="residenza", type="string", length=255)
     */
    private $residenza;

    /**
     * @var string
     *
     * @ORM\Column(name="cellulare", type="string", length=255)
     */
    private $cellulare;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     */
    private $email;

    /**
     * @var bool
     *
     * @ORM\Column(name="consenso", type="boolean")
     */
    private $consenso;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dataIscrizione", type="datetime")
     */
    private $dataIscrizione;

    /**
     * @var string
     *
     * @ORM\Column(name="bibliotecario", type="string", length=255)
     */
    private $bibliotecario;

    /**
     * @var string
     *
     * @ORM\Column(name="tipoDocumento", type="string", length=255)
     */
    private $tipoDocumento;

    /**
     * @var string
     *
     * @ORM\Column(name="emessoDa", type="string", length=255)
     */
    private $emessoDa;

    /**
     * @var string
     *
     * @ORM\Column(name="numeroDocumento", type="string", length=255)
     */
    private $numeroDocumento;

    /**
     * @var bool
     *
     * @ORM\Column(name="cancellato", type="boolean")
     */
    private $cancellato;


    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dataCancellazione", type="datetime", nullable=true)
     */
    private $dataCancellazione;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set username
     *
     * @param string $username
     * @return Utente
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string 
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set ruolo
     *
     * @param string $ruolo
     * @return Utente
     */
    public function setRuolo($ruolo)
    {
        $this->ruolo = $ruolo;

        return $this;
    }

    /**
     * Get ruolo
     *
     * @return string 
     */
    public function getRuolo()
    {
        return $this->ruolo;
    }

    /**
     * Set nome
     *
     * @param string $nome
     * @return Utente
     */
    public function setNome($nome)
    {
        $this->nome = $nome;

        return $this;
    }

    /**
     * Get nome
     *
     * @return string 
     */
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * Set cognome
     *
     * @param string $cognome
     * @return Utente
     */
    public function setCognome($cognome)
    {
        $this->cognome = $cognome;

        return $this;
    }

    /**
     * Get cognome
     *
     * @return string 
     */
    public function getCognome()
    {
        return $this->cognome;
    }

    /**
     * Set residenza
     *
     * @param string $residenza
     * @return Utente
     */
    public function setResidenza($residenza)
    {
        $this->residenza = $residenza;

        return $this;
    }

    /**
     * Get residenza
     *
     * @return string 
     */
    public function getResidenza()
    {
        return $this->residenza;
    }

    /**
     * Set cellulare
     *
     * @param string $cellulare
     * @return Utente
     */
    public function setCellulare($cellulare)
    {
        $this->cellulare = $cellulare;

        return $this;
    }

    /**
     * Get cellulare
     *
     * @return string 
     */
    public function getCellulare()
    {
        return $this->cellulare;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Utente
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set consenso
     *
     * @param boolean $consenso
     * @return Utente
     */
    public function setConsenso($consenso)
    {
        $this->consenso = $consenso;

        return $this;
    }

    /**
     * Get consenso
     *
     * @return boolean 
     */
    public function getConsenso()
    {
        return $this->consenso;
    }

    /**
     * Set dataIscrizione
     *
     * @param \DateTime $dataIscrizione
     * @return Utente
     */
    public function setDataIscrizione($dataIscrizione)
    {
        $this->dataIscrizione = $dataIscrizione;

        return $this;
    }

    /**
     * Get dataIscrizione
     *
     * @return \DateTime 
     */
    public function getDataIscrizione()
    {
        return $this->dataIscrizione;
    }

    /**
     * Set bibliotecario
     *
     * @param string $bibliotecario
     * @return Utente
     */
    public function setBibliotecario($bibliotecario)
    {
        $this->bibliotecario = $bibliotecario;

        return $this;
    }

    /**
     * Get bibliotecario
     *
     * @return string 
     */
    public function getBibliotecario()
    {
        return $this->bibliotecario;
    }

    /**
     * Set tipoDocumento
     *
     * @param string $tipoDocumento
     * @return Utente
     */
    public function setTipoDocumento($tipoDocumento)
    {
        $this->tipoDocumento = $tipoDocumento;

        return $this;
    }

    /**
     * Get tipoDocumento
     *
     * @return string 
     */
    public function getTipoDocumento()
    {
        return $this->tipoDocumento;
    }

    /**
     * Set emessoDa
     *
     * @param string $emessoDa
     * @return Utente
     */
    public function setEmessoDa($emessoDa)
    {
        $this->emessoDa = $emessoDa;

        return $this;
    }

    /**
     * Get emessoDa
     *
     * @return string 
     */
    public function getEmessoDa()
    {
        return $this->emessoDa;
    }

    /**
     * Set numeroDocumento
     *
     * @param string $numeroDocumento
     * @return Utente
     */
    public function setNumeroDocumento($numeroDocumento)
    {
        $this->numeroDocumento = $numeroDocumento;

        return $this;
    }

    /**
     * Get numeroDocumento
     *
     * @return string 
     */
    public function getNumeroDocumento()
    {
        return $this->numeroDocumento;
    }

    /**
     * Set cancellato
     *
     * @param boolean $cancellato
     * @return Utente
     */
    public function setCancellato($cancellato)
    {
        $this->cancellato = $cancellato;

        return $this;
    }

    /**
     * Get cancellato
     *
     * @return boolean 
     */
    public function getCancellato()
    {
        return $this->cancellato;
    }

    /**
     * Set dataCancellazione
     *
     * @param \DateTime $dataCancellazione
     * @return Utente
     */
    public function setDataCancellazione($dataCancellazione)
    {
        $this->dataCancellazione = $dataCancellazione;

        return $this;
    }

    /**
     * Get dataCancellazione
     *
     * @return \DateTime 
     */
    public function getDataCancellazione()
    {
        return $this->dataCancellazione;
    }
}
