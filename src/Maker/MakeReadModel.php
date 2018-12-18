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

class MakeReadModel extends AbstractMaker
{
    public static function getCommandName(): string
    {
        return 'make:ddd:read-model';
    }

    public function configureCommand(Command $command, InputConfiguration $inputConfig)
    {
        $command
            ->setDescription('Create a new read-model')
            ->addArgument('aggregate', InputArgument::REQUIRED, sprintf('The class name of the aggregate to create its event store (e.g. <fg=yellow>%s</>)', Str::asClassName(Str::getRandomTerm())))
            ->addArgument('entity', InputArgument::REQUIRED, sprintf('The class name of the entity to create (e.g. <fg=yellow>%sView</>)', Str::asClassName(Str::getRandomTerm())))
        ;

        $inputConfig->setArgumentAsNonInteractive('aggregate');
        $inputConfig->setArgumentAsNonInteractive('entity');
    }

    public function generate(InputInterface $input, ConsoleStyle $io, Generator $generator)
    {
        $entityName = ucfirst($input->getArgument('entity'));
        if (0 !== substr_compare($entityName, 'View', -4)) {
            throw new \Exception(sprintf('Classname suffix should be View: %1$s', $entityName));
        }

        $aggregateName = ucfirst($input->getArgument('aggregate'));
        $entityPluralName = Inflector::pluralize($entityName);

        $aggregateIdClassDetails = $generator->createClassNameDetails(
            $aggregateName.'Id',
            'Domain\\'.$aggregateName.'\\Model\\'
        );

        $notFoundExceptionClassDetails = $generator->createClassNameDetails(
            $aggregateName.'NotFoundException',
            'Application\\'.$aggregateName.'\\Exception'
        );
        if (!class_exists($notFoundExceptionClassDetails->getFullName())) {
            throw new \Exception(sprintf('%s class not found', $notFoundExceptionClassDetails->getFullName()));
        }

        $entityClassDetails = $generator->createClassNameDetails(
            $entityName,
            'Infrastructure\\Entity'
        );

        $repositoryInterfaceClassDetails = $generator->createClassNameDetails(
            $entityPluralName,
            'Infrastructure\\ReadModel\\Repository'
        );

        $repositoryClassDetails = $generator->createClassNameDetails(
            $entityPluralName .'Repository',
            'Infrastructure\\Repository'
        );

        $aggregateIdVarSingular = lcfirst(Inflector::singularize($aggregateIdClassDetails->getShortName()));
        $entityVarSingular = lcfirst(Inflector::singularize($entityClassDetails->getShortName()));

        $generator->generateClass(
            $entityClassDetails->getFullName(),
            __DIR__.'/../Resources/skeleton/read-model/Entity.tpl.php',
            []
        );

        $generator->generateClass(
            $repositoryInterfaceClassDetails->getFullName(),
            __DIR__.'/../Resources/skeleton/read-model/ReadModelRepository.tpl.php',
            [
                'not_found_exception_class_name' => $notFoundExceptionClassDetails->getShortName(),
                'not_found_exception_full_class_name' => $notFoundExceptionClassDetails->getFullName(),
                'entity_full_class_name' => $entityClassDetails->getFullName(),
                'entity_class_name' => $entityClassDetails->getShortName(),
                'entity_var_singular' => $entityVarSingular,
                'aggregate_id_var_singular' => $aggregateIdVarSingular,
            ]
        );

        $generator->generateClass(
            $repositoryClassDetails->getFullName(),
            __DIR__.'/../Resources/skeleton/read-model/Repository.tpl.php',
            [
                'not_found_exception_class_name' => $notFoundExceptionClassDetails->getShortName(),
                'not_found_exception_full_class_name' => $notFoundExceptionClassDetails->getFullName(),
                'entity_full_class_name' => $entityClassDetails->getFullName(),
                'entity_class_name' => $entityClassDetails->getShortName(),
                'repository_full_class_name' => $repositoryInterfaceClassDetails->getFullName(),
                'repository_class_name' => $repositoryInterfaceClassDetails->getShortName(),
                'entity_var_singular' => $entityVarSingular,
                'aggregate_id_var_singular' => $aggregateIdVarSingular,
                'aggregate_id_class_name' => $aggregateIdClassDetails->getShortName(),
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
