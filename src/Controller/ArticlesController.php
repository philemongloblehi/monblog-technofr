<?php

namespace App\Controller;

use App\Entity\Articles;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ArticlesController
 * @package App\Controller
 *
 * @Route("/actualites", name="actualites_")
 */
class ArticlesController extends AbstractController
{
    /**
     * @Route("/", name="articles")
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function list(Request $request, PaginatorInterface $paginator)
    {
        // On appelle la liste de tous mes articles
        $donnees = $this->getDoctrine()->getRepository(Articles::class)->findBy([], ['created_at' => 'desc']);

        $articles = $paginator->paginate(
            $donnees,
            $request->query->getInt('page', 1), // Numéro de la page en cours, 1 par défaut
            4
        );

        return $this->render('articles/index.html.twig', [
            'articles' => $articles,
        ]);
    }

    public function show($slug) {
        // On récupère l'article correspondant au slug
        $article = $this->getDoctrine()->getRepository(Articles::class)->findOneBy(['slug' => $slug]);
        if (!$article) {
            throw $this->createNotFoundException("L'article n'existe pas");
        }
        // Si l'article existe nous envoyons les données a la vue
        return $this->render('articles/article.html.twig', compact('article'));
    }
}
