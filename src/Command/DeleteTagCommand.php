<?php

declare(strict_types=1);

namespace App\Command;

use App\Repository\TagRepository;
use App\Service\Tag\TagService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\LogicException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class DeleteTagCommand extends Command
{
    private TagService $handler;

    public function __construct(TagService $handler)
    {
        parent::__construct();

        $this->handler = $handler;
    }

    protected function configure(): void
    {
        $this
            ->setName('tag:remove')
            ->setDescription('Remove tag');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $helper = $this->getHelper('question');

        $id = (int)$helper->ask($input, $output, new Question('Id: '));

        if (!$id) {
            throw new LogicException('Invalid request.');
        }

        $this->handler->delete($id);

        $output->writeln('<info>Done!</info>');

        return Command::SUCCESS;
    }
}
