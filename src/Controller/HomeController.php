<?php

namespace App\Controller;

use App\Entity\FicheFrais;
use App\Entity\Forfait;
use App\Entity\HorsForfait;
use App\Entity\TypeUtilisateur;
use App\Entity\Utilisateur;
use App\Form\ForfaitType;
use App\Form\UtilisateurType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
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
                        $type = $unUser->getMonType()->getLibelle();
                        break;
                    }
                }
            }
            if ($indexPW == $indexID && $type == 'Visiteur') {
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

    /**
     * @Route("/apifiches", name="apifiches")
     */
    public function apiFiches()
    {
        header('Access-Control-Allow-Origin: *');
        $repoUser = $this->getDoctrine()->getRepository(Utilisateur::class);
        $repoFiche = $this->getDoctrine()->getRepository(FicheFrais::class);
        $repoForfait = $this->getDoctrine()->getRepository(Forfait::class);
        $repoHorsForfait = $this->getDoctrine()->getRepository(HorsForfait::class);
        $utilisateurs = $repoUser->findAll();
        $formatted = [];
        foreach ($utilisateurs as $utilisateur) {
            $fiches = $repoFiche->findBy(['monUtilisateur' => $utilisateur->getId()]);
            foreach ($fiches as $fiche) {
                $forfaits = $repoForfait->findBy(['maFiche' => $fiche->getId()]);
                foreach ($forfaits as $forfait) {
                    $type = $forfait->getMonType();
                    $valeurs[] = [
                        'libelle' => $type->getLibelle(),
                        'quantite' => $forfait->getQuantite(),
                        'prix' => $type->getPrix(),
                    ];
                    $forfaitTab[] = [
                        'id' => $forfait->getId(),
                        'type' => 'forfait',
                        'valeurs' => $valeurs
                    ];
                    $valeurs = null;
                }
                $horsForfaits = $repoHorsForfait->findBy(['maFiche' => $fiche->getId()]);
                foreach ($horsForfaits as $horsForfait) {
                    $valeursHF[] = [
                        'date' => $horsForfait->getDate()->format('d/m/Y'),
                        'libelle' => $horsForfait->getLibelle(),
                        'prix' => $horsForfait->getPrix(),

                    ];
                    $horsforfaitTab[] = [
                        'id' => $horsForfait->getId(),
                        'type' => 'horsforfait',
                        'valeurs' => $valeursHF,
                    ];
                    $valeursHF = null;
                }
                $renvoi[] = [
                    'fiche' => $fiche->getId(),
                    'date' => $fiche->getDate()->format('M Y'),
                    'etat' => $fiche->getMonEtat()->getLibelle(),
                    'collapse' => 'collapse' . strval($fiche->getId()),
                    'collapseT' => '#collapse' . strval($fiche->getId()),
                    'forfait' => $forfaitTab,
                    'horsforfait' => $horsforfaitTab,
                ];
                $forfaitTab = null;
                $horsforfaitTab = null;
            }
            $formatted[] = [
                'id'                => $utilisateur->getId(),
                'fiches' => $renvoi,
            ];
            $renvoi = null;
        }
        return new JsonResponse($formatted);
    }




    /**
     * @Route("/apiusers", name="apiusers")
     */
    public function apiUsers()
    {
        header('Access-Control-Allow-Origin: *');
        $repoUser = $this->getDoctrine()->getRepository(Utilisateur::class);
        $utilisateurs = $repoUser->findAll();
        $formatted = [];
        foreach ($utilisateurs as $utilisateur) {
            $formatted[] = [
                "id" => $utilisateur->getId(),
                "identifiant" => $utilisateur->getIdentifiant(),
                "motDePasse" => $utilisateur->getMdp(),
                "type" => $utilisateur->getMonType()->getLibelle(),
            ];
        }


        return new JsonResponse($formatted);
    }
}
