<?php

namespace App\Controller;

use App\Entity\Evaluation;
use App\Entity\User;
use App\Form\EvalutationFormType;
use App\Repository\EvaluationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder): Response
    {
        $form = $this->createForm(EvalutationFormType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();

            $eval = new Evaluation();

            $eval->setEmail($formData->getEmail());
            $eval->setNote($formData->getNote());
            $eval->setCommentaire($formData->getCommentaire());

            $manager->persist($eval);
            $manager->flush();
        }

        return $this->render('main/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
    /**
     * @Route("/admin", name="admin")
     */
    public function admin(EvaluationRepository $repo): Response
    {

        $evals = $repo->findAll();

        return $this->render('main/admin.html.twig', [
            'evals' => $evals
        ]);
    }
}
