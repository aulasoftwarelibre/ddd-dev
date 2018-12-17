<?= "<?php\n"; ?>

declare(strict_types=1);

namespace <?= $namespace; ?>;

use <?= $not_found_exception_full_class_name ?>;
use <?= $repository_full_class_name ?>;
use <?= $aggregate_full_class_name ?>;
use <?= $aggregate_id_full_class_name ?>;
use Prooph\EventSourcing\Aggregate\AggregateRepository;

class <?= $class_name; ?> extends AggregateRepository implements <?= $repository_class_name; ?><?= "\n"; ?>
{
    /**
     * {@inheritdoc}
     */
    public function save(<?= $aggregate_class_name ?> $<?= $aggregate_var_singular ?>): void
    {
        $this->saveAggregateRoot($<?= $aggregate_var_singular; ?>);
    }

    /**
     * {@inheritdoc}
     */
    public function get(<?= $aggregate_id_class_name ?> $<?= $aggregate_id_var_singular ?>): <?= $aggregate_class_name ?><?= "\n" ?>
    {
        $<?= $aggregate_var_singular; ?> = $this->getAggregateRoot($<?= $aggregate_id_var_singular; ?>->toString());

        if (!$<?= $aggregate_var_singular; ?> instanceof <?= $aggregate_class_name; ?>) {
            throw <?= $aggregate_class_name; ?>NotFoundException::with<?= $aggregate_id_class_name ?>($<?= $aggregate_id_var_singular; ?>->toString());
        }

        return $<?= $aggregate_var_singular; ?>;
    }

    /**
     * {@inheritdoc}
     */
    public function find(<?= $aggregate_id_class_name ?> $<?= $aggregate_id_var_singular ?>): ?<?= $aggregate_class_name ?><?= "\n" ?>
    {
        return $this->getAggregateRoot($<?= $aggregate_id_var_singular; ?>->toString());
    }

    /**
     * {@inheritdoc}
     */
    public function nextIdentity(): <?= $aggregate_id_class_name ?><?= "\n" ?>
    {
        return <?= $aggregate_id_class_name; ?>::generate();
    }
}
