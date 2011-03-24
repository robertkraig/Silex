<?php

/*
 * This file is part of the Silex framework.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Silex;

use Silex\Exception\ControllerFrozenException;
use Symfony\Component\Routing\Route;

/**
 * A wrapper for a controller, mapped to a route.
 *
 * @author Igor Wiedler igor@wiedler.ch
 */
class Controller
{
    private $route;
    private $routeName;
    private $isFrozen = false;

    /**
     * Constructor.
     *
     * @param Route $route
     */
    public function __construct(Route $route)
    {
        $this->route = $route;
        $this->setRouteName($this->defaultRouteName());
    }

    /**
     * Get the controller's route.
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * Get the controller's route name.
     */
    public function getRouteName()
    {
        return $this->routeName;
    }

    /**
     * Set the controller's route.
     *
     * @param string $routeName
     */
    public function setRouteName($routeName)
    {
        if ($this->isFrozen) {
            throw new ControllerFrozenException(sprintf('Calling %s on frozen %s instance.', __METHOD__, __CLASS__));
        }

        $this->routeName = $routeName;
    }

    /**
     * Freeze the controller.
     *
     * Once the controller is frozen, you can no longer change the route name
     */
    public function freeze()
    {
        $this->isFrozen = true;
    }

    private function defaultRouteName()
    {
        $requirements = $this->route->getRequirements();
        $method = isset($requirements['_method']) ? $requirements['_method'] : '';

        $routeName = $method.$this->route->getPattern();
        $routeName = str_replace(array('{', '}'), '', $routeName);
        $routeName = str_replace(array('/', ':', '|'), '_', $routeName);

        return $routeName;
    }
}