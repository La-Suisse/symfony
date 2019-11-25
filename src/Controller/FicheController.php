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
use Symfony\Component\Validator\Constraints\Date;

class FicheController extends AbstractController
{
    /**
     * @Route("/fiche", name="fiche")
     */
    public function index(Request $request)
    {
        $session = $request->getSession();
        $idSession = $session->get('idSession');
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
        $repoForfait = $this->getDoctrine()->getRepository(Forfait::class);
        $repoHors = $this->getDoctrine()->getRepository(HorsForfait::class);
        $forfait = $repoForfait->findBy(['maFiche' => $id]);
        $horsforfait = $repoHors->findBy(['maFiche' => $id]);

        $repoFiche = $this->getDoctrine()->getRepository(FicheFrais::class);
        $maFiche = $repoFiche->find($id);


        $forfait1 = $forfait[0];
        $forfait2 = $forfait[1];
        $forfait3 = $forfait[2];
        $forfait4 = $forfait[3];

        $form1 = $this->createForm(ForfaitType::class, $forfait[0]);
        $form1->handleRequest($request);
        if ($form1->isSubmitted() && $form1->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($forfait1);
            $entityManager->flush();
            return $this->redirectToRoute('modifier', array("id" => $id));
        }

        $form2 = $this->createForm(ForfaitType::class, $forfait2);
        $form2->handleRequest($request);
        if ($form2->isSubmitted() && $form2->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($forfait2);
            $entityManager->flush();
            return $this->redirectToRoute('modifier', array("id" => $id));
        }

        $form3 = $this->createForm(ForfaitType::class, $forfait3);
        $form3->handleRequest($request);
        if ($form3->isSubmitted() && $form3->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($forfait3);
            $entityManager->flush();
            return $this->redirectToRoute('modifier', array("id" => $id));
        }


        $form4 = $this->createForm(ForfaitType::class, $forfait4);
        $form4->handleRequest($request);
        if ($form4->isSubmitted() && $form4->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($forfait4);
            $entityManager->flush();
            return $this->redirectToRoute('modifier', array("id" => $id));
        }


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
        ]);
    }

    /**
     * @Route("/creation", name="creation")
     */
    public function creation(Request $request)
    {
        $session = $request->getSession();
        $idSession = $session->get('idSession');
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


        return $this->redirectToRoute('fiche');
    }
    /**
     * @Route("/information/{id}", name="information")
     */
    public function information($id)
    {
        $repoForfait = $this->getDoctrine()->getRepository(Forfait::class);
        $repoHors = $this->getDoctrine()->getRepository(HorsForfait::class);
        $forfait = $repoForfait->findBy(['maFiche' => $id]);
        dump($forfait);
        $horsforfait = $repoHors->findBy(['maFiche' => $id]);
        dump($horsforfait);

        return $this->render('fiche/infoFiche.html.twig', [
            'forfaits' => $forfait,
            'horsForfaits' => $horsforfait
        ]);
    }
    /**
     * @Route("/supprimer/{id}", name="supprimer")
     */
    public function supprimer($id)
    {
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
