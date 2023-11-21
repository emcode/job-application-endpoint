<?php

namespace App\UI\CLI\User;

use App\Persistence\Entity\User;
use App\Persistence\Repository\UserRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:user:create',
    description: 'Creates admin user'
)]
class CreateCommand extends Command
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher,
        private UserRepository $userRepository,
        string $name = null,
    )
    {
        parent::__construct($name);
    }

    protected function configure()
    {
        $this
            ->addArgument(
            'username',
            InputArgument::REQUIRED,
            'Username',
            )
            ->addArgument(
                'password',
                InputArgument::REQUIRED,
                'Password'
            )
        ;
    }


    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        $username = $input->getArgument('username');
        $password = $this->passwordHasher->hashPassword(
            User::createExample(),
            $input->getArgument('password'),
        );
        $this->userRepository->save(new User($username, $password));
        $output->writeln('User created successfully');
        return self::SUCCESS;
    }
}