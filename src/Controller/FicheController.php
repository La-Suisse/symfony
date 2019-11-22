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
        foreach($listefiches as $liste){
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
    public function modifer($id,Request $request)
    {
        $repoForfait = $this->getDoctrine()->getRepository(Forfait::class);
        $repoHors = $this->getDoctrine()->getRepository(HorsForfait::class);
        $forfait = $repoForfait->findOneBy(['maFiche' => $id]);
        $horsforfait = $repoHors->findOneBy(['maFiche' => $id]);
        $formForfait = $this->createForm(ForfaitType::class, $forfait);
        $formHors = $this->createForm(HorsForfaitType::class, $horsforfait);
        $formForfait->handleRequest($request);
        $formHors->handleRequest($request);
        if ($formForfait->isSubmitted() && $formForfait->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($forfait);
            $entityManager->flush();
            //$this->addFlash('admin', 'Genre ajouté avec succès.');
            return $this->redirectToRoute('modifier');
        }
        if ($formHors->isSubmitted() && $formHors->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($horsforfait);
            $entityManager->flush();
            //$this->addFlash('admin', 'Genre ajouté avec succès.');
            return $this->redirectToRoute('modifier');
        }
        
        return $this->render('fiche/fiche.html.twig', [
            'formForfait' => $formForfait->createView(),
            'formHorsForfait' => $formHors->createView()
        ]);
    }

        /**
     * @Route("/creation", name="creation")
     */
    public function creation(Request $request)
    {
        $session = $request->getSession();
        $idSession = $session->get('idSession');
        $date=new DateTime("now");
        $newFiche= new FicheFrais();
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
        $forfait = $repoForfait->findOneBy(['maFiche' => $id]);
        $horsforfait = $repoHors->findOneBy(['maFiche' => $id]);
        
        return $this->render('fiche/infoFiche/html.twig', [
            'forfaits'=>$forfait,
            'horsForfaits'=>$horsforfait
        ]);
    }
}
