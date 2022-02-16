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

class UpdateTagCommand extends Command
{
    private TagService $handler;
    private TagRepository $tagRepository;

    public function __construct(TagService $handler, TagRepository $tagRepository)
    {
        parent::__construct();

        $this->handler = $handler;
        $this->tagRepository = $tagRepository;
    }

    protected function configure(): void
    {
        $this
            ->setName('tag:update')
            ->setDescription('Update tag');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $helper = $this->getHelper('question');

        $id = $helper->ask($input, $output, new Question('Id: '));
        if (!$this->tagRepository->find($id)) {
            throw new LogicException('Invalid request.');
        }

        $slug = $helper->ask($input, $output, new Question('New slug: '));
        $title = $helper->ask($input, $output, new Question('New title: '));

        if (!$slug || !$title) {
            throw new LogicException('Invalid request.');
        }

        $this->handler->update((int)$id, $slug, $title);

        $output->writeln('<info>Done!</info>');

        return Command::SUCCESS;
    }
}
