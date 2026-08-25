<?php

declare(strict_types=1);

namespace AndreaPeverelli\PhxCore;

/**
 * @phpstan-type PropsAttributes array{
 *		id?: string,
 *		class?: array<int, string>,
 *		...<string, string>
 * }
 */
class Props
{
    /** @var PropsAttributes */
    public array $attributes = [];

    /** @param null|PropsAttributes $attributes */
    public function __construct(?array $attributes = null)
    {
        if ($attributes !== null) {
            $this->attributes = $attributes;
        }
    }
}
