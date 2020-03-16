<?php

namespace App\Controller;

use App\Form\ContactType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use ReCaptcha\ReCaptcha;


class ContactController extends AbstractController
{
    /**
     * @Route("/", name="contact")
     */
    public function contact(Request $request, \Swift_Mailer $mailer)
    {
        $form = $this->createForm(ContactType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $contactFormData = $form->getData();

            dump($contactFormData);

            $message = (new \Swift_Message('You Got Mail!'))
                ->setFrom($contactFormData['email'])
                ->setTo('subconit@mailforspam.com')
                ->setBody(
                    "Name : $contactFormData[name]<br>
                           Last Name : $contactFormData[lastName]<br>
                           Email : $contactFormData[email]<br> 
                           Message : $contactFormData[message] ",
                    'text/plain'
                );

            $mailer->send($message);

//            return $this->redirectToRoute('/');
        }

        return $this->render('contact/contact.html.twig', [
            'our_form' => $form->createView(),
        ]);
    }
    public function mysubmitedAction(Request $request){
        $recaptcha = new ReCaptcha('here-is-the-secret-key-that-no-one-but-you-shouldKnow');
        $resp = $recaptcha->verify($request->request->get('g-recaptcha-response'), $request->getClientIp());

        if (!$resp->isSuccess()) {
            // Do something if the submit wasn't valid ! Use the message to show something
            $message = "The reCAPTCHA wasn't entered correctly. Go back and try it again." . "(reCAPTCHA said: " . $resp->error . ")";
        }else{
            // Everything works good ;) your contact has been saved.
        }
    }
}