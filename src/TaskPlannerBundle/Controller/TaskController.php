<?php

namespace TaskPlannerBundle\Controller;

use Symfony\Component\BrowserKit\Response;
use TaskPlannerBundle\Entity\Task;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;




/**
 * Task controller.
 *
 * @Route("task")
 */
class TaskController extends Controller
{
    /**
     * Lists all task entities.
     *
     * @Route("/allusers", name="task_all_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $tasks = $em->getRepository('TaskPlannerBundle:Task')->findAll();

        return $this->render('task/index.html.twig', array(
            'tasks' => $tasks,
            'tasks2' =>[]
        ));
    }

    /**
     * Creates a new task entity.
     *
     * @Route("/new", name="task_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $task = new Task();
        $user = $this->container->get('security.context')->getToken()->getUser();
        $task->setUser($user);
        $form = $this->createForm('TaskPlannerBundle\Form\TaskType', $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($task);
            $em->flush();

            return $this->redirectToRoute('task_show', array('id' => $task->getId()));
        }

        return $this->render('task/new.html.twig', array(
            'task' => $task,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a task entity.
     *
     * @Route("/{id}", name="task_show")
     * @Method("GET")
     */
    public function showAction(Task $task)
    {
        $deleteForm = $this->createDeleteForm($task);

        return $this->render('task/show.html.twig', array(
            'task' => $task,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing task entity.
     *
     * @Route("/{id}/edit", name="task_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Task $task)
    {
        $deleteForm = $this->createDeleteForm($task);
        $editForm = $this->createForm('TaskPlannerBundle\Form\TaskType', $task);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('task_edit', array('id' => $task->getId()));
        }

        return $this->render('task/edit.html.twig', array(
            'task' => $task,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a task entity.
     *
     * @Route("/{id}", name="task_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Task $task)
    {
        $form = $this->createDeleteForm($task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($task);
            $em->flush();
        }

        return $this->redirectToRoute('task_index');
    }




    /**
     * Lists all task entities per user.
     *
     * @Route("/", name="task_index")
     * @Method("GET")
     */
    public function indexUserAction()
    {


        $user = $this->container->get('security.context')->getToken()->getUser();

        $em = $this->getDoctrine()->getManager();
        $tasks1 = $em->getRepository('TaskPlannerBundle:Task')->findUserOldTask($user);
        $tasks2 = $em->getRepository('TaskPlannerBundle:Task')->findUserCurrentTask($user);



        return $this->render('task/index.html.twig', array(
            'tasks' => $tasks1,
            'tasks2' => $tasks2));

    }

    /**
     * Lists all task entities per user.
     *
     * @Route("/testmail", name="test_mail")
     * @Method("GET")
     */

    public function sendEmailAction(){

        $transport = (new \Swift_SmtpTransport('smtp.gmail.org', 25))
                        ->setUsername('marcin.urbaniak@gmail.com')
                        ->setPassword('password');


        $mailer = new \Swift_Mailer($transport);

        $message = (new \Swift_Message('Wonderful Subject'))
            ->setFrom(['john@doe.com' => 'John Doe'])
            ->setTo(['receiver@domain.org', 'other@domain.org' => 'A name'])
            ->setBody('Here is the message itself');

        $result = $mailer->send($message);



    }




//    /**
//     * Lists all task entities per user.
//     *
//     * @Route("/testmail", name="test_mail")
//     *
//     */
//
//    public function sendSpoolAction($messages = 10)
//    {
//        $kernel = $this->get('kernel');
//        $application = new Application($kernel);
//        $application->setAutoExit(false);
//
//        $input = new ArrayInput(array(
//            'command' => 'swiftmailer:spool:send',
//            '--message-limit' => $messages,
//        ));
//        // You can use NullOutput() if you don't need the output
//        $output = new BufferedOutput();
//        $application->run($input, $output);
//
//        // return the output, don't use if you used NullOutput()
//        $content = $output->fetch();
//
//        // return new Response(""), if you used NullOutput()
//        return new Response($content);
//    }



    /**
     * Creates a form to delete a task entity.
     *
     * @param Task $task The task entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Task $task)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('task_delete', array('id' => $task->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
