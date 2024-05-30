<?php

namespace App\Controller;
use DateTimeImmutable;
use App\Entity\Recipe;
use App\Form\RecipeType;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;


class RecipeController extends AbstractController  
{
    #[Route(path: '/recette', name: 'app_recipe_index')]
    public function index(Request $request, RecipeRepository $repository, EntityManagerInterface $em): Response
    {
        // Version avec l'utilisation de fluent setter
        // $recipe = new Recipe();
        // $recipe
        // ->setTitle('Omelette au fromage')
        // ->setSlug('omelette-au-fromage')
        // ->setContent('Prenez des oeufs, cassez les et ensuite battez les en rajoutant du sel.')
        // ->setDuration(6)
        // ->setCreatedAt(new DateTimeImmutable())
        // ->setUpdatedAt(new DateTimeImmutable());
        // $em->persist($recipe);
        // $em->flush();


        // Version avec l'utilisation de fluent setter
        // $recipe = new Recipe();
        // $recipe
        // ->setTitle('Le véritable Tiramisu')
        // ->setSlug('le-veritable-tiramisu')
        // ->setContent('Dans un saladier, battez de façon vive le sucre en poudre avec les jaunes d’œuf jusqu’à ce que le mélange blanchisse.')
        // ->setDuration(6)
        // ->setCreatedAt(new DateTimeImmutable())
        // ->setUpdatedAt(new DateTimeImmutable());
        // $em->persist($recipe);
        // $em->flush();

        // $recipe = new Recipe();
        // $recipe
        // ->setTitle('Le pain perdu')
        // ->setSlug('le-pain-perdu')
        // ->setContent('Battez grossièrement les deux oeufs avec le lait et le sucre dans une assiette creuse. Coupez les toasts en deux dans le sens de la diagonale.')
        // ->setDuration(6)
        // ->setCreatedAt(new DateTimeImmutable())
        // ->setUpdatedAt(new DateTimeImmutable());
        // $em->persist($recipe);
        // $em->flush();


        // $recipe = new Recipe;
        // $recipe->setTitle('Omelette au from');
        // $recipe->setSlug('omelette');
        // $recipe->setContent('Prenez des oeufs, cassez les et ensuite battez les en rajoutant du sel.');
        // $recipe->setDuration(6);
        // $recipe->setCreatedAt(new DateTimeImmutable());
        // $recipe->setUpdatedAt(new DateTimeImmutable());
        
        $recipes = $repository->findAll();

        // $recipes = $em->getRepository(Recipe::class)->findAll();

        //Modification
        //Je modifier le nom de la 2 ème recette 
        // $recipes[1]->setTitle('Pundu (Saka Saka)');
        // $em->flush();

        //Suppression

        // $em->remove($recipes[4]);
        // $em->flush();

        //permet de récuperer toutes les recettes en dessous d'une durée en BD
        // $recipes = $repository->findRecipeDurationLowerThan(60);
        // dd($recipes);
        return $this->render('recipe/index.html.twig', [
            'recipes' => $recipes
        ]); 
    }

    #[Route(path: '/recette/{slug}-{id}', name: 'app_recipe_show', requirements : ['id'=> '\d+', 'slug'=> '[a-z0-9-]+'])]
    public function show(Request $request, string $slug, int $id, RecipeRepository $repository): Response
    {

        //ca nous permet de récuperer une recette à partir du slug donné en paramètre 
        $recipe = $repository->find($id);
        if($recipe->getSlug() !== $slug){
        return $this->redirectToRoute('app_recipe_show', ['id' => $recipe->getId(), 'slug' => $recipe->getSlug()]);
        }
        
        // dd($slug,$id);
        // dd($request->attributes->get('slug'),$request->attributes->getInt('id'));
        
        return $this->render('recipe/show.html.twig', [
            'recipe' => $recipe

            
               

            
        ]);
    }
    #[Route(path: '/recette/{id}/edit', name: 'app_recipe_edit')]
    public function edit(Recipe $recipe, Request $request, EntityManagerInterface $em): Response {
       // dd($recipe); 
        //cette methode prend en premier paramètre le formulaire que l'on souhaite utiliser 
        // en second paramètre elle prend les données
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);
        // dd($recipe);
        if ($form->isSubmitted() && $form->isValid()){
            $recipe->setUpdatedAt(new DateTimeImmutable());
            $em->flush();
            $this->addFlash("success", "la recette a bien été modifiée");
            return $this->redirectToRoute('app_recipe_show', ['id' => $recipe->getId(), 'slug' => $recipe->getSlug()]);
        }
        return $this->render('recipe/edit.html.twig', [
            'recipe' => $recipe,
            'monForm' => $form
        ]);
    }
    #[Route(path: '/recette/create', name: 'app_recipe_create')]
    public function create( Request $request, EntityManagerInterface $em): Response {
        $recipe = new Recipe;
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $recipe->setCreatedAt(new DateTimeImmutable());
            $recipe->setUpdatedAt(new DateTimeImmutable());
            $em->persist($recipe);
            $em->flush();
            $this->addFlash("success", 'La recette'. $recipe->getTitle() .' a bien été créée');
            return $this->redirectToRoute('app_recipe_index');
        }
        return $this->render('recipe/create.html.twig', [
            'form' => $form
           

        ]);
    }
    #[Route(path : '/recette/{id}/delete', name : 'app_recipe_delete')]
     public function delete(Recipe $recipe, EntityManagerInterface $em) : Response{
        $titre=$recipe ->getTitle();
        $em->remove($recipe);
        $em->flush();
        $this->addFlash('info', 'La recette' . $titre . 'a été suprimée');
        return $this->redirectToRoute('app_recipe_index');
        }

   
  
    

}
