<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ChangePasswordFormType;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/profile')]
class AccountController extends AbstractController
{
    #[Route('/index', name: 'app_account_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('account/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    #[Route('/', name: 'app_account_show', methods: ['GET'])]
    public function show(UserRepository $ur): Response
    {
        return $this->render('account/show.html.twig', [
            'user' => $ur->findOneBy(['email' => $this->getUser()->getEmail()]),
        ]);
    }

    #[Route('/modifier-mes-informations', name: 'app_account_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, EntityManagerInterface $em, UserRepository $ur): Response
    {
        $user = $ur->findOneBy(['email' => $this->getUser()->getEmail()]);
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Vos informations ont été modifier avec succès.');
            return $this->redirectToRoute('app_account_show', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('account/edit.html.twig', [
            'user' => $user,
            'registrationForm' => $form,
        ]);
    }
    
    #[Route('/modifier-mon-mot-de-passe', name: 'app_account_change_password', methods: ['GET', 'POST'])]
    public function changePassword(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(ChangePasswordFormType::class, null, ['MotDePasseActuel_is_required' => true, 'method' => 'PATCH']);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = new User;
            $encodedPassword = $passwordHasher->hashPassword(
                $user,
                $form->get('plainPassword')->getData()
            );
            $user = $this->getUser();
            $user->setPassword($encodedPassword);
            $em->flush();
            $this->addFlash('success', 'Votre mot de passe a été modifier avec succès.');
            return $this->redirectToRoute('app_account_show', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('account/change_password.html.twig', [
            'resetForm' => $form,
        ]);
    }

    #[Route('/delete', name: 'app_account_delete', methods: ['POST'])]
    public function delete(Request $request, EntityManagerInterface $entityManager): Response
    {
        if ($user = $this->getUser()) {
            if ($this->isCsrfTokenValid('deleteCompte'.$user->getId(), $request->getPayload()->get('_token'))) {
                $entityManager->remove($user);
                $entityManager->flush();
                $this->addFlash('danger', 'Votre compte a été supprimer avec succès.');
            }
        }

        return $this->redirectToRoute('app_trajet_index', [], Response::HTTP_SEE_OTHER);
    }
}
