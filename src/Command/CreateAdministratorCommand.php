<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:create-administrator',
    description: 'Create an administrator',
)]
class CreateAdministratorCommand extends Command
{
    private EntityManagerInterface $entitymanager;

    public function __construct(EntityManagerInterface $entitymanager)
    {
        parent::__construct('app:create-administrator');
        $this->entitymanager = $entitymanager;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('full_name', InputArgument::OPTIONAL, 'Full name')
            ->addArgument('email', InputArgument::OPTIONAL, 'Email')
            ->addArgument('pseudo', InputArgument::OPTIONAL,'Pseudo')
            ->addArgument('password', InputArgument::OPTIONAL, 'Password');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $helper = $this->getHelper('question');
    
        $io = new SymfonyStyle($input, $output);
        $fullName = $input->getArgument('full_name');
        if(!$fullName){
            $question = new Question('Quel est le nom de l\'administrator : ');
            $fullName = $helper->ask($input, $output, $question); //pour recup la reponse de la question
        }

        $email = $input->getArgument('email');
        if(!$email){
            $question = new Question('Quel est l\'email de '. $fullName .':');
            $email = $helper->ask($input, $output, $question); //pour recup la reponse de la question
        }

        $pseudo = $input->getArgument('pseudo');
        if(!$pseudo){
            $question = new Question('Quel est le pseudo de '. $fullName .':');
            $pseudo = $helper->ask($input, $output, $question); //pour recup la reponse de la question
        }

        $plainPassword = $input->getArgument('password');
        if(!$plainPassword){
            $question = new Question('Quel est le mot de passe de '. $fullName .':');
            $plainPassword = $helper->ask($input, $output, $question); //pour recup la reponse de la question
        }

        $user = (new User())->setFullName($fullName)
                           ->setEmail($email)
                           ->setPseudo($pseudo)
                           ->setPassword($plainPassword)
                           ->setRoles(['ROLE_USER', 'ROLE_ADMIN']);

        $this->entitymanager->persist($user);
        $this->entitymanager->flush();


        $io->success('Le nouvel admin a été créé');

        return Command::SUCCESS;
    }
}
