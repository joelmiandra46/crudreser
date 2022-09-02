<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AccountType;
use App\Entity\PasswordUpdate;
use App\Form\RegistrationType;
use App\Form\PasswordUpdateType;
use Symfony\Component\Form\FormError;
use Doctrine\ORM\EntityManagerInterface;
use function PHPUnit\Framework\returnValue;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AccountController extends AbstractController
{
    /**
     * Permet d'afficher et de gerer le formulaire de connexion
     *
     * @return Response
     */
    #[Route('/login', name: 'account_login')]
    public function login(AuthenticationUtils $utils): Response
    {
        // get the login error if there is one
        $error = $utils->getLastAuthenticationError();
        $username = $utils->getLastUsername();
        
        return $this->render('account/login.html.twig',[
            'hasError' => $error !== null,
            'username' => $username
        ]);
    }

    /**
     * Permet de se deconnecter
     *
     * @return void
     */
    #[Route('/logout', name: 'account_logout')]
    public function logout(){
        //... riEN
    }

    /**
     * Permet d'afficher le formulaire d'inscription
     *
     * @return Response
     */
    #[Route('/register', name: 'account_register')]
    public function register(Request $request, EntityManagerInterface $manager, UserPasswordHasherInterface $hasher)
    {
        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hash = $hasher->hashPassword($user, $user->getPassword());
            $user->setPassword($hash);

            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success',
                "Votre compte a bien été créé ! Vous pouvez mainteant vous connecter !"
            );

            return $this->redirectToRoute('account_login');
        }

        return $this->render('account/registration.html.twig',[
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet d'afficher et de traiter le formulaire de modificationde profil
     *
     * 
     * @return void
     */
    #[Route('/account/profil', name: 'account_profil')]
    #[IsGranted('ROLE_USER')]
    public function profil(Request $request, EntityManagerInterface $manager) {
        $user = $this->getUser();
        $form = $this->createForm(AccountType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success',
                "Les données du profil ont été enregistrée avec succès ."
            );
        }
        return $this->render('account/profil.html.twig',[
            'form' => $form->createView()
        ]);
    }
    /**
     * Permet de modifier le mot de passe
     * 
     *
     * @return Response
     */
    #[Route('/account/password-update', name: 'account_password')]
    #[IsGranted('ROLE_USER')]
    public function updatePassword(Request $request,UserPasswordHasherInterface $hasher, EntityManagerInterface $manager){
        $passwordUpdate = new PasswordUpdate();
        $user = $this->getUser();

        $form = $this->createForm(PasswordUpdateType::class, $passwordUpdate);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            //1-verifier que le old password du formulaire soit le meme que le password de l'user
            if (!password_verify( $passwordUpdate->getOldPassword(), $user->getPassword())) {
                //gerer d'erreur
                $form->get('oldPassword')->addError(new FormError("Le mot de passe que vous avez tapez n'est pas votre mot de passe actuel !"));
            }else{
                $newPassword = $passwordUpdate->getNewPassword();
                $hash = $hasher->hashPassword( $user, $newPassword);

                $user->setPassword($hash);

                $manager->persist($user);
                $manager->flush();
                $this->addFlash(
                    'success',
                    "Votre mot de passe a bien été modifié ."
                );
                return $this->redirectToRoute('home_index');
            }
        }
        return $this->render('account/password.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
