<?php

declare(strict_types=1);

namespace Mcx\BasicExample\Command;

use Shopware\Core\Framework\Adapter\Console\ShopwareStyle;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\Uuid\Uuid;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateEventLogCommand extends Command
{
    protected static $defaultName = 'mcx:event-log:create';
    protected EntityRepository $eventRepository;

    public function __construct(EntityRepository $eventRepository, string $name = null)
    {
        parent::__construct($name);
        $this->eventRepository = $eventRepository;
    }

    public function configure(): void
    {
        $this->addArgument('name', InputArgument::REQUIRED, 'name of the new item');
    }


    public function run(InputInterface $input, OutputInterface $output): int
    {
        $id = Uuid::randomHex();
        $name = $input->getArgument('name');
        $this->eventRepository->create([
            [
                'id'   => $id,
                'name' => $name,
            ]
        ], Context::createDefaultContext());
        $io = new ShopwareStyle($input, $output);
        $io->info("created event " . $name);

        return Command::SUCCESS;
    }
}
