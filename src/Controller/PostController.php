<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Post;
use App\Repository\CategoryRepository;
use App\Repository\PostRepository;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PostController extends AbstractController
{
    #[Route('/posts', name: 'app_posts')]
    public function posts(PostRepository $repository): Response
    {
        $posts = $repository->findBy(
            ['isPublished' => true],
            ['createdAt' => 'DESC'],
        );
        return $this->render('post/posts.html.twig', [
            'posts' => $posts,
        ]);
    }

    #[Route('/post/{slug}', name: 'app_post')]
    public function post(PostRepository $repository, string $slug): Response
    {
        $post = $repository->findOneBy(
            ['slug' => $slug]
        );
        return $this->render('post/post.html.twig', [
            'post' => $post,
        ]);
    }


    #[Route('/posts/{slug}', name: 'app_posts_category')]
    public function postsCat(PostRepository $repository, string $slug): Response
    {
        $posts = $repository->findByCategory($slug);
        return $this->render('post/posts.html.twig', [
            'posts' => $posts,
        ]);
    }
}
