<?php

namespace App\Controller;

use App\Entity\Trajet;
use App\Service\GetTrajet;
use App\Entity\Reservation;
use App\Service\BuildHashedCode;
use App\Form\TrajetType;
use App\Repository\TrajetRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/trajet')]
class TrajetController extends AbstractController
{
    public function dispatchEntity($trajets, $villeDept = null, $villeArrv = null, $paysDept = null, $paysArrv = null)
    {
        $trajets2 = [];
        $trajets3 = [];
        $trajets4 = [];
        $trajets5 = [];
        foreach ($trajets as $trajet) {
            if (($trajet->getVilleDept() == $villeDept or empty($villeDept)) && ($trajet->getVilleArrv() == $villeArrv))
                $trajets2[] = $trajet;
            elseif ($paysDept != $paysArrv) {
                if (($trajet->getVilleDept() == $villeDept) && ($trajet->getVilleArrv() != $villeArrv))
                    $trajets3[] = $trajet;
                elseif (($trajet->getVilleDept() != $villeDept) && ($trajet->getVilleArrv() == $villeArrv))
                    $trajets4[] = $trajet;
                elseif (($trajet->getVilleDept() != $villeDept) && ($trajet->getVilleArrv() != $villeArrv))
                    $trajets5[] = $trajet;
            }
        }
        unset($trajets);
        $trajets = ['villeDept_villeArrv' => $trajets2, 'villeDept_villeArrvDif' => $trajets3, 'villeDeptDif_villeArrv' => $trajets4, 'villeDeptDif_villeArrvDif' => $trajets5];
        $trajets = $trajets['villeDept_villeArrv'];
        return $trajets;
    }

    #[Route('/', name: 'app_trajet_index', methods: ['GET'])]
    public function index(Request $request, TrajetRepository $em): Response
    {
        if (!empty($request->query->get('villeArrv'))) {
            $dept = $this->formateVille($request->query->get('villeDept'), 'Dept');
            $arrv = $this->formateVille($request->query->get('villeArrv'), 'Arrv');
            $dateDept = $this->formateDate($request->query->get('dateDept'), 'GET');
            $trajets = $em->getTrajetWithUsers($dept['villeDept'], $arrv['villeArrv'], $dept['paysDept'], $arrv['paysArrv'], $dateDept);
            if ($trajets)
                $trajets = $this->dispatchEntity($trajets, $dept['villeDept'], $arrv['villeArrv'], $dept['paysDept'], $arrv['paysArrv']);
            $sep = empty($dept['villeDept']) ? 'vers ' : ' - ';
            return $this->render('trajet/index.html.twig', ['title' => 'Trajet: ' . $dept['villeDept'] . $sep . $arrv['villeArrv'] . ' - Obled.fr', 'trajets' => $trajets]);
        } else {
            $trajets = $em->getTrajetWithUsers('', 'toulouse', '', 'france', '2020-01-22');
            if ($trajets)
                $trajets = $this->dispatchEntity($trajets, '', 'Toulouse', '', 'France');
            return $this->render('trajet/index.html.twig', [
                'trajets' => $trajets, 'title' => 'Obled.fr',
            ]);
        }
    }

    #[Route('/{page}/{villeDept}/{villeArrv}/{id}/{hashedCode}', name: 'app_affiche_Entity')]
    public function afficheEntity($page, EntityManagerInterface $em, BuildHashedCode $buildCode, $villeDept, $villeArrv, $id, $hashedCode)
    {
        $em = ($page == 'reservation') ? $em->getRepository(Reservation::class) : $em->getRepository(trajet::class);
        if ($entity = $em->findOneBy(['id' => $id, 'published' => true])) {
            $interval = $entity->getUpdatedAt()->diff(new \Datetime());
            if ($interval->format('%a') == 0 && $interval->format('%h') == 0 && $interval->format('%i') < 30) {
                $hashedCodeOrigin = $buildCode->buildHashedCodeOrigin($hashedCode, $entity->getHashedCode2());
                if (password_verify($entity->getCodeUser(), $hashedCodeOrigin)) {
                    if ($page == 'reservation' && $entity instanceof Reservation)
                        return $this->render('reservation/show.html.twig', ['title' => 'Réservation de place : ' . $villeDept . ' - ' . $villeArrv . ' - Obled.fr', 'reservation' => $entity]);
                    elseif ($page == 'publication' && $entity instanceof trajet) {
                        $title = ($entity->getCreatedAt() != $entity->getUpdatedAt()) ? 'Modification d\'un trajet' : 'Publication d\'un trajet';
                        dd($entity);
                        return $this->render('trajet/publication.html.twig', ['title' => $title, 'trajet' => $entity]);
                    }
                }
            }
        }
        return $this->redirectToRoute('app_trajet');
    }

    #[Route('/new', name: 'app_trajet_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $addDataPost = [];
        $trajet = (!empty($trajet)) ? $trajet : new Trajet;
        $form = $this->createForm(TrajetType::class, $trajet);
        if ($request->isMethod('POST')) {
            $addDataPost = $this->checkValidityOfValuesPost($request, $form, $trajet, $em);
            if ($addDataPost['errorData']) {
                $trajet = $addDataPost['trajet'];
                $form = $addDataPost['form'];
            }
        }
        return $this->render('trajet/new.html.twig', ['addDataPost' => $addDataPost, 'trajet' => $trajet, 'form' => $form]);
    }

    #[Route('/{id<\d+>}', name: 'app_trajet_show', methods: ['GET'])]
    public function show(Trajet $trajet): Response
    {
        return $this->render('trajet/show.html.twig', [
            'trajet' => $trajet,
        ]);
    }

    #[Route('/edit', name: 'app_trajet_edit', methods: ['GET', 'POST'])]
    #[Route('/supprimer-un-trajet', name: 'app_trajet_edit_delete', methods: ['GET'])]
    public function edit(Request $request, GetTrajet $getTrajet, EntityManagerInterface $em): Response
    {
        $addDataPost = [];
        if ($request->isMethod('POST')) {
            $post = $request->request->all('trajet');
            if ((count(array_keys($post)) < 5 && isset($post['email']) && isset($post['codeUser'])) or (count(array_keys($post)) > 5 && !empty($post['id']) && !empty($post['hashedCode']))) {
                if ($trajet = $getTrajet->execute($post, $em)) {
                    if (!is_object($trajet)) {
                        $erroModif = $trajet;
                        $trajet = null;
                    }
                }
            }
        }
        $trajet = (!empty($trajet)) ? $trajet : null;
        $form = $this->createForm(TrajetType::class, $trajet);
        if ($request->isMethod('POST')) {
            if (count(array_keys($post)) > 5 && !empty($post['id']) && !empty($post['hashedCode'])) {
                $addDataPost = $this->checkValidityOfValuesPost($request, $form, $trajet, $em);
                if ($addDataPost['errorData']) {
                    $trajet = $addDataPost['trajet'];
                    $form = $addDataPost['form'];
                }
            }
        }
        $delete = ($request->getPathInfo() == '/trajet/supprimer-un-trajet') ? true : false;
        return $this->render($trajet ? 'trajet/new.html.twig' : 'trajet/edit.html.twig', ['addDataPost' => $addDataPost, 'trajet' => $trajet, 'form' => $form, 'delete' => $delete]);
    }

    #[Route('/delete', name: 'app_trajet_delete', methods: ['POST'])]
    public function delete(Request $request, GetTrajet $getTrajet, EntityManagerInterface $em, MailerInterface $mailer): Response
    {
        if ($request->isMethod('POST')) {
            $post = $request->request->all('trajet');
            if (count(array_keys($post)) < 5 && isset($post['email']) && isset($post['codeUser'])) {
                if ($trajet = $getTrajet->execute($post, $em)) {
                    if (!is_object($trajet)) {
                        $erroModif = $trajet;
                        $trajet = null;
                        return $this->redirectToRoute('app_trajet_edit_delete', [], Response::HTTP_SEE_OTHER);
                    }
                }
            }
            if ($this->isCsrfTokenValid('deleteOneTrajet', $request->request->get('_token'))) {
                dd($trajet);
                if ($reservations = $trajet->getReservations()) {
                    foreach ($reservations as $reservation) {
                        if ($reservation->isPaiement()) {
                            //remboursement
                            $fraisRsrv = $reservation->getPrixPlaceRsrv() * 0.15;
                            // $this->addFlash('danger', 'Reservation annulé, informer le client '.$entity->getNumPhone().' en l\'envoyant ses frais de reservation: '.$fraisRsrv);
                            $email = (new TemplatedEmail())
                                ->from(new Address('admin@obled.com', 'Partner'))
                                ->to($reservation->getMailPassager())
                                ->subject('Voyage annulé: Paiement passager!')
                                ->htmlTemplate('trajet/emailSupTrajetPassager.html.twig');
                            $mailer->send($email);
                        }
                        $email = (new TemplatedEmail())
                            ->from(new Address('admin@obled.com', 'Partner'))
                            ->to($trajet->getMail())
                            ->subject('Voyage annulé!')
                            ->htmlTemplate('trajet/emailSupTrajetChauf.html.twig');
                        $mailer->send($email);
                    }
                }
                $em->remove($trajet);
                $em->flush();
                // $this->addFlash('sup', 'Votre trajet à été supprimé avec succès');
            }
        }
        return $this->redirectToRoute('app_trajet_index', [], Response::HTTP_SEE_OTHER);
    }

    public function checkValidityOfValuesPost($request, $form, $trajet, $em)
    {
        $post = $request->request->all('trajet');
        foreach ($post as $key => $value)
            $post[$key] = is_string($value) ? nl2br($value) : $value;
        $request->request->set('trajet', $post);
        $dept = $this->formateVille($post['villeDept'], 'Dept', 'Entrer votre ville de départ et le pays départ (ex: Francfort, Allemagne)');
        $arrv = $this->formateVille($post['villeArrv'], 'Arrv', 'Entrer la ville d\'arrivée et le pays d\'arrivée (ex. Londres, Royaume-Uni)');
        $dateDept = $this->formateDate($post['dateDept'], 'Post', $post['heureDept'], $post['minuteDept']);
        $anneeNaiss = $this->formateDate($post['anneeNaiss']);
        $addDataPost = array_merge($dept, $arrv, $dateDept, $anneeNaiss);
        $addDataPost['errorData'] = FALSE;
        if ($dept['msgErrorDept'] or $arrv['msgErrorArrv'] or $dateDept['msgErrorDate'] or $anneeNaiss['msgErrorNaiss'])
            $addDataPost['errorData'] = TRUE;
        if ($form->handleRequest($request)->isValid() && $form->isSubmitted() && $addDataPost['errorData'] == FALSE) {
            $trajet->setVilleDept($dept['villeDept']);
            $trajet->setVilleArrv($arrv['villeArrv']);
            $trajet->setPaysDept($dept['paysDept']);
            $trajet->setPaysArrv($arrv['paysArrv']);
            $trajet->setDateDept($dateDept['dateDept']);
            if (empty($anneeNaiss['msgErrorNaiss']))
                $trajet->setAnneeNaiss($anneeNaiss['anneeNaiss']);
            if ($this->getUser())
                $trajet->setUser($this->getUser());
            if (empty($trajet->getId())) {
                $em->persist($trajet);
                // $this->addFlash('success', 'Votre trajet à été publié en ligne avec succès');
            } else {
                $trajet->setPublish(true);
                // $this->addFlash('success', 'Votre trajet à été modifié avec succès');
            }
            if ($hashedCodeTrajet = $trajet->getHashedCode()) {
                $em->flush();
                dd($trajet);
                return $this->redirectToRoute(
                    'app_affiche_Entity',
                    [
                        'page' => 'publication', 'villeDept' => $addDataPost['villeDept'], 'villeArrv' => $addDataPost['villeArrv'],
                        'id' => $trajet->getId(), 'hashedCode' => $hashedCodeTrajet
                    ],
                    Response::HTTP_SEE_OTHER
                );
            }
        }
        $addDataPost['errorData'] = TRUE;
        $addDataPost['trajet'] = $trajet;
        $addDataPost['form'] = $form;
        return $addDataPost;
    }

    public function formateVille($ville, $destination, $msgError = null)
    {
        $pays = null;
        if (!empty($ville)) { //lyon, France
            $specialCaractere = FALSE;
            $tabSepare = explode(",", $ville);
            if ($long = count($tabSepare)) {
                foreach ($tabSepare as $value) {
                    if (!$nbrWord = str_word_count($value)) {
                        $specialCaractere = TRUE;
                        break;
                    }
                }
                if ($specialCaractere == FALSE) {
                    if ($long > 1) {
                        $pays = ucfirst(trim($tabSepare[$long - 1]));
                        unset($tabSepare[$long - 1]);
                        $msgError = null;
                    }
                    $ville = ucfirst(trim(implode('', $tabSepare)));
                }
            }
        }
        if ($destination == 'Dept')
            return ['villeDept' => $ville, 'paysDept' => $pays, 'msgErrorDept' => $msgError];
        else
            return ['villeArrv' => $ville, 'paysArrv' => $pays, 'msgErrorArrv' => $msgError];
    }

    public function formateDate($date, $method = null, $heure = null, $min = null)
    {
        if (empty($method)) {
            if (preg_match('`^([0-9]{4})$`', $date)) {
                $date = $date . '-01 ' . '-01';
                $date = new \DateTime($date);
                return ['anneeNaiss' => $date, 'msgErrorNaiss' => null];
            } elseif (empty($date))
                return ['anneeNaiss' => null, 'msgErrorNaiss' => null];
            else
                return ['anneeNaiss' => $date, 'msgErrorNaiss' => 'Donner l\'année. ex: 2000'];
        }
        if (preg_match('`^([0-9]{2})/([0-9]{2})/([0-9]{4})$`', $date) && ($method == 'GET' or (is_numeric($heure) && is_numeric($min)))) {
            $date = preg_replace('`^([0-9]{2})/([0-9]{2})/([0-9]{4})$`', "$3-$2-$1", $date);
            if ($heure && $min)
                $date = $date . ' ' . $heure . ':' . $min;
            $date = new \DateTimeImmutable($date);
            if ($method == 'Post')
                return ['dateDept' => $date, 'msgErrorDate' => null];
            else
                return $date = ($date->diff(new \DateTimeImmutable())->invert) ? $date : new \DateTimeImmutable();
        } else {
            if ($method == 'Post')
                return ['dateDept' => $date, 'msgErrorDate' => 'Donner la date de départ au format (20/01/2020).'];
            else
                return $date = new \DateTimeImmutable();
        }
    }
}
