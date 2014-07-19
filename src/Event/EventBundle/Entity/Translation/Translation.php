<?php

namespace Event\EventBundle\Entity\Translation;

trait Translation
{
    /**
     * Get translations
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTranslations()
    {
        return $this->translations;
    }

    /**
     * Add translation
     *
     * @param $translation
     */
    public function addTranslation($translation, $entity)
    {
        $this->translations[] = $translation;

        $translation->{'set' . $entity}($this);
    }

    /**
     * Remove translation
     *
     * @param $translation
     */
    public function removeTranslation($translation)
    {
        $this->translations->removeElement($translation);
    }
}
