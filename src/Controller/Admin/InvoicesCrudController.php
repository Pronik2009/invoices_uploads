<?php

namespace App\Controller\Admin;

use App\Entity\Invoices;
use App\Entity\Uploads;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use App\Service\UploadProcess;


class InvoicesCrudController extends AbstractCrudController
{
    private $adminUrlGenerator;
    
    public function __construct(AdminUrlGenerator $adminUrlGenerator)
    {
        $this->adminUrlGenerator = $adminUrlGenerator;
    }
    
    public static function getEntityFqcn(): string
    {
        return Invoices::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Invoice')
            ->setDateFormat('y-mm-dd')
            ->setEntityLabelInPlural('Invoices')
//            ->setEntityPermission('ROLE_EDITOR')
//            TODO: return here when login will be done.
            ->setPageTitle('index', '%entity_label_plural% listing')
            ->setPaginatorPageSize(20)
        ;
    }
    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
    /**
     * @Route("/add_invoices_from_csv/{filename}", name="add_invoices_from_csv",
     * requirements={"filename"="[ \w-]+\.csv"}
     * )
     */
    public function add_invoices_from_csv($filename, Request $request, UploadProcess $uploadprocess) {
        $em = $this->getDoctrine()->getManager();
        $upload = new Uploads();
        $upload->setFilename($filename);
//        $upload->setUser($user);
//        TODO: can store user history, after users appear in project
        $em->persist($upload);
        foreach ($request->request->getIterator() as $row) {
            $invoice = new Invoices();
            $invoice->setCustomId($row['id']);
            $invoice->setAmount($row['amount']);
            $invoice->setDueDate(\DateTime::createFromFormat('Y-m-d', $row['data']));
            $invoice->setUploads($upload);
            //Set outdated invoices to NULL for future manipulating
            if ($uploadprocess::OutdatedWarningMessage===$row['sellingprice']) $row['sellingprice'] = null;
            $invoice->setSellingprice($row['sellingprice']);
            $em->persist($invoice);
        };
        $em->flush();
        $this->addFlash('success', 'File "'.$filename.'" successfully stored!');
        $this->addFlash('warning', $request->request->count().'" invoices were imported');
//        $url = $this->adminUrlGenerator
//            ->setController(UploadsCrudController::class)
//            ->setAction(Action::INDEX)
//            ->generateUrl();
        //TODO: while EA3 have bug with AdminUrlGenerator, need use simple redirect to main page
        //Call to a member function getSignedUrls() on null
        //TODO: comment it to EA github for devs notation.
        return $this->redirect('/admin');
    }
}
