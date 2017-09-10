<?php
/**
 * Created by PhpStorm.
 * User: marcinurbaniak
 * Date: 10.09.2017
 * Time: 09:36
 */



namespace TaskPlannerBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SendMailCommand extends Command
{
    protected function configure()
    {
        // ...
        $this->setName('app:sendmail-user')
        ->setDescription('Sending e-mail to user.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln([
            'Send Email',
            '============',
            '',
        ]);

        // outputs a message followed by a "\n"
        $output->writeln('Whoa!');

        // outputs a message without adding a "\n" at the end of the line
        $output->write('You are about to ');
        $output->write('create a user.');
    }
}


