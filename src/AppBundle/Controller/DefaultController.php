<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\ExecutionContextInterface;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

use AppBundle\Entity\Utente;
use AppBundle\Entity\Prestito;

class DefaultController extends Controller
{

    /*
     * utility
     */
    public function createUsername($nome, $cognome) {
        $repoUtente = $this->getDoctrine()->getRepository('AppBundle:Utente');
	$count = 0;
	do {
	    $username = strtolower($nome.".".$cognome.($count>0?$count:"")); 
	    $count++;
	    $utente = $repoUtente->findOneByUsername($username);
	} while (!is_null($utente));
	return $username;
    }


    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        return $this->render('default/index.html.twig', array(
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
        ));
    }


    /**
     * @Route("/utility", name="utilityhomepage")
     */
    public function utilityindexAction(Request $request)
    {
        return $this->render('default/indexutility.html.twig', array(
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
        ));
    }


    /**
     * @Route("/mostra/utenti/{option}", name="mostrautenti", defaults={"option"=""})
     */
    public function mostrautentiAction(Request $request, $option = "")
    {
        $repo = $this->getDoctrine()->getrepository('AppBundle:Utente');
        $utenti = $repo->findAll();

        return $this->render('default/mostrautenti.html.twig', array(
            'utenti' => $utenti,
	    'isPerPrestito' => $option==="perprestito"?true:false,
	    'isPerUtility' => $option==="CONEXPORT"?true:false,
	    ));
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
	return $this->redirectToRoute('homepage');
    }


    /**
     * @Route("/edit/utente/{username}", name="editutente", defaults={"username"="N.U.O.V.O"})
     */
    public function editutenteAction(Request $request, $username = "N.U.O.V.O")
    {
        $repoUtente = $this->getDoctrine()->getRepository('AppBundle:Utente');
	$utente = $repoUtente->findOneByUsername($username);
	if (is_null($utente)) {
            $utente = new Utente();
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

	    return $this->redirectToRoute('mostrautenti',
					  array('option' => 'perprestito'));
	}

        return $this->render('default/editutente.html.twig', array(
            'form' => $form->createView(),
	    'utente' => $utente,
	    ));
    }


    /**
     * @Route("/mostra/prestito/{option}", name="mostraprestito", defaults={"option"=""})
     */
    public function mostraprestitoAction(Request $request, $option = "")
    {
        $repo = $this->getDoctrine()->getrepository('AppBundle:Prestito');
        $prestiti = $repo->findAll();

	if (strpos($option, 'CONEXPORT') !== false) {
	    $isUtility = true;
        } else {
	    $isUtility = false;
	}

	if (strpos($option, 'INCORSO') !== false) {
	    $prestiti = array_filter($prestiti, function($p){ return is_null($p->getDataRestituzione());});
	}

	if (strpos($option, 'RESTITUITO') !== false) {
	    $prestiti = array_filter($prestiti, function($p){ return (!is_null($p->getDataRestituzione()));});
	}

        return $this->render('default/mostraprestito.html.twig', array(
            'prestiti' => $prestiti,
	    'isUtility' => $isUtility,
        ));
    }


    /**
     * @Route("/action/prestito/prolungato/{id}", name="prestitoprolungato")
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
	return $this->redirectToRoute('mostraprestito',
					  array('option' => 'INCORSO'));
    }

    /**
     * @Route("/action/prestito/restituito/{id}", name="prestitorestituito")
     */
    public function prestitorestituitoAction(Request $request, $id)
    {
        $repo = $this->getDoctrine()->getrepository('AppBundle:Prestito');
        $prestito = $repo->findOneById($id);
	$prestito->setDataRestituzione(new \DateTime(date('Y-m-d H:i:s')));
	$prestito->setBibliotecarioRestituzione($this->get('security.context')->getToken()->getUser()->getUsername());
	$em = $this->getDoctrine()->getManager();
	$em->persist($prestito);
	$em->flush();

        // vai alla pagina "mostra prestiti in corso"
	return $this->redirectToRoute('mostraprestito',
					  array('option' => 'INCORSO'));
    }


    /**
     * @Route("/edit/nuovoprestito/", name="nuovoprestito")
     */
    public function nuovoprestitoAction(Request $request)
    {
        // nuovo prestito: mostra la pagina utenti,
	// da cui verra' scelto l'utente a cui fare il prestito
	return $this->redirectToRoute('mostrautenti',
				      array('option' => 'perprestito'));
    }


    /**
     * @Route("/edit/prestito/{id}/{username}", name="editprestito")
     */
    public function editprestitoAction(Request $request, $id, $username)
    {
        // nuovo prestito all'utente selezionato

        $repoUtente = $this->getDoctrine()->getRepository('AppBundle:Utente');
	$utente = $repoUtente->findOneByUsername($username);

        $repo = $this->getDoctrine()->getrepository('AppBundle:Prestito');
        $prestito = $repo->findOneById($id);

	if (is_null($prestito)) {
	    $isNuovoPrestito = true;
	    $prestito = new Prestito();
	    // campi impostati automaticamente
	    $prestito->setDataPrestito(new \DateTime());
	    $prestito->setBibliotecarioPrestito($this->get('security.context')->getToken()->getUser()->getUsername());
	    $prestito->setUtente($username);
	    // campi impostati a null di default
	    $prestito->setDataRestituzione(null);
	    $prestito->setRichiestaProroga("");
	    $prestito->setBibliotecarioRestituzione(null);
	} else {
	    $isNuovoPrestito = false;
	}
	$form = $this->createFormBuilder(
	      null, array(
              	    'data_class' => 'AppBundle\Entity\Prestito',
        	    'constraints' => array(new Assert\Callback('validateCollocazione1'),
		    		           new Assert\Callback('validateCollocazione2'))
              )
        );
	$form = $form->add('protocollo', TextType::class
	      	    //, array('data'  => "pippo",)
		    );
	$form = $form->add('titolo1', TextType::class);
	$form = $form->add('collocazione1', TextType::class);
	$form = $form->add('titolo2', TextType::class, array(
    	      	    'required'    => false,
		    // 'placeholder' => '',
    		    'empty_data'  => null
		    ));
	$form = $form->add('collocazione2', TextType::class, array(
    	      	    'required'    => false,
		    // 'placeholder' => '',
    		    'empty_data'  => null
		    ));
	$form = $form->add('note', TextType::class, array(
    	      	    'required'    => false,
		    // 'placeholder' => '',
    		    'empty_data'  => null
		    ));
	$form = $form->add('richiestaProroga', TextType::class, array(
    	      	    'required'    => false,
		    // 'placeholder' => '',
    		    'empty_data'  => null
		    ));
        $form = $form->getForm();
	$form->handleRequest($request);

	if ($form->isSubmitted() && $form->isValid()) {
	    $data = $form->getData();
	    $prestito->setProtocollo($data->getProtocollo());
	    $prestito->setTitolo1($data->getTitolo1());
	    $prestito->setCollocazione1($data->getCollocazione1());
	    $prestito->setTitolo2($data->getTitolo2());
	    $prestito->setCollocazione2($data->getCollocazione2());
	    $prestito->setNote($data->getNote());
	    $prestito->setRichiestaProroga($data->getRichiestaProroga());

	    $em = $this->getDoctrine()->getManager();
	    $em->persist($prestito);
	    $em->flush();
	    return $this->redirectToRoute('mostraprestito',
					  array('option' => 'INCORSO'));
	}

        return $this->render('default/editprestito.html.twig', array(
            'form' => $form->createView(),
	    'utente' => $utente,
	    'prestito' => $prestito,
	    'isNuovoPrestito' => $isNuovoPrestito,
	    ));
    }


   /**
     * @Route("/view/esportalistautenti", name="esportalistautenti")
     */
    public function esportalistautentiAction(Request $request) {
        $repo = $this->getDoctrine()->getrepository('AppBundle:Utente');
        $utenti = $repo->findAll();

        $content = "username,ruolo,nome,cognome,residenza,cellulare,email,consenso,dataIscrizione,bibliotecario,tipoDocumento,emessoDa,numeroDocumento\n";
	foreach($utenti as $u) {	
	    $content = $content . $u->getUsername() . ","; 
	    $content = $content . $u->getRuolo() . ","; 
	    $content = $content . $u->getNome() . ","; 
	    $content = $content . $u->getCognome() . ","; 
	    $content = $content . $u->getResidenza() . ","; 
	    $content = $content . $u->getCellulare() . ","; 
	    $content = $content . $u->getEmail() . ","; 
	    $content = $content . ($u->getConsenso()?"1":"0") . ","; 
	    $content = $content . $u->getDataIscrizione()->format('Y-m-dTH:i:s') . ","; 
	    $content = $content . $u->getBibliotecario() . ","; 
	    $content = $content . $u->getTipoDocumento() . ","; 
	    $content = $content . $u->getEmessoDa() . ","; 
	    $content = $content . $u->getNumeroDocumento() . "\n"; 
	}
        $response = new Response();
        $response->headers->set('Content-Type', 'text/csv');
        $response->setContent($content);
        return $response;
    }


   /**
     * @Route("/view/esportalistaprestiti", name="esportalistaprestiti")
     */
    public function esportalistaprestitiAction(Request $request) {
        $repo = $this->getDoctrine()->getrepository('AppBundle:Prestito');
        $prestiti = $repo->findAll();

        $content = "protocollo,titolo1,collocazione1,titolo2,collocazione2,dataPrestito,dataRestituzione,richiestaProroga,bibliotecarioPresito,bibliotecarioRestituzione,note,utente\n";
	foreach($prestiti as $u) {	
	    $dataRestituzione = is_null($u->getDataRestituzione())?"":$u->getDataRestituzione()->format('Y-m-dTH:i:s');
	    $content = $content . $u->getProtocollo() . ","; 
	    $content = $content . $u->getTitolo1() . ","; 
	    $content = $content . $u->getCollocazione1() . ","; 
	    $content = $content . $u->getTitolo2() . ","; 
	    $content = $content . $u->getCollocazione2() . ","; 
	    $content = $content . $u->getDataPrestito()->format('Y-m-dTH:i:s') . ","; 
	    $content = $content . $dataRestituzione . ","; 
	    $content = $content . $u->getRichiestaProroga() . ","; 
	    $content = $content . $u->getbibliotecarioPrestito() . ","; 
	    $content = $content . $u->getBibliotecarioRestituzione() . ","; 
	    $content = $content . $u->getNote() . ","; 
	    $content = $content . $u->getUtente() . "\n"; 
	}
        $response = new Response();
        $response->headers->set('Content-Type', 'text/csv');
        $response->setContent($content);
        return $response;
    }



   /**
     * @Route("/logout", name="logout")
     */
    public function logoutAction(Request $request) {
        return $this->redirect($this->generateUrl('homepage'));
    }


}
