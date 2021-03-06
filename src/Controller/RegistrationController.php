<?php

namespace App\Controller;
use App\Entity\Utilisateur;
use App\Security\EmailVerifier;
use Symfony\Component\Mime\Email;
use App\Form\Password1erefoisType;
use App\Form\RegistrationFormType;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mailer\Mailer;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UtilisateurRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
{
    private $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier, SluggerInterface $slugger)
    {
        $this->emailVerifier = $emailVerifier;
        $this->slugger = $slugger;
    }

    private function getSlugger(Utilisateur $user) : string{
        $slug = mb_strtolower($user->getUsername() . '-' . time(), 'UTF8');
        return $this->slugger->slug($slug);                        
    }

    public function uniqidReal($lenght = 8) {
        // uniqid donne 8 caratères, mais pouvez ajuster si vous voulez.
        if (function_exists("random_bytes")) {
            $bytes = random_bytes(ceil($lenght / 2));
        } elseif (function_exists("openssl_random_pseudo_bytes")) {
            $bytes = openssl_random_pseudo_bytes(ceil($lenght / 2));
        } else {
            throw new Exception("no cryptographically secure random function available");
        }
        return substr(bin2hex($bytes), 0, $lenght);
    }

     /**
      * @Route("/admin/utilisateur/new", name="app_utilisateur_new", methods={"GET", "POST"})
      */
    public function register(Request $request, EntityManagerInterface $entityManager,MailerInterface $mailer,UserPasswordHasherInterface $encoder): Response
    {

        if ($this->isGranted('ROLE_SUPERADMIN') ) {
            $user = new Utilisateur();

        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
             //Recuperation du mot de passe non encodé pour envoie pour première connexion
             $provisoire = $user->getPassword();
             $identifiant = $user->getEmail(); 
             //Encodage du mot de passe

              $password = $encoder->hashPassword($user , $user->getPassword());
            
              $user->setPassword($password);
            //     $password = $user->getPassword();
                $user->setSlug($this->getSlugger($user));
           
            $entityManager->persist($user);
            $entityManager->flush();

            // generate a signed url and email it to the user
            $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
                (new TemplatedEmail())
                    ->from(new Address('nwehlav@gmail.com', '"Quidideance Contact"'))
                    ->to($user->getEmail())
                    ->subject('Confirmation de votre compte') 
                      // pass variables (name => value) to the template
    ->context([
        'expiration_date' => new \DateTimeImmutable('+7 days'),
        'provisoire' => $provisoire,
        'identifiant'=> $identifiant,
    ])
          
                    ->htmlTemplate('registration/confirmation_email.html.twig')
                    
                    
            );

            $this->addFlash("success","La création a bien été effectuée");

             return $this->redirectToRoute('app_utilisateur_index');
        }

            return $this->render('utilisateur/new.html.twig', [
            'utilisateur' => $user,
            'form' => $form->createView(),
        ]);
    }
      else{
         return $this->redirectToRoute('app_accueil', [], Response::HTTP_SEE_OTHER);
       }
    }

    /**
     * @Route("/verify/email", name="app_verify_email")
     */
    public function verifyUserEmail(Request $request, TranslatorInterface $translator, UtilisateurRepository $utilisateurRepository): Response
    {
        $id = $request->get('id');

        if (null === $id) {
            return $this->redirectToRoute('app_register');
        }

        $user = $utilisateurRepository->find($id);

        if (null === $user) {
            return $this->redirectToRoute('app_register');
        }

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $user);
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $translator->trans($exception->getReason(), [], 'VerifyEmailBundle'));

            return $this->redirectToRoute('app_register');
        }

        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        $this->addFlash('success', 'Votre email a bien été validée.');

        return $this->redirectToRoute('compte_premiere_connexion');
    }

    
     /**
     * @Route("/admin/modifier-mon-mot-de-passe-première-fois", name="compte_premiere_connexion")
     */
    public function modifierPasswordPremiereConnexion(Request $request, UserPasswordHasherInterface $encoder, EntityManagerInterface $entityManager): Response
    {
        $notification = null;
        //Récupération de l'utilisateur
        $user = $this->getUser();
        //$user = $this->user;
        //Appel du formulaire
        $form = $this->createForm(Password1erefoisType::class, $user);

        //Traitement du formulaire
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {                           
               // Encode(hash) the plain password, and set it.
            $new = $form->get('new_password')->getData();   
            $encodedPassword = $encoder->hashPassword(
                $user,
                $new
            );

            $user->setPassword($encodedPassword);
                       
            $entityManager->persist($user);
            

            //Mettre à jour sur la base de 
            $entityManager->flush();

            //Notification pour dire que le mot de passe est bien changé
            $notification = "Votre mot de passe a bien été mis à jour.";
                return $this->redirectToRoute('app_logout', [], Response::HTTP_SEE_OTHER);
            }else{
                $notification = "Echec de modification de votre mot de passe! veuillez recommencer, merçi.";
                return $this->render('compte/compte_password_1erefois.html.twig', [
                    'form' => $form->createView(),
                    'notification' => $notification
                ]);
            
            }
        }
}