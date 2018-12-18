<?php

namespace App\Notification\Service\Discord\WebHook;

/**
 * WebHook
 *
 * @author Magnus Reiß <info@magnus-reiss.de>
 */
class WebHook
{
    /**
     * @var string
     */
    private $content = null;

    /**
     * @var string
     */
    private $username = null;

    /**
     * @var string
     */
    private $avatarUrl = null;

    /**
     * @var array
     */
    private $embeds = [];

    /**
     * @return string
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * @param string $content
     *
     * @return WebHook
     */
    public function setContent(string $content): WebHook
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return string
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * @param string $username
     *
     * @return WebHook
     */
    public function setUsername(string $username): WebHook
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @return string
     */
    public function getAvatarUrl(): ?string
    {
        return $this->avatarUrl;
    }

    /**
     * @param string $avatarUrl
     *
     * @return WebHook
     */
    public function setAvatarUrl(string $avatarUrl): WebHook
    {
        $this->avatarUrl = $avatarUrl;

        return $this;
    }

    /**
     * @return array
     */
    public function getEmbeds(): array
    {
        return $this->embeds;
    }

    /**
     * @param Embed $embed
     *
     * @return WebHook
     */
    public function addEmbed(Embed $embed): WebHook
    {
        $this->embeds[] = $embed;

        return $this;
    }

    /**
     * @return bool
     */
    public function hasContent(): bool
    {
        return (
            !is_null($this->getContent())
            || !is_null($this->getAvatarUrl())
            || !is_null($this->getUsername())
            || count($this->getEmbeds()) > 0
        );
    }

    /**
     * @return array
     */
    public function returnArray(): array
    {
        $data = [];
        if (!is_null($this->getContent())) {
            $data['content'] = $this->getContent();
        }
        if (!is_null($this->getAvatarUrl())) {
            $data['avatar_url'] = $this->getAvatarUrl();
        }
        if (!is_null($this->getUsername())) {
            $data['username'] = $this->getUsername();
        }
        $embeds = [];
        foreach ($this->getEmbeds() as $embed) {
            if ($embed instanceof Embed && $embed->hasContent()) {
                $embeds[] = $embed->returnArray();
            }
        }
        if (count($embeds) > 0) {
            $data['embeds'] = $embeds;
        }

        return $data;
    }
}