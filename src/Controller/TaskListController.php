<?php

namespace App\Controller;

use App\Entity\TaskList;
use App\Form\TaskListType;
use App\Repository\TaskListRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/task-list")
 */
class TaskListController extends AbstractController
{
    /**
     * @Route("/", name="task_list_index", methods={"GET"})
     * @param TaskListRepository $taskListRepository
     * @return Response
     */
    public function index(TaskListRepository $taskListRepository): Response
    {
        $taskList = new TaskList();
        $form = $this->createForm(TaskListType::class, $taskList, ['action' => $this->generateUrl('task_list_new')]);

        return $this->render('task_list/index.html.twig', [
            'task_lists' => $taskListRepository->findAll(),
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/new", name="task_list_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $taskList = new TaskList();
        $form = $this->createForm(TaskListType::class, $taskList);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $taskList->setUserId($this->getUser()->getId());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($taskList);
            $entityManager->flush();

            return $this->redirectToRoute('task_list_index');
        }

        return $this->render('task_list/new.html.twig', [
            'task_list' => $taskList,
            'form' => $form->createView(),
        ]);
    }

//    /**
//     * @Route("/{id}", name="task_list_show", methods={"GET"})
//     */
//    public function show(TaskList $taskList): Response
//    {
//        return $this->render('task_list/show.html.twig', [
//            'task_list' => $taskList,
//        ]);
//    }

    /**
     * @Route("/{id}/edit", name="task_list_edit", methods={"POST"})
     */
    public function edit(Request $request, TaskList $taskList, SerializerInterface $serializer): Response
    {
        $form = $this->createForm(TaskListType::class, $taskList);

        if ($request->isXmlHttpRequest()) {
            $form->submit(array_merge($serializer->normalize($taskList, 'array'), $request->request->all()));
        }
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
//            if ($form->isValid()) {

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($taskList);
                $taskList->setDone($request->request->get('done', 0));
                $entityManager->flush();

                if ($request->isXmlHttpRequest()) {
                    return $this->json(['message' => $request->request->get('done', 0)], Response::HTTP_OK);
                }

                return $this->redirectToRoute('task_list_index');
//            } else {
//                return $this->json(['message' => $form->getErrors()], Response::HTTP_OK);
//
//            }

        }
        dd(111);
        if ($request->isXmlHttpRequest()) {
            return $this->json(['message' => $form->isSubmitted()], Response::HTTP_BAD_REQUEST);
        }

        return $this->render('task_list/edit.html.twig', [
            'task_list' => $taskList,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="task_list_delete", methods={"DELETE"})
     */
    public function delete(Request $request, TaskList $taskList): Response
    {
        $message = 'Bad request!';
        $status = Response::HTTP_BAD_REQUEST;

        if ($this->isCsrfTokenValid('delete'.$taskList->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($taskList);
            $entityManager->flush();

            $message = 'Item was Deleted!';
            $status = Response::HTTP_OK;
        }

        if ($request->isXmlHttpRequest()) {
            return $this->json(['message' => $message], $status);
        }

        return $this->redirectToRoute('task_list_index');
    }
}
