<?php

namespace App\Serializer\Normalizer;

use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerAwareInterface;
use Symfony\Component\Serializer\SerializerAwareTrait;

/**
 * DateTimeNormalizer
 *
 * @author Magnus Reiß <info@magnus-reiss.de>
 */
class DateTimeNormalizer implements NormalizerInterface, SerializerAwareInterface
{
    use SerializerAwareTrait;

    const OUTPUT_FORMAT = 'c';

    /**
     * @param \DateTime   $object
     * @param string|null $format
     * @param array       $context
     *
     * @return string
     */
    public function normalize($object, $format = null, array $context = []): string
    {
        $object->setTimezone(new \DateTimeZone(date_default_timezone_get()));

        return $object->format(self::OUTPUT_FORMAT);
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($data, $format = null): bool
    {
        return $data instanceof \DateTime;
    }
}
