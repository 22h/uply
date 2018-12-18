<?php

namespace App\Scrutinizer;

/**
 * ScrutinizerChain
 *
 * @author Magnus Reiß <info@magnus-reiss.de>
 */
class ScrutinizerChain
{
    /**
     * @var ScrutinizerInterface[]
     */
    private $scrutinizer = [];

    /**
     * @param ScrutinizerInterface $scrutinizer
     */
    public function addScrutinizer(ScrutinizerInterface $scrutinizer): void
    {
        $this->scrutinizer[$scrutinizer->getIdent()] = $scrutinizer;
    }

    /**
     * @param string $identifier
     *
     * @return ScrutinizerInterface
     * @throws \Exception
     */
    public function getScrutinizer(string $identifier): ScrutinizerInterface
    {
        if (array_key_exists($identifier, $this->scrutinizer)) {
            return $this->scrutinizer[$identifier];
        }
        throw new \Exception(
            sprintf('No scrutinizer for ident "%s" found', $identifier)
        );
    }

    /**
     * @return array
     */
    public function getIdentifier(): array
    {
        return array_keys($this->scrutinizer);
    }
}