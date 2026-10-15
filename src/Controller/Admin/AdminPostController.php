<?php

namespace App\Controller\Admin;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;


final class AdminPostController extends AbstractController
{
    #[Route('/admin/posts', name: 'app_admin_posts')]
    public function posts(PostRepository $repository): Response
    {
        $posts = $repository->findBy(
            [],
            ['createdAt' => 'DESC']
        );
        return $this->render('admin/posts/posts.html.twig', [
            'posts' => $posts,
        ]);
    }

    #[Route('/admin/posts/new', name: 'app_admin_newpost')]
    public function newPost(Request $request, EntityManagerInterface $manager, SluggerInterface $slugger): Response
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $post->setCreatedAt(new \DateTimeImmutable())
                 ->setUpdatedAt(new \DateTimeImmutable())
                 ->setSlug($slugger->slug($post->getTitle()));
            $manager->persist($post); // Uniquement lors d'une insertion
            $manager->flush();
            $this->addFlash(
                'info',
                'L\'article a bien été ajouté'
            );
            return $this->redirectToRoute('app_admin_posts');
        }
        return $this->render('admin/posts/newpost.html.twig',[
            'form' => $form]
        );
    }

    #[Route('/admin/posts/delete/{id}', name: 'app_admin_posts_delete')]
    public function deletePost(Post $post, EntityManagerInterface $manager): Response
    {
        $manager->remove($post);
        $manager->flush();
        return $this->redirectToRoute('app_admin_posts');
    }

    #[Route('/admin/posts/edit/{id}', name: 'app_admin_editpost')]
    public function editPost(Post $post, Request $request, EntityManagerInterface $manager, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $post->setUpdatedAt(new \DateTimeImmutable())
                 ->setSlug($slugger->slug($post->getTitle()));
            $manager->flush();

            return $this->redirectToRoute('app_admin_posts');
        }
        return $this->render('admin/posts/editpost.html.twig',[
            'form' => $form,
            'post' => $post,
        ]);
    }

    #[Route('/admin/posts/view/{id}', name: 'app_admin_posts_view')]
    public function viewPost(Post $post, EntityManagerInterface $manager): Response
    {
        // Inversion de la valeur actuelle
        $post->setIsPublished(!$post->isPublished());
        $manager->flush();
        return $this->redirectToRoute('app_admin_posts');
    }
}


