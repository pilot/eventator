<?php

namespace Event\EventBundle\Command;

use Event\EventBundle\Entity\Speech;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\ProgressBar;

/**
 * Class SpeechEventCommand
 */
class SpeechEventCommand extends ContainerAwareCommand
{
    const TYPE_WRITE  = 'w',
          TYPE_READ  = 'r';

    /**
     * Configures the current command.
     */
    protected function configure()
    {
        $this
            ->setName('eventator:speech:event_migrate')
            ->setDescription('Migrate speech events from old version to new');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();
        $em = $container->get('doctrine.orm.entity_manager');
        $speechRepository = $em->getRepository('EventEventBundle:Speech');
        $eventRepository = $em->getRepository('EventEventBundle:Event');

        $speeches = $speechRepository->findAll();
        $output->writeln("<info>Starts recording speeches with events to a file</info>");
        $progress = new ProgressBar($output, count($speeches));
        $progress->start();

        /**
         * @var Speech $speech
         */
        foreach ($speeches as $speech) {
            $event = $eventRepository->getEventBySpeechId($speech->getId());

            if ($event) {
                $speech->setEvent($event);
                $em->persist($speech);
                $em->flush();
            }
            $progress->advance();
        }

        $progress->finish();

        $output->writeln("\n<info>Recording speeches with events to a file were finished</info>");
    }
}
