<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\UtilisateurType;
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
            $prenom = "";
            dump($user);
            dump($listeuser);
            foreach ($listeuser as $unUser) {
                if ($unUser->getIdentifiant() == $user->getIdentifiant()) {
                    $indexID = $unUser->getIdentifiant();
                    $prenom = $unUser->getPrenom();
                    $id = $unUser->getId();
                    if ($unUser->getMdp() == $user->getMdp()) {
                        $indexPW = $unUser->getIdentifiant();
                        break;
                    }
                }
            }
            if ($indexPW == $indexID) {
                $session = $request->getSession();
                $session->set('PrenomSession', $prenom);
                $session->set('idSession', $id);
                $this->addFlash('home', 'Vous êtes connecté.');
                return $this->redirectToRoute('fiche');
            } else {
                $this->addFlash('home', 'Identifiant ou mot de passe incorrect');
            }
        }
        return $this->render('home/log.html.twig', [
            'controller_name' => 'HomeController',
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/deconnexion", name="deconnexion")
     */
    public function deconnexion(Request $request)
    {
        $session = $request->getSession();
        $session->clear();
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
}
