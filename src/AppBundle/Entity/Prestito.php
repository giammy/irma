<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\ExecutionContextInterface;


/**
 * Prestito
 *
 * @ORM\Table(name="prestito")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PrestitoRepository")
 *
 */
class Prestito
{
     public function isCollocazioneValida($collocazione) {
	 if (strlen($collocazione)!=9 or
	     !ctype_alpha(substr($collocazione, 0, 3)) or
	     $collocazione[3]!='.' or
	     !ctype_alpha(substr($collocazione, 4, 1)) or
	     $collocazione[5]!='.' or
	     !ctype_digit(substr($collocazione, 6, 3)) ) {
	         return true;
	 } else {
	     return false;
	 }
     }

     public function validateCollocazione1(ExecutionContextInterface $context)
     {
         // check constraints FORMATO "DON.F.924"
	 $collocazione1 = $this->getCollocazione1();
	 if ($this->isCollocazioneValida($collocazione1)) {
             $context->buildViolation('La collocazione deve essere nel formato DON.F.924')
                ->atPath('collocazione1')
                ->addViolation();
	 }
     }

     public function validateCollocazione2(ExecutionContextInterface $context)
     {
	 $titolo2 = $this->getTitolo2();
	 $collocazione2 = $this->getCollocazione2();
	 if (!is_null($titolo2)) {
	     if ($this->isCollocazioneValida($collocazione2)) {
                 $context->buildViolation('La collocazione deve essere nel formato DON.F.924')
                     ->atPath('collocazione2')
                     ->addViolation();
	     }
        }
     }


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
     * @ORM\Column(name="protocollo", type="string", length=255)
     */
    private $protocollo;

    /**
     * @var string
     *
     * @ORM\Column(name="titolo1", type="string", length=255)
     */
    private $titolo1;

    /**
     * @var string
     *
     * @ORM\Column(name="titolo2", type="string", length=255, nullable=true)
     */
    private $titolo2;

    /**
     * @var string
     *
     * @ORM\Column(name="collocazione1", type="string", length=255)
     */
    private $collocazione1;

    /**
     * @var string
     *
     * @ORM\Column(name="collocazione2", type="string", length=255, nullable=true)
     */
    private $collocazione2;


    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dataPrestito", type="datetime")
     */
    private $dataPrestito;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dataRestituzione", type="datetime", nullable=true)
     */
    private $dataRestituzione;

    /**
     * @var string
     *
     * @ORM\Column(name="richiestaProroga", type="string", length=4096, nullable=true)
     */
    private $richiestaProroga;

    /**
     * @var string
     *
     * @ORM\Column(name="bibliotecarioPrestito", type="string", length=255, nullable=true)
     */
    private $bibliotecarioPrestito;

    /**
     * @var string
     *
     * @ORM\Column(name="bibliotecarioRestituzione", type="string", length=255, nullable=true)
     */
    private $bibliotecarioRestituzione;

    /**
     * @var string
     *
     * @ORM\Column(name="note", type="string", length=4096, nullable=true)
     */
    private $note;

    /**
     * @var string
     *
     * @ORM\Column(name="utente", type="string", length=255)
     */
    private $utente;


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
     * Set protocollo
     *
     * @param string $protocollo
     * @return Prestito
     */
    public function setProtocollo($protocollo)
    {
        $this->protocollo = $protocollo;

        return $this;
    }

    /**
     * Get protocollo
     *
     * @return string 
     */
    public function getProtocollo()
    {
        return $this->protocollo;
    }

    /**
     * Set titolo1
     *
     * @param string $titolo1
     * @return Prestito
     */
    public function setTitolo1($titolo1)
    {
        $this->titolo1 = $titolo1;

        return $this;
    }

    /**
     * Get titolo1
     *
     * @return string 
     */
    public function getTitolo1()
    {
        return $this->titolo1;
    }

    /**
     * Set titolo2
     *
     * @param string $titolo2
     * @return Prestito
     */
    public function setTitolo2($titolo2)
    {
        $this->titolo2 = $titolo2;

        return $this;
    }

    /**
     * Get titolo2
     *
     * @return string 
     */
    public function getTitolo2()
    {
        return $this->titolo2;
    }

    /**
     * Set dataPrestito
     *
     * @param \DateTime $dataPrestito
     * @return Prestito
     */
    public function setDataPrestito($dataPrestito)
    {
        $this->dataPrestito = $dataPrestito;

        return $this;
    }

    /**
     * Get dataPrestito
     *
     * @return \DateTime 
     */
    public function getDataPrestito()
    {
        return $this->dataPrestito;
    }

    /**
     * Set dataRestituzione
     *
     * @param \DateTime $dataRestituzione
     * @return Prestito
     */
    public function setDataRestituzione($dataRestituzione)
    {
        $this->dataRestituzione = $dataRestituzione;

        return $this;
    }

    /**
     * Get dataRestituzione
     *
     * @return \DateTime 
     */
    public function getDataRestituzione()
    {
        return $this->dataRestituzione;
    }

    /**
     * Set richiestaProroga
     *
     * @param string $richiestaProroga
     * @return Prestito
     */
    public function setRichiestaProroga($richiestaProroga)
    {
        $this->richiestaProroga = $richiestaProroga;

        return $this;
    }

    /**
     * Get richiestaProroga
     *
     * @return string 
     */
    public function getRichiestaProroga()
    {
        return $this->richiestaProroga;
    }

    /**
     * Set bibliotecarioPrestito
     *
     * @param string $bibliotecarioPrestito
     * @return Prestito
     */
    public function setBibliotecarioPrestito($bibliotecarioPrestito)
    {
        $this->bibliotecarioPrestito = $bibliotecarioPrestito;

        return $this;
    }

    /**
     * Get bibliotecarioPrestito
     *
     * @return string 
     */
    public function getBibliotecarioPrestito()
    {
        return $this->bibliotecarioPrestito;
    }

    /**
     * Set bibliotecarioRestituzione
     *
     * @param string $bibliotecarioRestituzione
     * @return Prestito
     */
    public function setBibliotecarioRestituzione($bibliotecarioRestituzione)
    {
        $this->bibliotecarioRestituzione = $bibliotecarioRestituzione;

        return $this;
    }

    /**
     * Get bibliotecarioRestituzione
     *
     * @return string 
     */
    public function getBibliotecarioRestituzione()
    {
        return $this->bibliotecarioRestituzione;
    }

    /**
     * Set note
     *
     * @param string $note
     * @return Prestito
     */
    public function setNote($note)
    {
        $this->note = $note;

        return $this;
    }

    /**
     * Get note
     *
     * @return string 
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * Set utente
     *
     * @param string $utente
     * @return Prestito
     */
    public function setUtente($utente)
    {
        $this->utente = $utente;

        return $this;
    }

    /**
     * Get utente
     *
     * @return string 
     */
    public function getUtente()
    {
        return $this->utente;
    }

    /**
     * Set collocazione1
     *
     * @param string $collocazione1
     * @return Prestito
     */
    public function setCollocazione1($collocazione1)
    {
        $this->collocazione1 = $collocazione1;

        return $this;
    }

    /**
     * Get collocazione1
     *
     * @return string 
     */
    public function getCollocazione1()
    {
        return $this->collocazione1;
    }

    /**
     * Set collocazione2
     *
     * @param string $collocazione2
     * @return Prestito
     */
    public function setCollocazione2($collocazione2)
    {
        $this->collocazione2 = $collocazione2;

        return $this;
    }

    /**
     * Get collocazione2
     *
     * @return string 
     */
    public function getCollocazione2()
    {
        return $this->collocazione2;
    }
}
