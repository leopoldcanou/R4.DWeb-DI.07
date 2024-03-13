<?php

namespace App\Command;

use App\Service\DatabaseInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Entity\Lego;
use Doctrine\ORM\EntityManagerInterface;

#[AsCommand(
    name: 'app:populate-database',
    description: 'Add a short description for your command',
)]
class PopulateDatabaseCommand extends Command
{

    private $entityManager;

    
    
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->entityManager = $entityManager;

    }
    protected function configure(): void
    {
        $this
            ->addArgument('jsonFile', InputArgument::OPTIONAL, 'Path to json file content')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $jsonFilePath = $input->getArgument('jsonFile');
    
        if (!file_exists($jsonFilePath)) {
            $io->error('The file does not exist');
            return Command::FAILURE;
            
        }
    
     $jsonData = file_get_contents($jsonFilePath);
     $legoData = json_decode($jsonData, true);
     if(!$legoData) {
         $io->error('The file does not contain valid JSON');
         return Command::FAILURE;
        }
    
        foreach ($legoData as $item) {
            $lego = new Lego();
            $lego->setName($item['name']);
            $lego->setDescription($item['description']);
            $lego->setPrice($item['price']);
            $lego->setPieces($item['pieces']);
            $lego->setBoxImage($item['images']['box']);
            $lego->setLegoImage($item['images']['bg']);
            
            $this->entityManager->persist($lego);
        }
    
        $this->entityManager->flush();
    
        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');
    
        return Command::SUCCESS;
    }
}
