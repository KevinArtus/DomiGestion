<?php

namespace App\Command;

use App\Entity\Client;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ImportExcelCommand
 * @package App\Command
 */
class ImportExcelCommand extends Command
{

    private $entityManager;

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct();

        $this->entityManager = $em;
    }

    protected function configure()
    {
        $this->setName('excel:import')
            ->setDescription('Importer les données clients depuis un fichier excel')
            ->addArgument('file', InputArgument::REQUIRED, 'Lien vers le fichier')
            ->addArgument('user', InputArgument::REQUIRED, 'Mail de l\'utilisateur pour qui les données doivent être importées')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $file = $input->getArgument('file');
        $email = $input->getArgument('user');

        $inputFileType = \PhpOffice\PhpSpreadsheet\IOFactory::identify($file);
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($file);
        $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $email]);
        foreach ($sheetData as $data) {
            $client = new Client($user);

            if (is_null($data['A'])) {
                $client->setNom('INDETERMINE');

            } else {
                $client->setNom($data['A']);
            }

            if (is_null($data['B'])) {
                $client->setPrenom('INDETERMINE');

            } else {
                $client->setPrenom($data['B']);
            }

            if (is_null($data['C'])) {
                $client->setAdresse('INDETERMINE');

            } else {
                $client->setAdresse($data['C']);
            }

            if (is_null($data['D'])) {
                $client->setCodePostale(00000);

            } else {
                $client->setCodePostale((int)$data['D']);
            }


            if (is_null($data['E'])) {
                $client->setVille('INDETERMINE');

            } else {
                $client->setVille($data['E']);
            }
            $client->setFixe($data['F']);
            $client->setPortable($data['G']);
            $client->setImporter(true);
            $client->setSexe();
            $client->setIsHote(false);

            $this->entityManager->persist($client);
        }

        $this->entityManager->flush();
    }
}
