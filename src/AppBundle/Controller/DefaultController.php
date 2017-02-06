<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

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
     * @Route("/logout", name="logout")
     */
    public function logoutAction(Request $request) {
        return $this->redirect($this->generateUrl('homepage'));
    }


}
