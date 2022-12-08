<?php

declare(strict_types=1);

namespace Mcx\BasicExample\Command;

use Mcx\BasicExample\Core\Content\Event\EventEntity;
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

class DeleteEventCommand extends Command
{
    protected static $defaultName = 'mcx:event:delete';
    protected EntityRepository $eventRepository;

    public function __construct(EntityRepository $eventRepository, string $name = null)
    {
        parent::__construct($name);
        $this->eventRepository = $eventRepository;
    }

    public function configure(): void
    {
        $this->addArgument('id', InputArgument::REQUIRED, 'id of the item to be deleted');
    }

    public function run(InputInterface $input, OutputInterface $output): int
    {
        $io = new ShopwareStyle($input, $output);

        $id = $input->getArgument('id');

        $rows = $this->eventRepository->searchIds((new Criteria())->addFilter(new EqualsFilter('id', $id)), Context::createDefaultContext());
        if($rows->getTotal() == 0) {
            $io->error("item not found");
            return Command::FAILURE;
        }

        $x = $this->eventRepository->delete([['id' => $id]], Context::createDefaultContext());

        if(!empty($x->getErrors())) {
            $io->error("There were errors");
            return Command::FAILURE;
        }

        $io->info("deleted event " . $id);

        return Command::SUCCESS;
    }
}
