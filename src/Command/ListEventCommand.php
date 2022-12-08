<?php

declare(strict_types=1);

namespace Mcx\BasicExample\Command;

use Mcx\BasicExample\Core\Content\Event\EventEntity;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ListEventCommand extends Command
{
    protected static $defaultName = 'mcx:event:list';
    protected EntityRepository $eventRepository;

    public function __construct(EntityRepository $eventRepository, string $name = null)
    {
        parent::__construct($name);
        $this->eventRepository = $eventRepository;
    }


    public function run(InputInterface $input, OutputInterface $output): int
    {
        $searchResult = $this->eventRepository->search(new Criteria(), Context::createDefaultContext());
        $table = new Table($output);
        $table->setHeaders(['id', 'name', 'created', 'updated']);
        /** @var EventEntity $row */
        foreach ($searchResult->getEntities() as $row) {
            $table->addRow([
                $row->getId(),
                $row->getName(),
                $row->getCreatedAt()?->format('Y-m-d H:i:s'),
                $row->getUpdatedAt()?->format('Y-m-d H:i:s'),
            ]);
        }
        $table->setFooterTitle($searchResult->getTotal() . " items found");
        $table->render();


        return Command::SUCCESS;
    }
}
