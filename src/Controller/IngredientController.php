<?php

namespace App\Controller;

use App\Entity\Ingredient;
use App\Form\IngredientType;
use App\Repository\IngredientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IngredientController extends AbstractController
{
    /**
     * Fetch and display all the ingredients.
     *
     * @param IngredientRepository $ingredientRepository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    #[Route('/ingredient', name: 'ingredient.index', methods: ['GET'])]
    public function index(IngredientRepository $ingredientRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $query = $ingredientRepository->findAll();
        $ingredients = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            12
        );

        return $this->render('pages/ingredient/index.html.twig', [
            'ingredients' => $ingredients,
        ]);
    }

    /**
     * Add new ingredient to the database.
     *
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    #[Route('/ingredient/new', name: 'ingredient.new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $entityManager
    ) :Response
    {
        $ingredient = new Ingredient();

        $form = $this->createForm(IngredientType::class, $ingredient);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $ingredient = $form->getData();
            $entityManager->persist($ingredient);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'Your ingredient is now added to your list!'
            );

            return $this->redirectToRoute('ingredient');
        }



        return $this->render('pages/ingredient/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
