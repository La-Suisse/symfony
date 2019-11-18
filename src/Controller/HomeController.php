<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\UtilisateurType;
use Doctrine\DBAL\Schema\Index;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/home", name="home")
     */
    public function index()
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    /**
     * @Route("/log", name="login")
     */
    public function login(Request $request)
    {
        $log = $this->getDoctrine()->getRepository(Utilisateur::class);
        $user = new Utilisateur();
        $form = $this->createForm(UtilisateurType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $listeuser = $log->findAll();
            $indexID = '';
            $indexPW = 't';
            dump($user->getIdentifiant());
            foreach ($listeuser as $unUser) {
                if ($unUser->getIdentifiant() == $user->getIdentifiant()) {
                    $indexID = $unUser->getIdentifiant();
                }
                if ($unUser->getMdp() == $user->getMdp()) {
                    $indexPW = $unUser->getIdentifiant();
                }
            }
            if ($indexPW == $indexID) {
                return $this->redirectToRoute('home');
            }
            $this->addFlash('admin', 'Ajout réalisé avec succès.');
        }

        return $this->render('home/log.html.twig', [
            'controller_name' => 'HomeController',
            'form' => $form->createView(),
        ]);
    }
}
