<?php

namespace App\Service\Discord\WebHook\Embed;

/**
 * Author
 *
 * @author Magnus Reiß <info@magnus-reiss.de>
 */
class Author
{

    /**
     * @var string
     */
    private $name = null;

    /**
     * @var string
     */
    private $url = null;

    /**
     * @var string
     */
    private $iconUrl = null;

    /**
     * @return string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return Author
     */
    public function setName(string $name): Author
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getUrl(): ?string
    {
        return $this->url;
    }

    /**
     * @param string $url
     *
     * @return Author
     */
    public function setUrl(string $url): Author
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return string
     */
    public function getIconUrl(): ?string
    {
        return $this->iconUrl;
    }

    /**
     * @param string $iconUrl
     *
     * @return Author
     */
    public function setIconUrl(string $iconUrl): Author
    {
        $this->iconUrl = $iconUrl;

        return $this;
    }

    /**
     * @return bool
     */
    public function hasContent(): bool
    {
        return (
            !is_null($this->getUrl())
            || !is_null($this->getName())
            || !is_null($this->getIconUrl())
        );
    }

    /**
     * @return array
     */
    public function returnArray(): array
    {
        $data = [];

        if (!is_null($this->getUrl())) {
            $data['url'] = $this->getUrl();
        }

        if (!is_null($this->getIconUrl())) {
            $data['icon_url'] = $this->getIconUrl();
        }

        if (!is_null($this->getName())) {
            $data['name'] = $this->getName();
        }

        return $data;
    }
}