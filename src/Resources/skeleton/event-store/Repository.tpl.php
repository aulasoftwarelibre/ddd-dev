<?= "<?php\n" ?>

declare(strict_types=1);

namespace <?= $namespace ?>;

use <?= $not_found_exception_full_class_name ?>;
use <?= $aggregate_full_class_name ?>;
use <?= $aggregate_id_full_class_name ?>;

interface <?= $class_name ?><?= "\n" ?>
{
    /**
     * @param <?= $aggregate_class_name ?> $<?= $aggregate_var_singular ?><?= "\n" ?>
     */
    public function save(<?= $aggregate_class_name ?> $<?= $aggregate_var_singular ?>): void;

    /**
     * @param <?= $aggregate_id_class_name ?> $<?= $aggregate_id_var_singular ?><?= "\n" ?>
     *
     * @throws <?= $not_found_exception_class_name ?><?= "\n" ?>
     *
     * @return <?= $aggregate_class_name ?><?= "\n" ?>
     */
    public function get(<?= $aggregate_id_class_name ?> $<?= $aggregate_id_var_singular ?>): <?= $aggregate_class_name ?>;

    /**
     * @param <?= $aggregate_id_class_name ?> $<?= $aggregate_id_var_singular ?><?= "\n" ?>
     *
     * @return <?= $aggregate_class_name ?>|null
     */
    public function find(<?= $aggregate_id_class_name ?> $<?= $aggregate_id_var_singular ?>): ?<?= $aggregate_class_name ?>;

    /**
     * @return <?= $aggregate_id_class_name ?><?= "\n" ?>
     */
    public function nextIdentity(): <?= $aggregate_id_class_name ?>;
}
