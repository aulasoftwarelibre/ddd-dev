<?= "<?php\n" ?>

declare(strict_types=1);

namespace <?= $namespace; ?>;

class <?= $class_name ?><?= "\n" ?>
{
    /**
     * @var string
     */
    private $id;

    public function __construct(string $id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }
}
