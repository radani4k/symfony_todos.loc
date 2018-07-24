<?php

namespace App\Controller;

use App\Entity\Todo;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class TodosController extends AbstractController
{

    /**
     * @Route("/", name="homepage")
     */
    public function homepage()
    {
        $repository = $this->getDoctrine()->getRepository(Todo::class);

        $todos = $repository->findAll();

        return $this->render('todos/homepage.html.twig', [
            'todos' => $todos
        ]);
    }

    /**
     * @Route("/todo/add", name="add")
     */
    public function addTodo() {
        $entityManager = $this->getDoctrine()->getManager();
        $request = Request::createFromGlobals();
        $message = $request->get('message');

        $todo = new Todo();
        $todo->setMessage($message);
        $todo->setStatus(0);

        $entityManager->persist($todo);
        $entityManager->flush();

        return $this->redirectToRoute('homepage');
    }

    /**
     * @Route("/todo/remove/{id}", name="remove")
     */
    public function removeTodo($id) {
        $entityManager = $this->getDoctrine()->getManager();
        $todo = $entityManager->getRepository(Todo::class)->find($id);
        $entityManager->remove($todo);
        $entityManager->flush();

        return $this->redirectToRoute('homepage');
    }

    /**
     * @Route("/todo/edit/{id}", name="edit")
     */
    public function update($id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $todo = $entityManager->getRepository(Todo::class)->find($id);

        if (!$todo) {
            throw $this->createNotFoundException(
                'No todo found for id '.$id
            );
        }

        $todo->setStatus(!$todo->getStatus());
        $entityManager->persist($todo);
        $entityManager->flush();

        return $this->redirectToRoute('homepage');
    }
}