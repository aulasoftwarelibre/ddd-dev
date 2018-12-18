<?= "<?php\n" ?>

declare(strict_types=1);

namespace <?= $namespace; ?>;

use <?= $not_found_exception_full_class_name ?>;
use <?= $entity_full_class_name ?>;

interface <?= $class_name ?><?= "\n" ?>
{
    /**
     * @param <?= $entity_class_name ?> $<?= $entity_var_singular ?><?= "\n" ?>
     */
    public function add(<?= $entity_class_name ?> $<?= $entity_var_singular ?>): void;

    /**
     * @param string $<?= $aggregate_id_var_singular ?><?= "\n" ?>
     */
    public function remove(string $<?= $aggregate_id_var_singular ?>): void;

    /**
     * @param string $<?= $aggregate_id_var_singular ?><?= "\n" ?>
     *
     * @throws <?= $not_found_exception_class_name ?><?= "\n" ?>
     *
     * @return <?= $entity_class_name ?><?= "\n" ?>
     */
    public function get(string $<?= $aggregate_id_var_singular ?>): <?= $entity_class_name ?>;

    /**
     * @param string $<?= $aggregate_id_var_singular ?><?= "\n" ?>
     *
     * @return <?= $entity_class_name ?>|null
     */
    public function ofId(string $<?= $aggregate_id_var_singular ?>): ?<?= $entity_class_name ?>;

    /**
     * @return array
     */
    public function all(): array;
}
