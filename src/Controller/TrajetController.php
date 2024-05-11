<?php

namespace App\Controller;

use App\Entity\Trajet;
use App\Form\TrajetType;
use App\Repository\TrajetRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/trajet')]
class TrajetController extends AbstractController
{
    #[Route('/', name: 'app_trajet_index', methods: ['GET'])]
    public function index(TrajetRepository $trajetRepository): Response
    {
        return $this->render('trajet/index.html.twig', [
            'trajets' => $trajetRepository->findAll(),
        ]);
    }

    #[Route('/{page}/{villeDept}/{villeArrv}/{id}/{hashedCode}', name: 'app_affiche_Entity')]
    public function afficheEntity($page, EntityManagerInterface $em, BuildHashedCode $buildCode, $villeDept, $villeArrv, $id, $hashedCode)
    {
        $em = ($page == 'reservation') ? $em->getRepository(Reserver::class) : $em->getRepository(Itineraire::class);
        if ($entity = $em->findOneBy(array('id' => $id, 'published' => true))) {
            $interval = $entity->getUpdatedAt()->diff(new \Datetime());
            if ($interval->format('%a') == 0 && $interval->format('%h') == 0 && $interval->format('%i') < 30) {
                $hashedCodeOrigin = $buildCode->buildHashedCodeOrigin($hashedCode, $entity->getHashedCode2());
                if (password_verify($entity->getCodeUser(), $hashedCodeOrigin)) {
                    if ($page == 'reservation' && $entity instanceof Reserver)
                        return $this->render('itineraire/reservation.html.twig', array('title' => 'Réservation de place : ' . $villeDept . ' - ' . $villeArrv . ' - MonColigo.fr', 'reservation' => $entity));
                    elseif ($page == 'publication' && $entity instanceof Itineraire) {
                        $title = ($entity->getCreatedAt() != $entity->getUpdatedAt()) ? 'Modification d\'une annonce' : 'Publication d\'une annonce';
                        dd($entity);
                        return $this->render('itineraire/publication.html.twig', array('title' => $title, 'Itineraire' => $entity));
                    }
                }
            }
        }
        return $this->redirectToRoute('app_itineraire');
    }

    #[Route('/new', name: 'app_trajet_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $addDataPost = FALSE;
        $trajet = new Trajet();
        $form = $this->createForm(TrajetType::class, $trajet);
        if ($request->isMethod('POST')) {
            $addDataPost = $this->checkValidityOfValuesPost($request, $form, $trajet);
            if ($addDataPost['errorData'] == FALSE) {
                $trajet = $addDataPost['trajet'];
                if ($this->getUser()) {
                    if ($trajet->getNumPhone() != $this->getUser()->getNumPhone()) {
                        $this->getUser()->setNumPhone($trajet->getNumPhone());
                        $em->persist($this->getUser());
                    }
                    $trajet->setUser($this->getUser());
                }
                if (empty($id)) {
                    $em->persist($trajet);
                    // $this->addFlash('success', 'Votre trajet à été publié en ligne avec succès');
                } else {
                    //$trajet->setPublished(true);
                    $this->addFlash('success', 'Votre trajet à été modifié avec succès');
                }
                if ($hashedCodeTrajet = $trajet->getHashedCode()) {
                    $em->flush();
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
            $form = $addDataPost['form'];
        }

        return $this->render($trajet->getId() ? 'trajet/edit.html.twig' : 'trajet/new.html.twig', ['addDataPost' => $addDataPost, 'trajet' => $trajet, 'form' => $form]);
    }

    #[Route('/{id}', name: 'app_trajet_show', methods: ['GET'])]
    public function show(Trajet $trajet): Response
    {
        return $this->render('trajet/show.html.twig', [
            'trajet' => $trajet,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_trajet_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Trajet $trajet, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TrajetType::class, $trajet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_trajet_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('trajet/edit.html.twig', [
            'trajet' => $trajet,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_trajet_delete', methods: ['POST'])]
    public function delete(Request $request, Trajet $trajet, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $trajet->getId(), $request->request->get('_token'))) {
            $entityManager->remove($trajet);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_trajet_index', [], Response::HTTP_SEE_OTHER);
    }

    public function checkValidityOfValuesPost($request, $form, $trajet)
    {
        $post = $request->request->all('trajet');
        foreach ($post as $key => $value)
            $post[$key] = (is_string($value)) ? nl2br($value) : $value;
        $request->request->set('trajet', $post);
        $Dept = $this->formateVille($post['villeDept'], 'Dept', 'Entrer votre ville de départ et le pays départ (ex: Francfort, Allemagne)');
        $Arrv = $this->formateVille($post['villeArrv'], 'Arrv', 'Entrer la ville d\'arrivée et le pays d\'arrivée (ex. Londres, Royaume-Uni)');
        $dateDept = $this->formateDate($post['dateDept'], 'Post', $post['heureDept'], $post['minuteDept']);
        $anneeNaiss = $this->formateDate($post['anneeNaiss']);
        $addDataPost = array_merge($Dept, $Arrv);
        $addDataPost['msgErrorDate'] = (is_array($dateDept)) ? $dateDept['msgErrorDate'] : null;
        $addDataPost['msgErrorNaiss'] = (is_array($anneeNaiss)) ? $anneeNaiss['msgErrorNaiss'] : null;
        $addDataPost['errorData'] = FALSE;
        if ($Dept['msgErrorDept'] or $Arrv['msgErrorArrv'] or $addDataPost['msgErrorDate'] or $addDataPost['msgErrorNaiss'])
            $addDataPost['errorData'] = TRUE;
        if ($form->handleRequest($request)->isValid() && $form->isSubmitted() && $addDataPost['errorData'] == FALSE) {
            $trajet->setVilleDept($Dept['villeDept']);
            $trajet->setVilleArrv($Arrv['villeArrv']);
            $trajet->setPaysDept($Dept['paysDept']);
            $trajet->setPaysArrv($Arrv['paysArrv']);
            $trajet->setDateDept($dateDept);
            $trajet->setAnneeNaiss($anneeNaiss);
            $addDataPost['trajet'] = $trajet;
        } else
            $addDataPost['errorData'] = TRUE;
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
            $date = (preg_match('`^[0-9]{4}$`', $date)) ? new \DateTime($date) : ['msgErrorNaiss' => 'Donner l\année. ex: 2000'];
            return $date;
        }
        if (preg_match('`^([0-9]{2})/([0-9]{2})/([0-9]{4})$`', $date) && ($method == 'GET' or (is_numeric($heure) && is_numeric($min)))) {
            $date = preg_replace('`^([0-9]{2})/([0-9]{2})/([0-9]{4})$`', "$3-$2-$1", $date);
            if ($heure && $min)
                $date = $date . ' ' . $heure . ':' . $min;
            $date = new \DateTimeImmutable($date);
            if ($method == 'Post')
                return $date;
            else
                return $date = ($date->diff(new \DateTimeImmutable())->invert) ? $date : new \DateTimeImmutable();
        } else {
            if ($method == 'Post')
                return ['msgErrorDate' => 'Donner la date de départ au format (20/01/2020).'];
            else
                return $date = new \DateTimeImmutable();
        }
    }
}
