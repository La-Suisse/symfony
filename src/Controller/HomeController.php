<?php

namespace App\Controller;

use App\Entity\FicheFrais;
use App\Entity\Forfait;
use App\Entity\HorsForfait;
use App\Entity\Utilisateur;
use App\Form\UtilisateurType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Twig_Extensions_Extension_Intl;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    /**
     * @Route("/connexion", name="login")
     */
    public function login(Request $request) //UC : gestion de la connexion
    {
        $log = $this->getDoctrine()->getRepository(Utilisateur::class);
        $user = new Utilisateur();
        $form = $this->createForm(UtilisateurType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $userBDD = $log->findBy(
                ['identifiant' => $user->getIdentifiant(), 'mdp' => $this->cryptageVigenere($user->getMdp()), 'monType' => 1]
            );
            if ($userBDD) {

                // debut : assigne les information en session
                $session = $request->getSession();
                $session->set('PrenomSession', $userBDD[0]->getIdentifiant());
                $session->set('idSession', $userBDD[0]->getId());
                // fin

                $this->addFlash('home', 'Vous êtes connecté.'); //message flash a la connexion
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
    public function deconnexion(Request $request) // UC : deconnexion de l'utilisateur
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
    public function apiFiches() // api de toutes les fiche de l'application console
    {
        header('Access-Control-Allow-Origin: *');
        $repoUser = $this->getDoctrine()->getRepository(Utilisateur::class);
        $repoFiche = $this->getDoctrine()->getRepository(FicheFrais::class);
        $repoForfait = $this->getDoctrine()->getRepository(Forfait::class);
        $repoHorsForfait = $this->getDoctrine()->getRepository(HorsForfait::class);
        $utilisateurs = $repoUser->findAll();
        $formatted = [];
        foreach ($utilisateurs as $utilisateur) {
            $fiches = $repoFiche->findBy(
                ['monUtilisateur' => $utilisateur->getId()],
                ['date' => 'DESC']
            );
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
                    'montant' => $fiche->getMontant(),
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
        return new JsonResponse($formatted); //retourne un fichier json
    }




    /**
     * @Route("/apiusers", name="apiusers")
     */
    public function apiUsers() // api de tous les utilisateurs
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
        return new JsonResponse($formatted); //retourne un fichier json
    }

    #/**
    # * @Route("/cryptage/{mot}", name="cryptage")
    # */
    function cryptageVigenere($mot)
    {
        $cle = "41lac27ga35";
        $motcode = "";
        $longCle = strlen($cle);
        $longMot = strlen($mot);
        $cle = str_split($cle);
        $mot = str_split($mot);
        $tmp = 0;
        $i = 0;
        while ($i < $longMot) {
            $motcode = $motcode . $this->cryptagelettre($mot[$i], $cle[$tmp]);
            $tmp = $tmp + 1;
            if ($tmp == $longCle) {
                $tmp = 0;
            }
            $i++;
        }
        return $motcode;
    }

    function rangdanslalphabet($lettre)
    {
        $alphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
        $N = strlen($alphabet);
        $alphabet = str_split($alphabet);
        $j = 0;
        $rang = 0;
        while ($j < $N) {
            if ($lettre == $alphabet[$j]) {
                $rang = $j;
            }
            $j++;
        }
        return $rang;
    }

    function lettredelalphabet($rang)
    {
        $alphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
        $N = strlen($alphabet);
        $alphabet = str_split($alphabet);
        if ($rang >= $N) {
            $rang = $rang - $N;
        }
        if ($rang < 0) {
            $rang = $rang + $N;
        }
        $lettre = $alphabet[$rang];
        return $lettre;
    }

    function cryptagelettre($lettre, $cle)
    {
        $code = $this->rangdanslalphabet($lettre) + $this->rangdanslalphabet($cle);
        return $this->lettredelalphabet($code);
    }
}
