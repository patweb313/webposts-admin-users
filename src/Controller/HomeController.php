<?php

namespace App\Controller;

use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(PostRepository $repository): Response
    {
        $posts = $repository->findBy(
            ['isPublished' => true],
            ['createdAt' => 'DESC'],
            3
        );
        return $this->render('home/index.html.twig',[
            'posts' => $posts,
        ]);
    }
}
