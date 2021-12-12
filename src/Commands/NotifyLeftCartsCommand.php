<?php

declare(strict_types=1);

namespace App\Commands;

use App\Managers\EmailManager;
use App\Orm\Entities\CartEntity;
use App\Orm\Repositories\CartsRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class NotifyLeftCartsCommand extends Command
{
    private const NOTIFY_DAYS_AFTER = 7;

    protected static $defaultName = 'app:notifyLeftCarts';

    public function __construct(private CartsRepository $cartsRepository, private EmailManager $emailManager)
    {
        parent::__construct(null);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /** @var CartEntity $cart */
        foreach ($this->cartsRepository->findLeftCarts(self::NOTIFY_DAYS_AFTER) as $cart) {
            $clientEmail = $cart->getCustomer()->getEmail();
            $output->writeln("Sending email to {$clientEmail}..");
            $this->emailManager->sendEmail($clientEmail, "EMAIL SUBJECT", "EMAIL TEXT");
        }

        $output->writeln('ALL DONE');
        return 0;
    }
}
