<?php

declare(strict_types=1);

/*
 * This file is part of the `ddd-dev` project.
 *
 * (c) Aula de Software Libre de la UCO <aulasoftwarelibre@uco.es>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AulaSoftwareLibre\DDD\DevBundle\Maker;

use Doctrine\Common\Inflector\Inflector;
use Prooph\EventSourcing\AggregateRoot;
use Symfony\Bundle\MakerBundle\ConsoleStyle;
use Symfony\Bundle\MakerBundle\DependencyBuilder;
use Symfony\Bundle\MakerBundle\Generator;
use Symfony\Bundle\MakerBundle\InputConfiguration;
use Symfony\Bundle\MakerBundle\Maker\AbstractMaker;
use Symfony\Bundle\MakerBundle\Str;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;

class MakeEventStore extends AbstractMaker
{
    public static function getCommandName(): string
    {
        return 'make:ddd:event-store';
    }

    public function configureCommand(Command $command, InputConfiguration $inputConfig)
    {
        $command
            ->setDescription('Create a new event-store')
            ->addArgument('aggregate', InputArgument::REQUIRED, sprintf('The class name of the aggregate to create its event store (e.g. <fg=yellow>%s</>)', Str::asClassName(Str::getRandomTerm())))
        ;

        $inputConfig->setArgumentAsNonInteractive('aggregate');
    }

    public function generate(InputInterface $input, ConsoleStyle $io, Generator $generator)
    {
        $aggregateName = $input->getArgument('aggregate');

        $aggregateClassDetails = $generator->createClassNameDetails(
            $aggregateName,
            'Domain\\'.$aggregateName.'\\Model\\'
        );
        $aggregateVarSingular = Inflector::singularize($aggregateClassDetails->getShortName());
        $aggregateVarPlural = Inflector::pluralize($aggregateClassDetails->getShortName());

        if (!class_exists($aggregateClassDetails->getFullName())) {
            throw new \Exception(sprintf('%s class not found', $aggregateClassDetails->getFullName()));
        }

        $notFoundExceptionClassDetails = $generator->createClassNameDetails(
            $aggregateName.'NotFoundException',
            'Application\\'.$aggregateName.'\\Exception'
        );

        if (!class_exists($notFoundExceptionClassDetails->getFullName())) {
            throw new \Exception(sprintf('%s class not found', $notFoundExceptionClassDetails->getFullName()));
        }

        $aggregateIdClassDetails = $generator->createClassNameDetails(
            $aggregateName.'Id',
            'Domain\\'.$aggregateName.'\\Model\\'
        );

        $aggregateIdVarSingular = lcfirst(Inflector::singularize($aggregateIdClassDetails->getShortName()));

        $repositoryClassNameDetails = $generator->createClassNameDetails(
            $aggregateVarPlural,
            'Application\\'.$aggregateName.'\\Repository\\'
        );

        $generator->generateClass(
            $repositoryClassNameDetails->getFullName(),
            __DIR__.'/../Resources/skeleton/event-store/Repository.tpl.php',
            [
                'not_found_exception_class_name' => $notFoundExceptionClassDetails->getShortName(),
                'not_found_exception_full_class_name' => $notFoundExceptionClassDetails->getFullName(),
                'aggregate_class_name' => $aggregateClassDetails->getShortName(),
                'aggregate_full_class_name' => $aggregateClassDetails->getFullName(),
                'aggregate_var_singular' => $aggregateVarSingular,
                'aggregate_var_plural' => $aggregateVarPlural,
                'aggregate_id_class_name' => $aggregateIdClassDetails->getShortName(),
                'aggregate_id_full_class_name' => $aggregateIdClassDetails->getFullName(),
                'aggregate_id_var_singular' => $aggregateIdVarSingular,
            ]
        );

        $eventStoreClassNameDetails = $generator->createClassNameDetails(
            $aggregateVarPlural.'EventStore',
            'Infrastructure\\EventStore\\'
        );

        $generator->generateClass(
            $eventStoreClassNameDetails->getFullName(),
            __DIR__.'/../Resources/skeleton/event-store/EventStore.tpl.php',
            [
                'not_found_exception_class_name' => $notFoundExceptionClassDetails->getShortName(),
                'not_found_exception_full_class_name' => $notFoundExceptionClassDetails->getFullName(),
                'aggregate_class_name' => $aggregateClassDetails->getShortName(),
                'aggregate_full_class_name' => $aggregateClassDetails->getFullName(),
                'aggregate_var_singular' => $aggregateVarSingular,
                'aggregate_var_plural' => $aggregateVarPlural,
                'aggregate_id_class_name' => $aggregateIdClassDetails->getShortName(),
                'aggregate_id_full_class_name' => $aggregateIdClassDetails->getFullName(),
                'aggregate_id_var_singular' => $aggregateIdVarSingular,
                'repository_full_class_name' => $repositoryClassNameDetails->getFullName(),
                'repository_class_name' => $repositoryClassNameDetails->getShortName(),
            ]
        );

        $generator->writeChanges();

        $this->writeSuccessMessage($io);
    }

    public function configureDependencies(DependencyBuilder $dependencies)
    {
        $dependencies->addClassDependency(
            AggregateRoot::class,
            'prooph/event-sourcing'
        );
    }
}
