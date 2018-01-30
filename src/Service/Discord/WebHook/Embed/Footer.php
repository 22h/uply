<?php

namespace App\Service\Discord\WebHook\Embed;

/**
 * Footer
 *
 * @author Magnus Reiß <info@magnus-reiss.de>
 */
class Footer
{

    /**
     * @var string
     */
    private $text = null;

    /**
     * @var string
     */
    private $iconUrl = null;

    /**
     * @return string
     */
    public function getText(): ?string
    {
        return $this->text;
    }

    /**
     * @param string $text
     *
     * @return Footer
     */
    public function setText(string $text): Footer
    {
        $this->text = $text;

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
     * @return Footer
     */
    public function setIconUrl(string $iconUrl): Footer
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
            !is_null($this->getText())
            || !is_null($this->getIconUrl())
        );
    }

    /**
     * @return array
     */
    public function returnArray(): array
    {
        $data = [];

        if (!is_null($this->getIconUrl())) {
            $data['icon_url'] = $this->getIconUrl();
        }

        if (!is_null($this->getText())) {
            $data['text'] = $this->getText();
        }

        return $data;
    }
}