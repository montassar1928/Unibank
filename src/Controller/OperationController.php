<?php

namespace App\Controller;

use App\Entity\CompteCourant;
use App\Entity\Operation;
use App\Entity\VirementInternational;
use App\Form\OperationType;
use App\Form\InternationalType;
use App\Form\RetraitType;
use App\Form\VirementType;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use App\Repository\CompteCourantRepository;
use Symfony\Component\Security\Core\Security; // Import Security class
#[Route('/operation')]
class OperationController extends AbstractController
{
    #[Route('/admin', name: 'app_operation_index', methods: ['GET'])]
    public function index(Request $request, EntityManagerInterface $entityManager, PaginatorInterface $paginator): Response
    {
        $searchQuery = $request->query->get('q');
        $startDate = $request->query->get('start_date');
        $endDate = $request->query->get('end_date');
        $itemsPerPage = $request->query->get('items_per_page', 5); // Default to 5 if not set
    
        // Create a query builder for the Operation entity
        $queryBuilder = $entityManager->getRepository(Operation::class)->createQueryBuilder('o');
    
        // If there's a search query, add a condition to search by operation properties
        if ($searchQuery) {
            $queryBuilder->andWhere('o.numoperation LIKE :searchQuery OR o.typeoperation LIKE :searchQuery OR o.description LIKE :searchQuery')
                        ->setParameter('searchQuery', '%'.$searchQuery.'%');
        }
    
        // If start date and end date are provided, add a condition to search by date range
        if ($startDate && $endDate) {
            $queryBuilder->andWhere('o.dateoperation BETWEEN :startDate AND :endDate')
                        ->setParameter('startDate', $startDate)
                        ->setParameter('endDate', $endDate);
        }
    
        // Get the query from the query builder
        $query = $queryBuilder->getQuery();
    
        // Paginate the results
        $operations = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1), // Get the current page number, default to 1 if not set
            $itemsPerPage // Display the selected number of items per page
        );
    
        return $this->render('operation/index.html.twig', [
            'operations' => $operations,
            'searchQuery' => $searchQuery, // Pass the search query to the template
            'startDate' => $startDate, // Pass the start date to the template
            'endDate' => $endDate, // Pass the end date to the template
            'itemsPerPage' => $itemsPerPage, // Pass the items per page to the template
        ]);
    }
    
    

    #[Route('/front', name: 'op_front', methods: ['GET'])]
    public function front(EntityManagerInterface $entityManager): Response
    {
        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            // Redirect to the login page
            return $this->redirectToRoute('app_login');
        }
        $operations = $entityManager
            ->getRepository(Operation::class)
            ->findAll();

        return $this->render('operation/front.html.twig', [
            'operations' => $operations,
        ]);
    }
    #[Route('/send-test-email', name: 'send_test_email')]
    public function sendTestEmail(MailerInterface $mailer): Response
    {
        $email = (new Email())
        ->from('oumaima.khamassi@esprit.tn')
        ->to('oumaima.khamassi@esprit.tn')
            ->subject('Test Email')
            ->text('This is a test email.');
    
        $mailer->send($email);
    
        return new Response('Test email sent!');
    }
    
    #[Route('/new', name: 'app_operation_new', methods: ['GET', 'POST'])]
public function new(Request $request, EntityManagerInterface $entityManager, MailerInterface $mailer, CompteCourantRepository $compteCourantRepository, Security $security): Response
{
    if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
        // Redirect to the login page
        return $this->redirectToRoute('app_login');
    }
    // Create a new Operation instance
    $operation = new Operation();
    $user = $security->getUser();
    $userCompteCourant = $user ? $compteCourantRepository->findOneBy(['iduser' => $user->getId()]) : null;

    // Create the form
    $form = $this->createForm(OperationType::class, $operation);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Retrieve data from the form
        $montantopt = $form->get('montantopt')->getData(); // Corrected field name
        
        // Retrieve the CompteCourant entity associated with the current user
        $user = $security->getUser();
        $userCompteCourant = $user ? $compteCourantRepository->findOneBy(['iduser' => $user->getId()]) : null;

        // Check if the user has a CompteCourant entity
        if (!$userCompteCourant) {
            throw $this->createNotFoundException('User CompteCourant entity not found.');
        }

        // Get the current balance from the user's CompteCourant entity
        $currentBalance = $userCompteCourant->getMontant();

        // Check if the balance is sufficient for the withdrawal
        if ($currentBalance < $montantopt) {
            // Handle insufficient balance, for example, display an error message
            $this->addFlash('error', 'Insufficient balance.');
            // Redirect back to the form
            return $this->redirectToRoute('app_operation_new');
        }

        // Subtract the withdrawal amount from the current balance
        $newBalance = $currentBalance - $montantopt;

        // Update the balance in the user's CompteCourant entity
        $userCompteCourant->setMontant($newBalance);

        // Persist the changes to the user's CompteCourant entity
        $entityManager->persist($userCompteCourant);
        
        // Set operation details
        $operation->setTypeOperation("retrait");
        // Set the user associated with the operation

        // Persist the Operation entity
        $entityManager->persist($operation);
        $entityManager->flush();

        // Send email notification
        $email = (new Email())
            ->from('oumaima.khamassi@esprit.tn')
            ->to('oumaima.khamassi@esprit.tn')
            ->subject('Operation Notification')
            ->html("<p>The withdrawal operation has been successfully completed!</p>");
        $mailer->send($email);

        // Redirect to the index page or any other appropriate page
        return $this->redirectToRoute('op_front', [], Response::HTTP_SEE_OTHER);
    }

    // Render the form if not submitted or invalid
    return $this->renderForm('operation/new.html.twig', [
        'operation' => $operation,
        'form' => $form,
        'koko' =>$user->getId(),
    ]);
}


    #[Route('/{numoperation}', name: 'app_operation_show', methods: ['GET'])]
    public function show(Operation $operation): Response
    {
        
        return $this->render('operation/show.html.twig', [
            'operation' => $operation,
        ]);
    }

    #[Route('/{numoperation}/edit', name: 'app_operation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Operation $operation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(OperationType::class, $operation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_operation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('operation/edit.html.twig', [
            'operation' => $operation,
            'form' => $form,
        ]);
    }

    #[Route('/{numoperation}', name: 'app_operation_delete', methods: ['POST'])]
    public function delete(Request $request, Operation $operation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$operation->getNumoperation(), $request->request->get('_token'))) {
            $entityManager->remove($operation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_operation_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/new/retrait', name: 'app_operation_new_retrait', methods: ['GET', 'POST'])]
public function newRETRAIT(Request $request, EntityManagerInterface $entityManager,CompteCourantRepository $CompteCourantRepository): Response
{
    // Create a new Operation instance with type "RETRAIT"
    $operation = new Operation();
    $operation->setTypeOperation('RETRAIT'); // Set the type to "RETRAIT"
    
    // Set the default value for dateoperation before creating the form
    $operation->setDateOperation(new \DateTime());

    // Create the form
    $form = $this->createForm(RetraitType::class, $operation);
    $form->handleRequest($request);
    $currentAmount=0;
    if ($form->isSubmitted() && $form->isValid()) {
        // Retrieve data from the form
        $montantopt = $form->get('montantopt')->getData();
        $description = $form->get('description')->getData();

        // Update the balance (montant) in the Compte entity with account number 7
        $compteNumber = 1;
        $compte =  $CompteCourantRepository->find($compteNumber);

        // Check if the Compte entity exists
        if (!$compte) {
            throw $this->createNotFoundException('Compte entity with the provided number not found.');
        }

        // Get the current amount (montant) from the Compte entity
        $currentAmount = $compte->getMontant();

        // Update the amount (montant) in the Compte entity
        $newAmount = $currentAmount - $montantopt;
        $compte->setMontant($newAmount);

        // Persist changes to the Compte entity
        $entityManager->persist($compte);

        // Persist changes to the Operation entity
        $entityManager->persist($operation);
        $entityManager->flush();

        // Redirect to the index page or render a response as needed
    
    }

    // Render the form and pass it to the template
    return $this->render('operation/new_retrait.html.twig', [
        'form' => $form->createView(),
        'mon' => $currentAmount,
    ]);
}

#[Route('/new/international', name: 'app_operation_new_international', methods: ['GET', 'POST'])]
public function newInternational(Request $request, EntityManagerInterface $entityManager, MailerInterface $mailer, Security $security, CompteCourantRepository $compteCourantRepository): Response
{
    // Create a new Operation instance
    $operation = new Operation();

    // Create the form using the InternationalType form type
    $form = $this->createForm(InternationalType::class, $operation);

    // Handle the form submission
    $form->handleRequest($request);
    if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
        // Redirect to the login page
        return $this->redirectToRoute('app_login');
    }
    // Check if the form is submitted and valid
    if ($form->isSubmitted() && $form->isValid()) {
        // Retrieve data from the form
        $ref = $form->get('ref')->getData();
        $montantopt = $form->get('montantopt')->getData();
        $recipientAccountNumber = $form->get('send')->getData(); // Get the recipient account number from the form

        // Find the VirementInternational entity by its reference
        $virementInternational = $entityManager->getRepository(VirementInternational::class)->find($ref);

        // Check if the VirementInternational entity exists
        if (!$virementInternational) {
            throw $this->createNotFoundException('VirementInternational entity with the provided reference not found.');
        }

        // Calculate the converted amount using the exchange rate
        $convertedAmount = $montantopt / $virementInternational->getTauxEchange();

        // Update the balance (montant) in the sender's account
        $user = $security->getUser();
        $userCompteCourant = $user ? $compteCourantRepository->findOneBy(['iduser' => $user->getId()]) : null;
        $senderAccount = $entityManager->getRepository(CompteCourant::class)->find($userCompteCourant);

        // Check if the sender's account exists
        if (!$senderAccount) {
            throw $this->createNotFoundException('Sender account with the provided number not found.');
        }

        // Retrieve old and new amounts
        $oldAmount = $senderAccount->getMontant();
        $newAmount = $oldAmount - $convertedAmount;

        // Update the sender's account balance
        $senderAccount->setMontant($newAmount);

        // Find the recipient's account by its account number
        $recipientAccount = $entityManager->getRepository(CompteCourant::class)->find($recipientAccountNumber);

        // Check if the recipient's account exists
        if (!$recipientAccount) {
            throw $this->createNotFoundException('Recipient account with the provided number not found.');
        }

        // Update the recipient's account balance
        $recipientAccount->setMontant($recipientAccount->getMontant() + $convertedAmount);

        // Set operation details
        $operation->setFrom($senderAccount->getId());
        $operation->setTypeOperation('INTERNATIONAL');
        $operation->setStatusOperation(true);
        $operation->setMontantOpt($montantopt);
        $operation->setRef($virementInternational);

        // Persist changes to the entities
        $entityManager->persist($senderAccount);
        $entityManager->persist($recipientAccount);
        $entityManager->persist($operation);
        $entityManager->flush();

        // Construct the email message
        $emailContent = "Old Amount in Sender's Account: $oldAmount <br>";
        $emailContent .= "Converted Amount using Exchange Rate: $convertedAmount <br>";
        $emailContent .= "New Amount in Sender's Account: $newAmount <br>";

        // Send the email
        $email = (new Email())
        ->from('oumaima.khamassi@esprit.tn')
        ->to('oumaima.khamassi@esprit.tn')
            ->subject('Transaction Details')
            ->html($emailContent);

        $mailer->send($email);

        // Redirect to the index page or render a response as needed
        return $this->redirectToRoute('op_front');
    }

    // Render the form and pass it to the template
    return $this->render('operation/international.html.twig', [
        'form' => $form->createView(),
    ]);
}


#[Route('/new/virement', name: 'app_virement_new', methods: ['GET', 'POST'])]
public function newVirement(Request $request, EntityManagerInterface $entityManager, MailerInterface $mailer, Security $security, CompteCourantRepository $compteCourantRepository): Response
{
    if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
        // Redirect to the login page
        return $this->redirectToRoute('app_login');
    }
    $operation = new Operation();
    $operation->setStatusOperation(true);
    $operation->setStatusOperation(7);
    $form = $this->createForm(VirementType::class, $operation);
    $form->handleRequest($request);
    $operation->setTypeOperation('virement'); 
    if ($form->isSubmitted() && $form->isValid()) {
        $data = $form->getData();
        $amount = $data->getMontantopt();
        $description = $data->getDescription();
        $id = $data->getSend();

        $user = $security->getUser();
        $userCompteCourant = $user ? $compteCourantRepository->findOneBy(['iduser' => $user->getId()]) : null;

        // Check if the user has a CompteCourant entity
        if (!$userCompteCourant) {
            throw $this->createNotFoundException('User CompteCourant entity not found.');
        }

        // Find sender and receiver accounts
        $senderCompte = $userCompteCourant;
        $receiverCompte = $entityManager->getRepository(CompteCourant::class)->find($id);

        // Check if sender and receiver accounts exist
        if (!$senderCompte || !$receiverCompte) {
            // Handle the case where one or both accounts do not exist
            // You can redirect to an error page or render a specific template
            return $this->render('error.html.twig', [
                'error' => 'One or both accounts do not exist.'
            ]);
        }

        // Check if the sender account has sufficient balance
        if ($senderCompte->getMontant() < $amount) {
            // Handle the case where the sender account does not have sufficient balance
            // You can redirect to an error page or render a specific template
            return $this->render('error.html.twig', [
                'error' => 'Insufficient balance in the sender account.'
            ]);
        }

        // Update account balances
        $senderCompte->setMontant($senderCompte->getMontant() - $amount);
        $receiverCompte->setMontant($receiverCompte->getMontant() + $amount);

        // Persist changes
        $entityManager->persist($operation);
        $entityManager->flush();

        // Send email notification
        $email = (new Email())
        ->from('oumaima.khamassi@esprit.tn')
        ->to('oumaima.khamassi@esprit.tn')
            ->subject('Virement Notification')
            ->html("<p>A transfer of $amount has been successfully made from your account.</p>");

        $mailer->send($email);

        // Redirect or return response as needed
        return $this->redirectToRoute('op_front');
    }

    // Render the form if not submitted or invalid
    return $this->render('operation/virement.html.twig', [
        'form' => $form->createView(),
    ]);
}
}
