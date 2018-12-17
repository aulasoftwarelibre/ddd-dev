<?= "<?php\n" ?>

declare(strict_types=1);

namespace <?= $namespace ?>;

use AulaSoftwareLibre\DDD\BaseBundle\Domain\ApplyMethodDispatcherTrait;
use Prooph\EventSourcing\AggregateRoot;

final class <?= $class_name ?> extends AggregateRoot
{
    use ApplyMethodDispatcherTrait;

    /**
     * @var <?= $aggregate_id_class_name ?><?= "\n" ?>
     */
    private $<?= $aggregate_id_var_singular ?>;

    public static function add(): self
    {
        $<?= $aggregate_var_singular ?> = new self();

        return $<?= $aggregate_var_singular ?>;
    }

    public function <?= $aggregate_id_var_singular ?>(): <?= $aggregate_id_class_name ?><?= "\n" ?>
    {
        return $this-><?= $aggregate_id_var_singular ?>;
    }

    public function __toString(): string
    {
    }

    protected function aggregateId(): string
    {
        return $this-><?= $aggregate_var_singular ?>Id->toString();
    }
}
