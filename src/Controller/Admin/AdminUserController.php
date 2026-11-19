<?php

namespace App\Controller\Admin;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Knp\Component\Pager\PaginatorInterface;

final class AdminUserController extends AbstractController
{
    #[Route('/admin/users', name: 'app_admin_users')]
    public function users(UserRepository $repository, Request $request, PaginatorInterface $paginator): Response
    {
        $users = $repository->findForPaginationFilter();
        $pagination = $paginator->paginate($users, $request->query->getInt('page', 1), 10);
    
        // dump($pagination);
        return $this->render('admin/user/users.html.twig', [
           'users' => $pagination,
        ]);
    }
}
