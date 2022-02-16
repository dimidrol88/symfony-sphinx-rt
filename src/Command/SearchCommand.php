<?php

declare(strict_types=1);

namespace App\Command;

use App\Service\Search\SphinxSearchService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class SearchCommand extends Command
{
    private SphinxSearchService $service;

    public function __construct(SphinxSearchService $service)
    {
        parent::__construct();
        $this->service = $service;
    }

    protected function configure(): void
    {
        $this
            ->setName('tag:search')
            ->setDescription('Search data in real time index');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $helper = $this->getHelper('question');

        $phrase = $helper->ask($input, $output, new Question('Phrase: '));

        $result = $this->service->findAll($phrase);

        $output->writeln('<info>Count:</info>' . $result->getTotal());

        foreach ($result->getTags() as $tag) {
            $output->writeln('title: ' . $tag['title'] . ', slug: ' . $tag['slug']);

        }

        $output->writeln('<info>Done!</info>');

        return Command::SUCCESS;
    }
}
