<?= "<?php\n" ?>

declare(strict_types=1);

namespace <?= $namespace; ?>;

use AulaSoftwareLibre\DDD\BaseBundle\Domain\ApplyMethodDispatcherTrait;
use AulaSoftwareLibre\DDD\BaseBundle\Handlers\EventHandler;
use <?= $entity_full_class_name ?>;
use <?= $repository_full_class_name ?>;

class <?= $class_name ?> implements EventHandler
{
    use ApplyMethodDispatcherTrait {
        applyMessage as public __invoke;
    }

    /**
     * @var <?= $repository_full_class_name ?><?= "\n" ?>
     */
    private $<?= $repository_var ?>;

    public function __construct(ZoneViews $<?= $repository_var ?>)
    {
        $this-><?= $repository_var ?> = $<?= $repository_var ?>;
    }
}
