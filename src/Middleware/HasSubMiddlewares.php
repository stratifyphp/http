<?php
declare(strict_types = 1);

namespace Stratify\Http\Middleware;

/**
 * Middleware that contains middlewares.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
interface HasSubMiddlewares
{
    /**
     * @return Middleware[]
     */
    public function getSubMiddlewares() : array;
}
