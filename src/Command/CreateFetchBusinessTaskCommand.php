<?php

namespace App\Command;

use App\Entity\Task;
use App\Provider\BusinessTaskProvider;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

#[AsCommand(
    name: 'app:create-fetch-business-task',
    description: 'Add a short description for your command',
)]
class CreateFetchBusinessTaskCommand extends Command
{
    private EntityManagerInterface $entityManager;
    private BusinessTaskProvider $businessTaskProvider;


    public function __construct(EntityManagerInterface $entityManager, BusinessTaskProvider $businessTaskProvider)
    {
        parent::__construct();
        $this->entityManager = $entityManager;

        $this->businessTaskProvider = $businessTaskProvider;
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Import IT Task List')
        ;
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $tasks = $this->businessTaskProvider->fetchBusinessTask();

        if ($tasks) {
            $io->warning('Existent tasks will be removed.');

            // Ask user confirmation before update system
            if (false === $input->getOption('no-interaction')) {
                if (false === $io->confirm('Do you want to continue?', false)) {
                    $io->comment('Command terminated.');
                    return -1;
                }
            }

            foreach($tasks as $task) {
                $this->entityManager->persist(
                    (new Task())
                        ->setName($task->getName())
                        ->setTime($task->getTime())
                        ->setDifficulty($task->getDifficulty())
                );
            }

            $this->entityManager->flush();

            $io->success(count($tasks).' tasks added.');
        } else {
            $io->comment('Tasks not found.');
        }

        return Command::SUCCESS;
    }
}
