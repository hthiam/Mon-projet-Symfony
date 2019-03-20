<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


use App\Entity\Article;
use App\Repository\ArticleRepository;

class BlogController extends AbstractController
{
    /**
     * @Route("/blog", name="blog")
     */
    public function index()
    {
        $repo = $this->getDoctrine()->getRepository(Article::class);
            $articles = $repo->findAll();
        return $this->render('blog/index.html.twig', [
            'controller_name' => 'BlogController',
                   'articles' => $articles
        ]);
    }

    /**
     * @Route("/filtrer", name="filtrer")
     */
    public function filtrer()
    {
        $repo = $this->getDoctrine()->getRepository(Article::class);
             $articles = $repo->findAll();
        return $this->render('blog/filtrer.html.twig',[
            'controller_name' => 'BlogController',
                   'articles' => $articles
        ]
    );
    }

    /**
     * @Route("/create", name="create")
     */
    public function create(Request $request, ObjectManager $manager)
    {
        $article = new Article();
        $form = $this->createFormBuilder($article)
                     ->add('title')
                     ->add('pseudo')
                     ->add('categorie', ChoiceType::class, [
                         'choices'=> [
                             ' '=> ' ',
                       'Bricolage' => 'bricol',
                       'Jardinage' => 'jardin',
'Depannage - Réparation Véhicule' => 'reparation',
             'Service Véhiculés' => 'livraison'
                         ]
                     ])
                     ->add('content')
                     
                     ->getForm();
        $form->handleRequest($request);
        dump($request);
            if($form->isSubmitted() && $form-> isValid()){
                    $article->setCreatedAt(new \DateTime());
                    $manager->persist($article);
                    $manager->flush();

                    return $this->redirectToRoute('blog_show',
                    ['id'=>$article->getId()]);
            
            }

        return $this->render('blog/create.html.twig',[
            'formArticle'=> $form->createView()
        ]);
    }
    /**
     * @Route("/blog/{id}", name="blog_show")
     */
    public function show($id)
    {
        $repo = $this->getDoctrine()->getRepository(Article::class);
        $article = $repo->find($id);
        return $this->render('blog/show.html.twig',[
            'article' => $article
        ]
    );
    }
}
