<?php

namespace App\Serializer\Normalizer;

use App\Entity\Unit\UnitInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerAwareInterface;
use Symfony\Component\Serializer\SerializerAwareTrait;

/**
 * ArticleNormalizer
 *
 * @author Magnus Reiß <info@magnus-reiss.de>
 */
class DefaultUnitNormalizer implements NormalizerInterface, SerializerAwareInterface
{
    use SerializerAwareTrait;

    /**
     * @param UnitInterface $object
     * @param string|null   $format
     * @param array         $context
     *
     * @return array
     */
    public function normalize($object, $format = null, array $context = []): array
    {
        $normalized = [
            'id'          => $object->getId(),
            'url'         => $object->getUrl(),
            'domain'      => $object->getDomain(),
            'idle_time'   => $object->getIdleTime(),
            'triggered'   => $this->serializer->normalize($object->getTriggered(), $format, $context),
            'deactivated' => $object->isDeactivated(),
            'created'     => $this->serializer->normalize($object->getCreateDate(), $format, $context),
        ];

        return $normalized;
    }

    /**
     * @param mixed $data
     * @param null  $format
     *
     * @return bool
     */
    public function supportsNormalization($data, $format = null): bool
    {
        return $data instanceof UnitInterface;
    }


}