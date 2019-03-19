<?php

namespace Event\EventBundle\Entity\Translation;

use Doctrine\Common\Collections\Criteria;

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
    
    public function getTranslated($attribute, $lang){
        $criteria = Criteria::create()->where(Criteria::expr()->contains('locale', $lang . '%'));
        $translations = $this->translations->matching($criteria)->toArray();

        if(isset($translations[0]) && method_exists($translations[0], 'get' . $attribute)) {
            if(!empty($translations[0]->{'get' . $attribute}()))
            return $translations[0]->{'get' . $attribute}();
        }
        if(!method_exists($this, 'get' . $attribute)){
            return "Error! If You see it, please tell us! Thank You!";
        }
        return $this->{'get' . $attribute}();
    }
}
