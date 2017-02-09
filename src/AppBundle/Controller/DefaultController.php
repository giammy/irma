<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\ExecutionContextInterface;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

use AppBundle\Entity\Utente;
use AppBundle\Entity\Prestito;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', array(
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
        ));
    }


    /**
     * @Route("/stat", name="adminhomepage")
     */
    public function adminindexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/adminindex.html.twig', array(
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
        ));
    }


    /**
     * @Route("/mostra/utente/{username}", name="mostrautente", defaults={"username"="T.U.T.T.I"})
     */
    public function mostrautenteAction(Request $request, $username = "T.U.T.T.I")
    {
        $repo = $this->getDoctrine()->getrepository('AppBundle:Utente');
        $utenti = $repo->findAll();

        return $this->render('default/mostrautente.html.twig', array(
            'utenti' => $utenti,
        ));
    }

    public function createUsername($nome, $cognome) {
        $repoUtente = $this->getDoctrine()->getRepository('AppBundle:Utente');
	$count = 0;
	do {
	    $username = $count>0 ? $nome.".".$cognome.$count : $nome.".".$cognome;
	    $username = strtolower($username);
	    $count++;
	    $utente = $repoUtente->findOneByUsername($username);
	} while (!is_null($utente));
	return $username;
    }

    /**
     * @Route("/elimina/utente/{username}", name="eliminautente")
     */
    public function eliminautenteAction(Request $request, $username) {
        $repoUtente = $this->getDoctrine()->getRepository('AppBundle:Utente');
	$utente = $repoUtente->findOneByUsername($username);
	if (!is_null($utente)) {
	    $em = $this->getDoctrine()->getManager();
	    $em->remove($utente);
	    $em->flush();
	}
	return $this->redirectToRoute('mostrautente');
    }


    /**
     * @Route("/edit/utente/{username}", name="editutente", defaults={"username"="N.U.O.V.O"})
     */
    public function editutenteAction(Request $request, $username = "N.U.O.V.O")
    {
        $repoUtente = $this->getDoctrine()->getRepository('AppBundle:Utente');
	if ($username === "N.U.O.V.O") {
            $utente = new Utente();
	} else {
	    $utente = $repoUtente->findOneByUsername($username);
	}

	$form = $this->createFormBuilder();
	$form = $form->add('cognome', TextType::class);
	$form = $form->add('nome', TextType::class);
	$form = $form->add('residenza', TextType::class);
	$form = $form->add('cellulare', TextType::class);
	$form = $form->add('email', TextType::class);
	$form = $form->add('consenso', CheckboxType::class);
	$form = $form->add('tipoDocumento', TextType::class);
	$form = $form->add('emessoDa', TextType::class);
	$form = $form->add('numeroDocumento', TextType::class);
	$form = $form->getForm();
	$form->handleRequest($request);

	if ($form->isSubmitted() && $form->isValid()) {
	    $data = $form->getData();
	    $utente->setCognome($data['cognome']);
	    $utente->setNome($data['nome']);
	    $utente->setResidenza($data['residenza']);
	    $utente->setCellulare($data['cellulare']);
	    $utente->setEmail($data['email']);
	    $utente->setConsenso($data['consenso']);
	    $utente->setTipoDocumento($data['tipoDocumento']);
	    $utente->setEmessoDa($data['emessoDa']);
	    $utente->setNumeroDocumento($data['numeroDocumento']);
	    $utente->setCancellato(false);

	    // campi generati
	    if (is_null($utente->getDataIscrizione())) {
	        $utente->setUsername($this->createUsername($data['nome'], $data['cognome']));
	        $utente->setRuolo("UTENTE");
                $utente->setBibliotecario($this->get('security.context')->getToken()->getUser()->getUsername());
		$utente->setDataIscrizione(new \DateTime(date('Y-m-d H:i:s')));
	    }		
	    $em = $this->getDoctrine()->getManager();
	    $em->persist($utente);
	    $em->flush();

	    return $this->redirectToRoute('mostrautente');
	}

        return $this->render('default/editutente.html.twig', array(
            'form' => $form->createView(),
	    'utente' => $utente,
	    ));
    }




    /**
     * @Route("/mostra/prestito/{idprestito}", name="mostraprestito", defaults={"idprestito"="T.U.T.T.I"})
     */
    public function mostraprestitoAction(Request $request, $idprestito = "T.U.T.T.I")
    {
        $repo = $this->getDoctrine()->getrepository('AppBundle:Prestito');
        $prestiti = $repo->findAll();

        return $this->render('default/mostraprestito.html.twig', array(
            'prestiti' => $prestiti,
        ));
    }

    /**
     * @Route("/mostra/prestitoincorso", name="mostraprestitoincorso")
     */
    public function mostraprestitoincorsoAction(Request $request)
    {
        $repo = $this->getDoctrine()->getrepository('AppBundle:Prestito');
        $prestiti = $repo->findAll();
	$prestitiInCorso = array_filter ($prestiti, function($p){ return is_null($p->getDataRestituzione());});

        return $this->render('default/mostraprestito.html.twig', array(
            'prestiti' => $prestitiInCorso,
        ));
    }



    /**
     * @Route("/edit/prestito/{id}", name="editprestito")
     */
    public function editprestitoAction(Request $request, $id)
    {
        // edit prestito
        $repo = $this->getDoctrine()->getrepository('AppBundle:Prestito');
        $prestito = $repo->findOneById($id);

	$form = $this->createFormBuilder(
	      null, array(
              	    'data_class' => 'AppBundle\Entity\Prestito',
        	    'constraints' => array(new Assert\Callback('validateCollocazione'))
              )
        );
	$form = $form->add('protocollo', TextType::class);
	$form = $form->add('titolo1', TextType::class);
	$form = $form->add('titolo2', TextType::class);
	$form = $form->add('collocazione', TextType::class);
	$form = $form->add('note', TextType::class);
	$form = $form->add('richiestaProroga', TextType::class);
	$form = $form->getForm();
	$form->handleRequest($request);

	if ($form->isSubmitted() && $form->isValid()) {
	    $data = $form->getData();
	    $prestito->setProtocollo($data->getProtocollo());
	    $prestito->setTitolo1($data->getTitolo1());
	    $prestito->setTitolo2($data->getTitolo2());
	    $prestito->setCollocazione($data->getCollocazione());
	    $prestito->setNote($data->getNote());
	    $prestito->setRichiestaProroga($data->getRichiestaProroga());
	    $em = $this->getDoctrine()->getManager();
	    $em->persist($prestito);
	    $em->flush();
	    // vai alla pagina "mostra prestiti in corso"
	    return $this->redirectToRoute('mostraprestitoincorso');
	}

        return $this->render('default/editprestito.html.twig', array(
            'form' => $form->createView(),
            'prestito' => $prestito,
        ));
    }


    /**
     * @Route("/edit/prestito/prolungato/{id}", name="prestitoprolungato")
     */
    public function prestitoprolungatoAction(Request $request, $id)
    {
        $repo = $this->getDoctrine()->getrepository('AppBundle:Prestito');
        $prestito = $repo->findOneById($id);
	$date = new \DateTime(date('Y-m-d H:i:s'));
	$dataStr = date_format($date, 'd/m/Y');
	$prestito->setRichiestaProroga("Proroga in data " . $dataStr);
	$em = $this->getDoctrine()->getManager();
	$em->persist($prestito);
	$em->flush();

        // vai alla pagina "mostra prestiti in corso"
	return $this->redirectToRoute('mostraprestitoincorso');
    }

    /**
     * @Route("/edit/prestito/restituito/{id}", name="prestitorestituito")
     */
    public function prestitorestituitoAction(Request $request, $id)
    {
        $repo = $this->getDoctrine()->getrepository('AppBundle:Prestito');
        $prestito = $repo->findOneById($id);
	$prestito->setDataRestituzione(new \DateTime(date('Y-m-d H:i:s')));
	$em = $this->getDoctrine()->getManager();
	$em->persist($prestito);
	$em->flush();

        // vai alla pagina "mostra prestiti in corso"
	return $this->redirectToRoute('mostraprestitoincorso');
    }



    /**
     * @Route("/edit/nuovoprestito/", name="nuovoprestito")
     */
    public function nuovoprestitoAction(Request $request)
    {
        // nuovo prestito: mostra la pagina utenti, da cui verra' scelto l'utente a cui fare il prestito
	return $this->redirectToRoute('mostrautente');
    }

    /**
     * @Route("/edit/nuovoprestitoautente/{username}", name="nuovoprestitoautente")
     */
    public function nuovoprestitoautenteAction(Request $request, $username)
    {
        // nuovo prestito all'utente selezionato

        $repoUtente = $this->getDoctrine()->getRepository('AppBundle:Utente');
	$utente = $repoUtente->findOneByUsername($username);

	$prestito = new Prestito();
	$form = $this->createFormBuilder(
	      null, array(
              	    'data_class' => 'AppBundle\Entity\Prestito',
        	    'constraints' => array(new Assert\Callback('validateCollocazione'))
              )
        );
	$form = $form->add('protocollo', TextType::class);
	$form = $form->add('titolo1', TextType::class);
	$form = $form->add('titolo2', TextType::class);
	$form = $form->add('collocazione', TextType::class);
	$form = $form->add('note', TextType::class);
	$form = $form->getForm();
	$form->handleRequest($request);

	if ($form->isSubmitted() && $form->isValid()) {
	    $data = $form->getData();
	    $prestito->setProtocollo($data->getProtocollo());
	    $prestito->setTitolo1($data->getTitolo1());
	    $prestito->setTitolo2($data->getTitolo2());
	    $prestito->setCollocazione($data->getCollocazione());
	    $prestito->setNote($data->getNote());

	    // campi impostati automaticamente
	    $prestito->setDataPrestito(new \DateTime());
	    $prestito->setBibliotecarioPrestito($this->get('security.context')->getToken()->getUser()->getUsername());
	    $prestito->setUtente($username);
	    
	    // campi impostati a null di default
	    $prestito->setDataRestituzione(null);
	    $prestito->setRichiestaProroga("");
	    $prestito->setBibliotecarioRestituzione(null);

	    $em = $this->getDoctrine()->getManager();
	    $em->persist($prestito);
	    $em->flush();
	    return $this->redirectToRoute('mostraprestito');
	}

        return $this->render('default/nuovoprestito.html.twig', array(
            'form' => $form->createView(),
	    'utente' => $utente,
	    'prestito' => $prestito,
	    ));
    }




   /**
     * @Route("/logout", name="logout")
     */
    public function logoutAction(Request $request) {
        return $this->redirect($this->generateUrl('homepage'));
    }


}
