<?php

namespace App\Controller;

use App\Entity\Message;
use App\Form\MessageType;


use App\Repository\MessageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class MessageController extends AbstractController
{
    #[Route('/', name: 'app_messages')]
    public function index(MessageRepository $repository): Response
    {
        return $this->render('message/index.html.twig', [
            'messages'=>$repository->findAll(),
        ]);
    }

    #[Route('/message', name: 'create_message')]
    public function create(Request $request, EntityManagerInterface $manager): Response
    {
        $message = new Message();

        $form = $this->createForm(MessageType::class, $message);

            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {

                $message->setCreatedAt(new \DateTime());
                $manager->persist($message);
                $manager->flush();
                return $this->redirectToRoute('app_messages');
            }


        return $this->render('message/create.html.twig', [
            "form" => $form->createView(),
        ]);
    }


          #[Route('/message/edit/{id}', name: 'edit_message')]
    public function edit(Message $message, Request $request, EntityManagerInterface $manager): Response
          {
              if(!$message){return $this->redirectToRoute('app_messages');}
              $form = $this->createForm(MessageType::class, $message);
              $form->handleRequest($request);
              if ($form->isSubmitted() && $form->isValid()) {
                  $manager->persist($message);
                  $manager->flush();
                  return $this->redirectToRoute('app_messages');

              }
              return $this->render('message/edit.html.twig', [
                  "form" => $form->createView(),
              ]);
          }

          #[Route('/message/delete/{id}', name: 'delete_message')]
    public function delete(Message $message, EntityManagerInterface $manager): Response
{

        if($message)
        {

        $manager->remove($message);
        $manager->flush();
        }

        return $this->redirectToRoute('app_messages');
}
        #[Route('/message/{id}', name: 'show_message', priority: -1)]
            public function show(Message $message): Response
        {
            return $this->render('message/show.html.twig', [
                'message'=>$message,
            ]);
        }





}
