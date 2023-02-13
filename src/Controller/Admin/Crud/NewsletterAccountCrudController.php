<?php

/*
 * This file is part of the Needlify project.
 *
 * Copyright (c) Needlify <https://needlify.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Controller\Admin\Crud;

use App\Entity\NewsletterAccount;
use App\Service\Newsletter\NewsletterService;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\TextFilter;
use Symfony\Contracts\Translation\TranslatorInterface;
use EasyCorp\Bundle\EasyAdminBundle\Dto\BatchActionDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Filter\BooleanFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\DateTimeFilter;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class NewsletterAccountCrudController extends AbstractCrudController
{
    public function __construct(
        private NewsletterService $newsletterService,
        private TranslatorInterface $translator
    ) {
    }

    public static function getEntityFqcn(): string
    {
        return NewsletterAccount::class;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(TextFilter::new('email'))
            ->add(BooleanFilter::new('isVerified'))
            ->add(BooleanFilter::new('isEnabled'))
            ->add(DateTimeFilter::new('verifiedAt'))
            ->add(DateTimeFilter::new('subscribedAt'))
        ;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setSearchFields(['email'])
            ->setDateTimeFormat('d LLL yyyy HH:mm:ss ZZZZ')
            ->setDefaultSort(['subscribedAt' => 'DESC'])
            ->setPageTitle(Crud::PAGE_INDEX, $this->translator->trans('admin.crud.newsletter.index.title', [], 'admin'))
            ->setPageTitle(Crud::PAGE_NEW, $this->translator->trans('admin.crud.newsletter.new.title', [], 'admin'))
            ->setPageTitle(Crud::PAGE_EDIT, $this->translator->trans('admin.crud.newsletter.edit.title', [], 'admin'))
            ->setPageTitle(Crud::PAGE_DETAIL, $this->translator->trans('admin.crud.newsletter.details.title', [], 'admin'))
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        yield FormField::addPanel('admin.crud.section.essential');
        yield IdField::new('id', 'admin.crud.newsletter.column.id')->onlyOnDetail();
        yield EmailField::new('email', 'admin.crud.newsletter.column.email');

        yield FormField::addPanel('admin.crud.section.verification');
        yield BooleanField::new('isVerified', 'admin.crud.newsletter.column.is_verified');
        yield BooleanField::new('isEnabled', 'admin.crud.newsletter.column.is_enabled')
            ->setHelp('admin.crud.newsletter.column.is_enabled.help')
            ->hideWhenCreating();

        yield FormField::addPanel('admin.crud.section.dates')->hideOnForm();
        yield DateTimeField::new('subscribedAt', 'admin.crud.newsletter.column.subscribed_at')->setTimezone('UTC')->hideOnForm();
        yield DateTimeField::new('verifiedAt', 'admin.crud.newsletter.column.verified_at')->setTimezone('UTC')->onlyOnDetail();

        yield FormField::addPanel('admin.crud.section.security')->onlyOnDetail();
        yield BooleanField::new('canRetryConfirmation', 'admin.crud.newsletter.column.can_retry_confirmation')
            ->renderAsSwitch(false)
            ->onlyOnDetail();
        yield DateTimeField::new('lastRetryAt', 'admin.crud.newsletter.column.last_retry_at')->setTimezone('UTC')->onlyOnDetail();
        yield TextField::new('token', 'admin.crud.newsletter.column.token')->onlyOnDetail();
    }

    public function configureActions(Actions $actions): Actions
    {
        $sendMail = Action::new('sendEmail', 'admin.crud.newsletter.actions.send_mail')
            ->linkToCrudAction('sendMail')
            ->displayIf(fn (NewsletterAccount $account) => !$account->getIsVerified());

        $sendMailBatch = Action::new('sendEmailBatch', 'admin.crud.newsletter.actions.send_mails')
            ->linkToCrudAction('sendMailBatch')
            ->createAsBatchAction();

        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)->update(Crud::PAGE_INDEX, Action::DETAIL, fn (Action $action) => $action->setLabel('admin.crud.action.details'))
            ->update(Crud::PAGE_INDEX, Action::NEW, fn (Action $action) => $action->setLabel('admin.crud.newsletter.actions.create'))
            ->add(Crud::PAGE_INDEX, $sendMail)
            ->add(Crud::PAGE_INDEX, $sendMailBatch)
            ->add(Crud::PAGE_DETAIL, $sendMail)
        ;
    }

    public function sendMail(AdminContext $context)
    {
        /** @var NewsletterAccount $account */
        $account = $context->getEntity()->getInstance();
        $this->newsletterService->sendVerificationMail($account);

        return $this->redirect($context->getReferrer());
    }

    public function sendMailBatch(BatchActionDto $batchActionDto)
    {
        $className = $batchActionDto->getEntityFqcn();
        $entityManager = $this->container->get('doctrine')->getManagerForClass($className);
        foreach ($batchActionDto->getEntityIds() as $id) {
            /** @var NewsletterAccount $account */
            $account = $entityManager->find($className, $id);
            $this->newsletterService->sendVerificationMail($account);
        }

        return $this->redirect($batchActionDto->getReferrerUrl());
    }
}
