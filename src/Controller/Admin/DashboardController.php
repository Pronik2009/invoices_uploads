<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        // redirect to some CRUD controller
//        $routeBuilder = $this->get(\EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator::class);
//        return $this->redirect($routeBuilder->setController(InvoicesCrudController::class)->generateUrl());

        // you can also render some template to display a proper Dashboard
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        return $this->render('easyadmin/invoices.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Tallindolls')
            ->renderContentMaximized()
//            ->renderSidebarMinimized()
        ;
    }

    public function configureMenuItems(): iterable
    {
        return [
            MenuItem::linktoDashboard('Dashboard', 'fa fa-home'),
            MenuItem::section('Invoices'),
            MenuItem::linkToCrud('List', 'fas fa-list', \App\Entity\Invoices::class),
            MenuItem::linkToCrud('Add new', 'fas fa-plus', \App\Entity\Invoices::class)
                ->setAction('new'),
            MenuItem::section('Uploads'),
            MenuItem::linkToCrud('List', 'fas fa-list', \App\Entity\Uploads::class),
            MenuItem::linktoRoute('Upload Report', 'fas fa-mail', 'upload_csv', []),
            MenuItem::linktoRoute('Upload CSV', 'fas fa-mail', 'add_invoices_from_csv', []),
        ];
    }
    public function configureAssets(): Assets
    {
        return Assets::new()->addCssFile('css/admin.css');
    }
}
