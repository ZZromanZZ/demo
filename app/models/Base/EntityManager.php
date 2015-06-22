<?php

/**
 * This file is part of demo application for example of using framework Obo beta 2 version (http://www.obophp.org/)
 * Created under supervision of company as CreatApps (http://www.creatapps.cz/)
 * @link http://www.obophp.org/
 * @author Adam Suba, http://www.adamsuba.cz/
 * @copyright (c) 2011 - 2013 Adam Suba
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 */

namespace Base;

/**
 * Base entity manager class for my improvements
 */
abstract class EntityManager extends \obo\EntityManager {

    /**
     * Universal method for creating and processing data from the form
     * @param \Base\Form $form
     * @return \Base\Entity
     */
    protected static function newEntityFromForm(\Base\Form $form) {

        self::defineAddSubmit($form);

        self::setDefaultsForForm($form);

        self::protectPrimaryItem($form);

        return self::processForm($form);
    }

    /**
     * Universal method for creating and processing data from the form
     * @param \Base\Form $form
     * @param \Base\Entity $entity
     * @return \Base\Entity
     */
    protected static function editEntityFromForm(\Base\Form $form, \Base\Entity $entity = null) {

        self::defineSaveSubmit($form);

        if(!\is_null($entity)){
            self::setDefaultsForForm($form, $entity);
            self::protectPrimaryItem($form, $entity);
        }

        return self::processForm($form);
    }

    /**
     * Universal method to retrieve entities from the form data and store it
     * @param \Base\Form $form
     * @return \Base\Entity
     */
    protected static function saveEntityFromForm(\Base\Form $form) {
         return self::entity($form->values)->save();
    }

    protected static function defineAddSubmit(\Base\Form $form) {
        $form->addSubmit("add", "Insert");
    }

    protected static function defineSaveSubmit(\Base\Form $form) {
        $form->addSubmit("save", "Save");
    }

    protected static function setDefaultsForForm(\Base\Form $form,  \Base\Entity $entity = null) {
        $entity = $entity ? : self::entity(array());
        $form->setDefaults($entity->propertiesAsArray($form->values));
    }

    protected static function processForm(\Base\Form $form) {
        if ($form->isSuccess()) return self::saveEntityFromForm($form);
    }

    protected static function protectPrimaryItem(\Base\Form $form,  \Base\Entity $entity = null) {
        $entity = $entity ? : self::entity(array());
        $form[$primaryPropertyName = $entity->entityInformation()->primaryPropertyName]->setValue($entity->$primaryPropertyName);
    }
}