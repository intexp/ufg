<?php

namespace AdminBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

/**
 * Class OfficeToIdTransformer
 * @package ApiBundle\Form\DataTransformer
 */
class SlugTransformer implements DataTransformerInterface
{
    /**
     * Transforms a slug to slug material.
     *
     * @param mixed $slug
     * @return int|mixed|void
     * @internal param Office|null $office
     */
    public function transform($slug)
    {
        if (null === $slug) {
            return;
        }

        return $slug;
    }

    /**
     * Transforms string to slug.
     *
     * @param mixed $slugMaterial
     * @return mixed|string|void
     */
    public function reverseTransform($slugMaterial)
    {
        if (!$slugMaterial) {
            return;
        }

        $slug = strtolower(trim($slugMaterial));
        $slug = str_replace(' ', '_', $slug);

        return $slug;
    }
}