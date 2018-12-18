<?php

namespace App\Notification\Service\Discord\WebHook;

use App\Notification\Service\Discord\WebHook\Embed\Author;
use App\Notification\Service\Discord\WebHook\Embed\Footer;

/**
 * Embed
 *
 * @author Magnus Reiß <info@magnus-reiss.de>
 */
class Embed
{
    /**
     * @var string
     */
    private $title = null;

    /**
     * @var string
     */
    private $description = null;

    /**
     * @var string
     */
    private $url = null;

    /**
     * @var Author
     */
    private $author = null;

    /**
     * @var Footer
     */
    private $footer = null;

    /**
     * @var int
     */
    private $color = null;

    /**
     * @return string
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string $title
     *
     * @return Embed
     */
    public function setTitle(string $title): Embed
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string $description
     *
     * @return Embed
     */
    public function setDescription(string $description): Embed
    {
        $this->description = $description;

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
     * @return Embed
     */
    public function setUrl(string $url): Embed
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return Author
     */
    public function getAuthor(): ?Author
    {
        return $this->author;
    }

    /**
     * @param Author $author
     *
     * @return Embed
     */
    public function setAuthor(Author $author): Embed
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return Footer
     */
    public function getFooter(): ?Footer
    {
        return $this->footer;
    }

    /**
     * @param Footer $footer
     *
     * @return Embed
     */
    public function setFooter(Footer $footer): Embed
    {
        $this->footer = $footer;

        return $this;
    }

    /**
     * @return int
     */
    public function getColor(): ?int
    {
        return $this->color;
    }

    /**
     * @param string $hexColor
     *
     * @return Embed
     * @throws \Exception
     */
    public function setColor(string $hexColor): Embed
    {
        if (strlen($hexColor) != 3 && strlen($hexColor) != 6) {
            throw new \Exception('only hex values with 3 or 6 chars are allowed');
        }
        $this->color = hexdec($hexColor);

        return $this;
    }

    /**
     * @return bool
     */
    public function hasContent(): bool
    {
        return (
            !is_null($this->getTitle())
            || !is_null($this->getDescription())
            || !is_null($this->getUrl())
            || !is_null($this->getColor())
            || ($this->getAuthor() instanceof Author && $this->getAuthor()->hasContent())
            || ($this->getFooter() instanceof Footer && $this->getFooter()->hasContent())
        );
    }

    /**
     * @return array
     */
    public function returnArray(): array
    {
        $data = [];
        if (!is_null($this->getTitle())) {
            $data['title'] = $this->getTitle();
        }
        if (!is_null($this->getDescription())) {
            $data['description'] = $this->getDescription();
        }
        if (!is_null($this->getUrl())) {
            $data['url'] = $this->getUrl();
        }
        if (!is_null($this->getColor())) {
            $data['color'] = $this->getColor();
        }
        if ($this->getAuthor() instanceof Author && $this->getAuthor()->hasContent()) {
            $data['author'] = $this->getAuthor()->returnArray();
        }
        if ($this->getFooter() instanceof Footer && $this->getFooter()->hasContent()) {
            $data['footer'] = $this->getFooter()->returnArray();
        }

        return $data;
    }
}