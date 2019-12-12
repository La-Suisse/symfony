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
    public function index(Request $request) //UC : affichage de toutes les fiches en fonction de l'utilisateur
    {
        //recuperation de l'id de l'utilisateur en session et test si il existe  salut
        $session = $request->getSession();
        $idSession = $session->get('idSession');
        if (!isset($idSession)) {
            return $this->redirectToRoute('login');
        }

        //recuperation de l'utilisateur en fonction de son id aissi que toutes les fiches
        $repo = $this->getDoctrine()->getRepository(Utilisateur::class);
        $repoFiches = $this->getDoctrine()->getRepository(FicheFrais::class);
        $UserBdd = $repo->find($idSession);
        dump($UserBdd);
        $listefiches = $UserBdd->getMaFicheFrais();
        dump($listefiches);

        //sert a griser le bouton si une fiche a ete cree par l'utilisateur ce mois ci
        $etatBouton = "";
        foreach ($listefiches as $liste) {
            $month = $liste->getDate()->format('m');
            if ($month == date('m')) {
                $etatBouton = "disabled";
            }
        }

        return $this->render('fiche/index.html.twig', [
            'controller_name' => 'FicheController',
            'listeFrais' => $listefiches,
            'etatBouton' => $etatBouton
        ]);
    }
    /**
     * @Route("/modifier/{id}", name="modifier")
     */
    public function modifer($id, Request $request) //UC : Modification & ajout d'ellements d'une fiche
    {
        //recuperation de l'id de l'utilisateur en session et test si il existe
        $session = $request->getSession();
        $idSession = $session->get('idSession');
        if (!isset($idSession)) {
            return $this->redirectToRoute('login');
        }
        //recuperation des forfait et hors forfait existant dans une fiche a partir de son id passer dans la route
        $repoForfait = $this->getDoctrine()->getRepository(Forfait::class);
        $repoHors = $this->getDoctrine()->getRepository(HorsForfait::class);
        $forfait = $repoForfait->findBy(['maFiche' => $id]);
        $horsforfait = $repoHors->findBy(['maFiche' => $id]);

        //recuperation de la fiche en elle meme pour pouvoir creer les nouveaux hors forfaits
        $repoFiche = $this->getDoctrine()->getRepository(FicheFrais::class);
        $maFiche = $repoFiche->find($id);

        //creation des 4 formulaire des 4 type de forfait
        $form1 = $this->createForm(ForfaitType::class, $forfait[0]);
        $form2 = $this->createForm(ForfaitType::class, $forfait[1]);
        $form3 = $this->createForm(ForfaitType::class, $forfait[2]);
        $form4 = $this->createForm(ForfaitType::class, $forfait[3]);

        //creation d'un nouveau hors forfait et envoi dans la base
        $UnHorsForfait = new HorsForfait;
        $UnHorsForfait->setMaFiche($maFiche);
        $UnHorsForfait->setDate(new DateTime("now"));
        $formCreationHors = $this->createForm(HorsForfaitType::class, $UnHorsForfait);
        $formCreationHors->handleRequest($request);
        if ($formCreationHors->isSubmitted() && $formCreationHors->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($UnHorsForfait);
            $entityManager->flush();
            return $this->redirectToRoute('modifier', array("id" => $id));
        }

        return $this->render('fiche/modification.html.twig', [
            'forfaits' => $forfait,
            'horsForfaits' => $horsforfait,
            'form' => $formCreationHors->createView(), //form nouveau hors forfait
            'form1' => $form1->createView(),
            'form2' => $form2->createView(),
            'form3' => $form3->createView(),
            'form4' => $form4->createView(),
            'idFiche' => $id,
            'maFiche' => $maFiche
        ]);
    }
    /**
     * @Route("/form1route/{id}", name="form1Route")
     */
    public function form1Route($id, Request $request) //Formulaire concernant les forfait repas
    {
        //recuperation de l'id de l'utilisateur en session et test si il existe
        $session = $request->getSession();
        $idSession = $session->get('idSession');
        if (!isset($idSession)) {
            return $this->redirectToRoute('login');
        }
        //recuperation du bon forfait
        $repoForfait = $this->getDoctrine()->getRepository(Forfait::class);
        $forfaits = $repoForfait->findBy(['maFiche' => $id]);
        $forfait = $forfaits[0];

        //modification dans la base
        $forfait->setQuantite($_GET['forfait']['quantite']);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($forfait);
        $entityManager->flush();
        return $this->redirectToRoute('modifier', array("id" => $id));
    }

    /**
     * @Route("/form2route/{id}", name="form2Route")
     */
    public function form2Route($id, Request $request) //Formulaire concernant les forfait étape
    {
        //recuperation de l'id de l'utilisateur en session et test si il existe
        $session = $request->getSession();
        $idSession = $session->get('idSession');
        if (!isset($idSession)) {
            return $this->redirectToRoute('login');
        }

        //recuperation du bon forfait
        $repoForfait = $this->getDoctrine()->getRepository(Forfait::class);
        $forfaits = $repoForfait->findBy(['maFiche' => $id]);
        $forfait = $forfaits[1];

        //modification dans la base
        $forfait->setQuantite($_GET['forfait']['quantite']);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($forfait);
        $entityManager->flush();
        return $this->redirectToRoute('modifier', array("id" => $id));
    }

    /**
     * @Route("/form3route/{id}", name="form3Route")
     */
    public function form3Route($id, Request $request) //Formulaire concernant les forfait nuitee
    {
        //recuperation de l'id de l'utilisateur en session et test si il existe
        $session = $request->getSession();
        $idSession = $session->get('idSession');
        if (!isset($idSession)) {
            return $this->redirectToRoute('login');
        }

        //recuperation du bon forfait
        $repoForfait = $this->getDoctrine()->getRepository(Forfait::class);
        $forfaits = $repoForfait->findBy(['maFiche' => $id]);
        $forfait = $forfaits[2];

        //modification dans la base
        $forfait->setQuantite($_GET['forfait']['quantite']);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($forfait);
        $entityManager->flush();
        return $this->redirectToRoute('modifier', array("id" => $id));
    }

    /**
     * @Route("/form4route/{id}", name="form4Route")
     */
    public function form4Route($id, Request $request) //Formulaire concernant les forfait Kilometrage
    {
        //recuperation de l'id de l'utilisateur en session et test si il existe
        $session = $request->getSession();
        $idSession = $session->get('idSession');
        if (!isset($idSession)) {
            return $this->redirectToRoute('login');
        }

        //recuperation du bon forfait
        $repoForfait = $this->getDoctrine()->getRepository(Forfait::class);
        $forfaits = $repoForfait->findBy(['maFiche' => $id]);
        $forfait = $forfaits[3];

        //modification dans la base
        $forfait->setQuantite($_GET['forfait']['quantite']);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($forfait);
        $entityManager->flush();
        return $this->redirectToRoute('modifier', array("id" => $id));
    }



    /**
     * @Route("/creation", name="creation")
     */
    public function creation(Request $request) //UC : Creation d'une nouvelle fiche
    {
        //recuperation de l'id de l'utilisateur en session et test si il existe
        $session = $request->getSession();
        $idSession = $session->get('idSession');
        if (!isset($idSession)) {
            return $this->redirectToRoute('login');
        }

        //creation d'une fiche vierge et recuperation des information pour la fiche
        $date = new DateTime("now");
        $newFiche = new FicheFrais();
        $repoUser = $this->getDoctrine()->getRepository(Utilisateur::class);
        $utilistateur = $repoUser->findOneBy(['id' => $idSession]);
        $repo = $this->getDoctrine()->getRepository(Etat::class);
        $etat = $repo->findOneBy(['id' => 1]);

        //assignation des element a la fiche
        $newFiche->setMonEtat($etat);
        $newFiche->setMonUtilisateur($utilistateur);
        $newFiche->setDate($date);

        //envoi de la fiche dans la base
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($newFiche);
        $entityManager->flush();

        //redirection vers la modification de cette fiche
        return $this->redirectToRoute('modifier', array('id' => $newFiche->getId()));
    }


    /**
     * @Route("/information/{id}", name="information")
     */
    public function information($id, Request $request) //UC : Affichage d'une fiche cloturé
    {
        //recuperation de l'id de l'utilisateur en session et test si il existe
        $session = $request->getSession();
        $idSession = $session->get('idSession');
        if (!isset($idSession)) {
            return $this->redirectToRoute('login');
        }

        //recuperation des informations de la fiche
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
    public function supprimer($id, Request $request) //UC : Suppression d'un hors forfait
    {
        //recuperation de l'id de l'utilisateur en session et test si il existe
        $session = $request->getSession();
        $idSession = $session->get('idSession');
        if (!isset($idSession)) {
            return $this->redirectToRoute('login');
        }

        //recuperation du hors forfait en fonction de son id
        $repoHors = $this->getDoctrine()->getRepository(HorsForfait::class);
        $horsforfait = $repoHors->find($id);

        //suppresion dans la base
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($horsforfait);
        $entityManager->flush();

        //recuperation de la fiche pour la redirection
        $Fiche = $horsforfait->getMaFiche();
        $idFiche = $Fiche->getId();
        return $this->redirectToRoute('modifier', array("id" => $idFiche));
    }
}
