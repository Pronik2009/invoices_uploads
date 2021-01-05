<?php

namespace App\Controller\Admin;

use App\Entity\Uploads;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File;
use App\Service\UploadProcess;
use PhpOffice\PhpSpreadsheet\Reader as CSVreader;

class UploadsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Uploads::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Upload')
            ->setEntityLabelInPlural('Uploads')
//            ->setEntityPermission('ROLE_EDITOR')
//            TODO: return here when login will be done.
            ->setPageTitle('index', '%entity_label_plural% listing')
            ->setPaginatorPageSize(20)
        ;
    }
    public function configureFields(string $pageName): iterable
    {
        return [
            Field::new('id'),
            //temporary disabled native date due some bugs with new EasyAdmin
//            DateTimeField::new('date')->renderAsText(true),
            //TODO: comment this to EA github for devs notation
            Field::new('datestr'),
            Field::new('user'),
            Field::new('filename'),
        ];
    }
    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('date')
            ->add('user')
        ;
    }
    /**
     * @Route("/upload_csv", name="upload_csv")
     */
    public function upload_csv(Request $request, UploadProcess $uploadprocess): Response
    {
        /** @var File\UploadedFile $uploadedFile */
        $uploadedFile = $request->files->get('upload');
        $tmpFilePath = $uploadprocess->projectDir.$uploadprocess::TempFileName;
        $uploadedFile->move($uploadprocess->projectDir, $tmpFilePath);
        //TODO: would add here file checking with CSV format at least. $uploadprocess::CsvMimeType and try\catch on reading
        //TODO: uploaded file already stored, would check it on break lines with some service function
        //TODO: require logic, how to do with incompatible format in users cells, like string instead float, etc
        $reader = new CSVreader\Csv();
        $spreadsheet = $reader->load($tmpFilePath);
        
        $uploadprocess->removeTmp($tmpFilePath);
        return $this->render('upload/index.html.twig', [
            'controller_name' => 'UploadController',
            'filename' => $uploadedFile->getClientOriginalName(),
            'spreadsheet' => $uploadprocess->getRows($spreadsheet),
        ]);
    }
}
