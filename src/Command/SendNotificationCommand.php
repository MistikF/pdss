<?php

namespace App\Command;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class SendNotificationCommand extends Command
{
    protected static $defaultName = 'products:send_notification';

    private EntityManagerInterface $entityManager;
    private MailerInterface $mailer;

    public function __construct(EntityManagerInterface $entityManager, MailerInterface $mailer)
    {
        $this->entityManager = $entityManager;
        $this->mailer = $mailer;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Отправляет уведомление о количестве товаров с расширенными данными')
            ->addArgument('email', InputArgument::REQUIRED, 'Email для отправки уведомления');
    }

    /**
     * @throws TransportExceptionInterface
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $email = $input->getArgument('email');

        $productRepository = $this->entityManager->getRepository(Product::class);
        $products = $productRepository->findAll();

        $totalProducts = 0;
        foreach ($products as $product) {
            if ($product->getProductDetail()->first()) {
                $totalProducts++;
            }
        }

        $message = (new Email())
            ->from('you@example.com')
            ->to($email)
            ->subject('Уведомление о количестве товаров с расширенными данными')
            ->text("Всего товаров с расширенными данными: {$totalProducts}");

        $this->mailer->send($message);

        $output->writeln("Total: {$totalProducts}");
        $output->writeln("=========================");

        return Command::SUCCESS;
    }
}
