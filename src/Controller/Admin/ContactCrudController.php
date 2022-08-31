<?php

namespace App\Controller\Admin;

use App\Entity\Contact;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;

class ContactCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Contact::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
        ->setEntityLabelInPlural('Demande de contact')
        ->setEntityLabelInSingular('Demandes de contact')
        ->setPageTitle("index", "SymRecipe - Administration des demandes de contact");

    }

        public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
            ->hideOnIndex(),
            TextField::new('fullName'),
            TextField::new('email')
                    ->hideOnForm(),
            TextField::new('message'),
            DateTimeField::new('createdAt')
            ->hideOnForm()
            ->setFormTypeOption('disabled', 'disabled'),
        ];
    }
    
}
