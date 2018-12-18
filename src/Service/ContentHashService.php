<?php

namespace App\Service;

/**
 * ContentHashService
 *
 * @author Magnus Reiß <info@magnus-reiss.de>
 */
class ContentHashService
{

    /**
     * @param string $url
     *
     * @return string
     * @throws \Exception
     */
    public function getContentHashFromUrl(string $url): string
    {
        $content = @file_get_contents($url);
        if ($content === false) {
            throw new \Exception(sprintf('can not connect to %s', $url));
        }

        return md5($content);
    }
}
