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

class MakeAggregate extends AbstractMaker
{
    public static function getCommandName(): string
    {
        return 'make:ddd:aggregate';
    }

    public function configureCommand(Command $command, InputConfiguration $inputConfig)
    {
        $command
            ->setDescription('Create a new aggregate')
            ->addArgument('aggregate', InputArgument::REQUIRED, sprintf('The class name of the aggregate to create (e.g. <fg=yellow>%s</>)', Str::asClassName(Str::getRandomTerm())))
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

        $aggregateIdClassDetails = $generator->createClassNameDetails(
            $aggregateName.'Id',
            'Domain\\'.$aggregateName.'\\Model\\'
        );

        if (!class_exists($aggregateIdClassDetails->getFullName())) {
            throw new \Exception(sprintf('%s class not found', $aggregateIdClassDetails->getFullName()));
        }

        $aggregateVarSingular = lcfirst(Inflector::singularize($aggregateClassDetails->getShortName()));
        $aggregateIdVarSingular = lcfirst(Inflector::singularize($aggregateIdClassDetails->getShortName()));

        $generator->generateClass(
            $aggregateClassDetails->getFullName(),
            __DIR__.'/../Resources/skeleton/aggregate/Aggregate.tpl.php',
            [
                'aggregate_var_singular' => $aggregateVarSingular,
                'aggregate_id_class_name' => $aggregateIdClassDetails->getShortName(),
                'aggregate_id_var_singular' => $aggregateIdVarSingular,
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
