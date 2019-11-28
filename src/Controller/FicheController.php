<?php

namespace App\Controller;

use App\Entity\Etat;
use App\Entity\FicheFrais;
use App\Entity\Forfait;
use App\Entity\HorsForfait;
use App\Entity\Utilisateur;
use App\Form\ForfaitType;
use App\Form\HorsForfaitType;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class FicheController extends AbstractController
{
    /**
     * @Route("/fiche", name="fiche")
     */
    public function index(Request $request)
    {
        $session = $request->getSession();
        $idSession = $session->get('idSession');
        if (!isset($idSession)) {
            return $this->redirectToRoute('login');
        }
        $repo = $this->getDoctrine()->getRepository(Utilisateur::class);
        $UserBdd = $repo->find($idSession);
        $listefiches = $UserBdd->getMaFicheFrais();
        $test = "";
        foreach ($listefiches as $liste) {
            $month = $liste->getDate()->format('m');
            if ($month == date('m')) {
                $test = "disabled";
            }
        }
        return $this->render('fiche/index.html.twig', [
            'controller_name' => 'FicheController',
            'listeFrais' => $listefiches,
            'test' => $test
        ]);
    }
    /**
     * @Route("/modifier/{id}", name="modifier")
     */
    public function modifer($id, Request $request)
    {
        $button = null;
        $session = $request->getSession();
        $idSession = $session->get('idSession');
        if (!isset($idSession)) {
            return $this->redirectToRoute('login');
        }
        $repoForfait = $this->getDoctrine()->getRepository(Forfait::class);
        $repoHors = $this->getDoctrine()->getRepository(HorsForfait::class);
        $forfait = $repoForfait->findBy(['maFiche' => $id]);
        $horsforfait = $repoHors->findBy(['maFiche' => $id]);

        $repoFiche = $this->getDoctrine()->getRepository(FicheFrais::class);
        $maFiche = $repoFiche->find($id);

        $form1 = $this->createForm(ForfaitType::class, $forfait[0]);
        $form2 = $this->createForm(ForfaitType::class, $forfait[1]);
        $form3 = $this->createForm(ForfaitType::class, $forfait[2]);
        $form4 = $this->createForm(ForfaitType::class, $forfait[3]);


        $UnHorsForfait = new HorsForfait;
        $UnHorsForfait->setMaFiche($maFiche);
        $UnHorsForfait->setDate(new DateTime("now"));
        $formCreationHors = $this->createForm(HorsForfaitType::class, $UnHorsForfait);
        $formCreationHors->handleRequest($request);
        if ($formCreationHors->isSubmitted() && $formCreationHors->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($UnHorsForfait);
            $entityManager->flush();
            //$this->addFlash('admin', 'Genre ajouté avec succès.');
            return $this->redirectToRoute('modifier', array("id" => $id));
        }

        return $this->render('fiche/modification.html.twig', [
            'forfaits' => $forfait,
            'horsForfaits' => $horsforfait,
            'form' => $formCreationHors->createView(),
            'form1' => $form1->createView(),
            'form2' => $form2->createView(),
            'form3' => $form3->createView(),
            'form4' => $form4->createView(),
            'idFiche' => $id,
            'button' => $button,
            'maFiche' => $maFiche
        ]);
    }
    /**
     * @Route("/form1route/{id}", name="form1Route")
     */
    public function form1Route($id, Request $request)
    {
        $session = $request->getSession();
        $idSession = $session->get('idSession');
        if (!isset($idSession)) {
            return $this->redirectToRoute('login');
        }
        $repoForfait = $this->getDoctrine()->getRepository(Forfait::class);
        $forfaits = $repoForfait->findBy(['maFiche' => $id]);
        $forfait = $forfaits[0];
        $forfait->setQuantite($_GET['forfait']['quantite']);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($forfait);
        $entityManager->flush();
        return $this->redirectToRoute('modifier', array("id" => $id));
    }

    /**
     * @Route("/form2route/{id}", name="form2Route")
     */
    public function form2Route($id, Request $request)
    {
        $session = $request->getSession();
        $idSession = $session->get('idSession');
        if (!isset($idSession)) {
            return $this->redirectToRoute('login');
        }
        $repoForfait = $this->getDoctrine()->getRepository(Forfait::class);
        $forfaits = $repoForfait->findBy(['maFiche' => $id]);
        $forfait = $forfaits[1];
        $forfait->setQuantite($_GET['forfait']['quantite']);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($forfait);
        $entityManager->flush();
        return $this->redirectToRoute('modifier', array("id" => $id));
    }

    /**
     * @Route("/form3route/{id}", name="form3Route")
     */
    public function form3Route($id, Request $request)
    {
        $session = $request->getSession();
        $idSession = $session->get('idSession');
        if (!isset($idSession)) {
            return $this->redirectToRoute('login');
        }
        $repoForfait = $this->getDoctrine()->getRepository(Forfait::class);
        $forfaits = $repoForfait->findBy(['maFiche' => $id]);
        $forfait = $forfaits[2];
        $forfait->setQuantite($_GET['forfait']['quantite']);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($forfait);
        $entityManager->flush();
        return $this->redirectToRoute('modifier', array("id" => $id));
    }

    /**
     * @Route("/form4route/{id}", name="form4Route")
     */
    public function form4Route($id, Request $request)
    {
        $session = $request->getSession();
        $idSession = $session->get('idSession');
        if (!isset($idSession)) {
            return $this->redirectToRoute('login');
        }
        $repoForfait = $this->getDoctrine()->getRepository(Forfait::class);
        $forfaits = $repoForfait->findBy(['maFiche' => $id]);
        $forfait = $forfaits[3];
        $forfait->setQuantite($_GET['forfait']['quantite']);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($forfait);
        $entityManager->flush();
        return $this->redirectToRoute('modifier', array("id" => $id));
    }



    /**
     * @Route("/creation", name="creation")
     */
    public function creation(Request $request)
    {
        $session = $request->getSession();
        $idSession = $session->get('idSession');
        if (!isset($idSession)) {
            return $this->redirectToRoute('login');
        }
        $date = new DateTime("now");
        $newFiche = new FicheFrais();
        $repoUser = $this->getDoctrine()->getRepository(Utilisateur::class);
        $utilistateur = $repoUser->findOneBy(['id' => $idSession]);
        dump($utilistateur);
        $repo = $this->getDoctrine()->getRepository(Etat::class);
        $etat = $repo->findOneBy(['id' => 1]);
        dump($etat);

        $newFiche->setMonEtat($etat);
        $newFiche->setMonUtilisateur($utilistateur);
        $newFiche->setDate($date);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($newFiche);
        $entityManager->flush();

        $idFiche = $newFiche->getId();

        return $this->redirectToRoute('modifier', array('id' => $idFiche));
    }
    /**
     * @Route("/information/{id}", name="information")
     */
    public function information($id, Request $request)
    {
        $session = $request->getSession();
        $idSession = $session->get('idSession');
        if (!isset($idSession)) {
            return $this->redirectToRoute('login');
        }
        $repoForfait = $this->getDoctrine()->getRepository(Forfait::class);
        $repoHors = $this->getDoctrine()->getRepository(HorsForfait::class);
        $forfait = $repoForfait->findBy(['maFiche' => $id]);
        $horsforfait = $repoHors->findBy(['maFiche' => $id]);
        $repoFiche = $this->getDoctrine()->getRepository(FicheFrais::class);
        $maFiche = $repoFiche->find($id);

        return $this->render('fiche/infoFiche.html.twig', [
            'forfaits' => $forfait,
            'horsForfaits' => $horsforfait,
            'maFiche' => $maFiche,
        ]);
    }
    /**
     * @Route("/supprimer/{id}", name="supprimer")
     */
    public function supprimer($id, Request $request)
    {
        $session = $request->getSession();
        $idSession = $session->get('idSession');
        if (!isset($idSession)) {
            return $this->redirectToRoute('login');
        }
        $repoHors = $this->getDoctrine()->getRepository(HorsForfait::class);
        $horsforfait = $repoHors->find($id);
        $Fiche = $horsforfait->getMaFiche();
        $idFiche = $Fiche->getId();
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($horsforfait);
        $entityManager->flush();
        return $this->redirectToRoute('modifier', array("id" => $idFiche));
    }
}
