<?php

namespace AppBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use AppBundle\Entity\Tag;

use AppBundle\Service\Slugger;

class TagSlugger
{
    private $slugger;

    public function __construct(Slugger $slugger)
    {
        $this->slugger = $slugger;
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        // only act on some "Tag" entity
        if (!$entity instanceof Tag) {
            return;
        }

        $entity->setSlug($this->slugger->slugify($entity->getTitle()));
    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        // only act on some "Tag" entity
        if (!$entity instanceof Tag) {
            return;
        }

        $entity->setSlug($this->slugger->slugify($entity->getTitle()));
    }
}
