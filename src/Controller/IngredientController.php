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

            return $this->redirectToRoute('ingredient.index');
        }



        return $this->render('pages/ingredient/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Edit an existing ingredient.
     *
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @param Ingredient $ingredient
     * @return Response
     */
    #[Route('/ingredient/edit/{id}', name: 'ingredient.edit', methods: ['GET', 'POST'])]
    public function edit(
        EntityManagerInterface $entityManager,
        Request $request,
        Ingredient $ingredient,
    ): Response
    {
        $form = $this->createForm(IngredientType::class, $ingredient, [
            'button_label' => 'Update ingredient',
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $ingredient = $form->getData();
            $entityManager->persist($ingredient);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'Your ingredient has been updated!'
            );

            return $this->redirectToRoute('ingredient.index');
        }

        return $this->render('pages/ingredient/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Delete an ingredient.
     *
     * @param EntityManagerInterface $entityManager
     * @param Ingredient $ingredient
     * @return Response
     */
    #[Route('/ingredient/delete/{id}', name: 'ingredient.delete', methods: ['GET'])]
    public function delete(EntityManagerInterface $entityManager, Ingredient $ingredient): Response
    {
        $entityManager->remove($ingredient);
        $entityManager->flush();

        $this->addFlash(
            'success',
            'Your ingredient has been deleted!'
        );

        return $this->redirectToRoute('ingredient.index');

    }
}
