<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace MonorepoBuilder202211\Symfony\Component\DependencyInjection\Argument;

/**
 * Represents a closure acting as a service locator.
 *
 * @author Nicolas Grekas <p@tchwork.com>
 */
class ServiceLocatorArgument implements ArgumentInterface
{
    /**
     * @var mixed[]
     */
    private $values;
    /**
     * @var \Symfony\Component\DependencyInjection\Argument\TaggedIteratorArgument|null
     */
    private $taggedIteratorArgument;
    /**
     * @param mixed[]|\Symfony\Component\DependencyInjection\Argument\TaggedIteratorArgument $values
     */
    public function __construct($values = [])
    {
        if ($values instanceof TaggedIteratorArgument) {
            $this->taggedIteratorArgument = $values;
            $values = [];
        }
        $this->setValues($values);
    }
    public function getTaggedIteratorArgument() : ?TaggedIteratorArgument
    {
        return $this->taggedIteratorArgument;
    }
    public function getValues() : array
    {
        return $this->values;
    }
    public function setValues(array $values)
    {
        $this->values = $values;
    }
}
