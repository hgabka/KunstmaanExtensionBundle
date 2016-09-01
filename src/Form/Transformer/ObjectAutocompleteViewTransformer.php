<?php
/**
 * Created by PhpStorm.
 * User: Gabe
 * Date: 2016.08.17.
 * Time: 11:26
 */

namespace Hgabka\KunstmaanExtensionBundle\Form\Transformer;

use Doctrine\Common\Persistence\ObjectRepository;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\DataTransformerInterface;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

class ObjectAutocompleteViewTransformer implements DataTransformerInterface
{
    /**
     * @var ObjectRepository
     */
    protected $repository;

    /**
     * @var string
     */
    protected $callback;

    public function __construct(ObjectRepository $repository, $callback)
    {
        $this->repository = $repository;
        $this->callback = $callback;
    }

    /**
     * Transforms a collection into an array.
     *
     * @param Collection $collection A collection of entities
     *
     * @return mixed An array of entities
     *
     * @throws TransformationFailedException
     */
    public function transform($collection)
    {
        $result = [
                'items' => [],
                'labels' => [],
        ];

        if (null === $collection) {
            return $result;
        }

        foreach ($collection as $entity)
        {
            $result['items'][] = [
                'label' => is_null($this->callback)
                    ? (string)$entity : $entity->{$this->callback}(),
                'id' => $entity->getId()
            ];
        }

        return $result;
    }

    /**
     * Transforms choice keys into entities.
     *
     * @param mixed $value
     *
     * @return Collection A collection of entities
     */
    public function reverseTransform($value)
    {
        $collection = new ArrayCollection();

        if (empty($value) || empty($value['items'])) {
            return $collection;
        }

        foreach ($value['items'] as $data) {
            $collection->add($this->repository->find($data));
        }

        return $collection;
    }

}