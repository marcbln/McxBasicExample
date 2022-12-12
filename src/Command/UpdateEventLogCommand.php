<?php

declare(strict_types=1);

namespace Mcx\BasicExample\Command;

use Mcx\BasicExample\Core\Content\EventLog\EventLogEntity;
use Shopware\Core\Framework\Adapter\Console\ShopwareStyle;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use WhyooOs\Util\UtilDebug;

class UpdateEventLogCommand extends Command
{
    protected static $defaultName = 'mcx:event-log:update';
    protected EntityRepository $eventRepository;

    public function __construct(EntityRepository $eventRepository, string $name = null)
    {
        parent::__construct($name);
        $this->eventRepository = $eventRepository;
    }

    public function configure(): void
    {
        $this->addArgument('id', InputArgument::REQUIRED, 'id of the item to be updated');
        $this->addArgument('name', InputArgument::REQUIRED, 'new name of the new item');
    }

    public function run(InputInterface $input, OutputInterface $output): int
    {
        $io = new ShopwareStyle($input, $output);

        $id = $input->getArgument('id');
        $name = $input->getArgument('name');

        $searchResult = $this->eventRepository->search(new Criteria([$id]), Context::createDefaultContext());
        if ($searchResult->getTotal() == 0) {
            $io->error("item not found");
            return Command::FAILURE;
        }

//        /** @var EventEntity $item (NOT NEEDED / USED)*/
//        $item = $searchResult->first();
//        $item->setName($name);

        $x = $this->eventRepository->update([
            [
                'id'   => $id,
                'name' => $name
            ]
        ], Context::createDefaultContext());

        if (!empty($x->getErrors())) {
            $io->error("There were errors");
            return Command::FAILURE;
        }

        $io->info("updated event " . $id);

        return Command::SUCCESS;
    }
}
