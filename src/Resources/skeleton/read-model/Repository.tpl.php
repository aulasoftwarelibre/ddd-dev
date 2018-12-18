<?= "<?php\n" ?>

declare(strict_types=1);

namespace <?= $namespace; ?>;

use <?= $not_found_exception_full_class_name ?>;
use <?= $entity_full_class_name ?>;
use <?= $repository_full_class_name ?>;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class <?= $class_name ?> extends ServiceEntityRepository implements <?= $repository_class_name ?><?= "\n" ?>
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, <?= $entity_class_name ?>::class);
    }

    /**
     * {@inheritdoc}
     */
    public function add(<?= $entity_class_name ?> $<?= $entity_var_singular ?>): void
    {
        $this->_em->persist($<?= $entity_var_singular ?>);
        $this->_em->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function get(string $<?= $aggregate_id_var_singular ?>): <?= $entity_class_name ?><?= "\n" ?>
    {
        $<?= $entity_var_singular ?> = $this->find($<?= $aggregate_id_var_singular ?>);

        if (!$<?= $entity_var_singular ?> instanceof <?= $entity_class_name ?>) {
            throw <?= $not_found_exception_class_name ?>::with<?= $aggregate_id_class_name ?>($<?= $aggregate_id_var_singular ?>);
        }

        return $<?= $entity_var_singular ?>;
    }

    /**
     * {@inheritdoc}
     */
    public function remove(string $<?= $aggregate_id_var_singular ?>): void
    {
        $<?= $entity_var_singular ?> = $this->find($<?= $aggregate_id_var_singular ?>);

        if (!$<?= $entity_var_singular ?> instanceof <?= $entity_class_name ?>) {
            return;
        }

        $this->_em->remove($<?= $entity_var_singular ?>);
        $this->_em->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function ofId(string $<?= $aggregate_id_var_singular ?>): ?<?= $entity_class_name ?><?= "\n" ?>
    {
        return $this->find($<?= $aggregate_id_var_singular ?>);
    }

    /**
     * {@inheritdoc}
     */
    public function all(): array
    {
        return $this->findAll();
    }
}
