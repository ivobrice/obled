<?php

namespace App\Controller;

use App\Entity\Trajet;
use App\Entity\Reservation;
use App\Form\ReservationType;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/reservation')]
class ReservationController extends AbstractController
{
    #[Route('/', name: 'app_reservation_index', methods: ['GET'])]
    public function index(ReservationRepository $reservationRepository): Response
    {
        return $this->render('reservation/index.html.twig', [
            'reservations' => $reservationRepository->findAll(),
        ]);
    }

    #[Route('/trajet', name: 'app_reservation_trajet', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        if ($request->isMethod('POST')) {
            $post = $request->request->all();
            if (!empty($post['mailPassager']) && !empty($post['nbrDePlaceRsrv']) && !empty($post['idTrajet'])) {
                if ($trajet = $em->getRepository(trajet::class)->find($post['idTrajet'])) {
                    if ($this->checkTimeDept($trajet->getDateDept())) {
                        $nbPlace = $trajet->getNbDePlace() - $post['nbrDePlaceRsrv'];
                        if ($nbPlace >= 0) {
                            $reservation = new Reservation();
                            $reservation->setTrajet($trajet);
                            $reservation->setPrixPlaceRsrv($post['nbDePlaceRsrv']);
                            $reservation->setMailPassager($post['mailPassager']);
                            if (!empty($post['phonePassager']))
                                $reservation->setPhonePassager($post['phonePassager']);
                            $reservation->setMailChauf($trajet->getEmail());
                            $reservation->setPhoneChauf($trajet->getPhone());
                            $reservation->setDateDept($trajet->getDateDept());
                            $reservation->setVilleDept($trajet->getVilleDept());
                            $reservation->setVilleArrv($trajet->getVilleArrv());
                            if ($this->getUser())
                                $reservation->setUser($this->getUser());
                            $em->persist($reservation);
                            if ($hashedCodeClient = $reservation->getHashedCode()) {
                                $em->flush();
                                return $this->redirectToRoute('app_afficheEntity', 
                                ['page' => 'reservation', 'villeDept' => $trajet->getVilleDept(),
                                 'villeArrv' => $trajet->getVilleArrv(), 'id' => $reservation->getId(), 
                                 'hashedCode' => $hashedCodeClient],  Response::HTTP_SEE_OTHER);
                            }
                        } else {
                            if ($trajet->getNbrDePlace() == 1)
                                $place = 'qu\'une place disponible';
                            elseif ($trajet->getNbrDePlace() > 1) 
                                $place = 'que ' . $trajet->getNbrDePlace() . ' places disponibles';
                            else
                                $place = 'de place disponible';
                            $this->addFlash('danger', 'Plus ' . $place . ' pour ce trajet !');
                            return $this->redirectToRoute('app_trajet_show', ['id' => $post['idTrajet'], 'villeDept' => $trajet->getVilleDept(), 'villeArrv' => $trajet->getVilleArrv()]);
                        }
                    } else
                        $this->addFlash('danger', 'Réservation impossible, la date de départ du voyage est dépassé.');
                }
            } else
                $this->addFlash('danger', 'Vos informations sont incomplètes');
            return $this->redirectToRoute('app_trajet');
        }
        return $this->redirectToRoute('app_trajet');
    }

    #[Route('/{id}', name: 'app_reservation_show', methods: ['GET'])]
    public function show(Reservation $reservation): Response
    {
        return $this->render('reservation/show.html.twig', [
            'reservation' => $reservation,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_reservation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reservation $reservation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reservation/edit.html.twig', [
            'reservation' => $reservation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reservation_delete', methods: ['POST'])]
    public function delete(Request $request, Reservation $reservation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reservation->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($reservation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
    }

    public function checkTimeDept($dateDept)
    {
        $currentDate = new \Datetime();
        $interval = $currentDate->diff($dateDept);
        if ($interval->format('%R') == '+') {
            if (($dateDept->format('Y-m-d') == $currentDate->format('Y-m-d')) && $interval->format('%h') == 0 && $interval->format('%i') < 12)
                return FALSE;
            return TRUE;
        }
        return FALSE;
    }
}
