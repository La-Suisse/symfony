<?php

namespace App\Controller;

use App\Entity\FicheFrais;
use App\Entity\Utilisateur;
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
        $idSession=$session->get('idSession');
        $repo = $this->getDoctrine()->getRepository(Utilisateur::class);
        $UserBdd=$repo->find($idSession);
        dump($UserBdd);
        $listefiches=$UserBdd->getMaFicheFrais();
        
        dump($listefiches);
        dump($idSession);
        return $this->render('fiche/index.html.twig', [
            'controller_name' => 'FicheController',
            'listeFrais'=>$listefiches, 
        ]);
    }
}
