<?php

namespace Proxies\__CG__\Entities;

/**
 * THIS CLASS WAS GENERATED BY THE DOCTRINE ORM. DO NOT EDIT THIS FILE.
 */
class PlayerServer extends \Entities\PlayerServer implements \Doctrine\ORM\Proxy\Proxy
{
    private $_entityPersister;
    private $_identifier;
    public $__isInitialized__ = false;
    public function __construct($entityPersister, $identifier)
    {
        $this->_entityPersister = $entityPersister;
        $this->_identifier = $identifier;
    }
    /** @private */
    public function __load()
    {
        if (!$this->__isInitialized__ && $this->_entityPersister) {
            $this->__isInitialized__ = true;

            if (method_exists($this, "__wakeup")) {
                // call this after __isInitialized__to avoid infinite recursion
                // but before loading to emulate what ClassMetadata::newInstance()
                // provides.
                $this->__wakeup();
            }

            if ($this->_entityPersister->load($this->_identifier, $this) === null) {
                throw new \Doctrine\ORM\EntityNotFoundException();
            }
            unset($this->_entityPersister, $this->_identifier);
        }
    }

    /** @private */
    public function __isInitialized()
    {
        return $this->__isInitialized__;
    }

    
    public function prePersistSetCreateDate()
    {
        $this->__load();
        return parent::prePersistSetCreateDate();
    }

    public function preUpdateSetUpdateDate()
    {
        $this->__load();
        return parent::preUpdateSetUpdateDate();
    }

    public function getId()
    {
        if ($this->__isInitialized__ === false) {
            return (int) $this->_identifier["id"];
        }
        $this->__load();
        return parent::getId();
    }

    public function setId($value)
    {
        $this->__load();
        return parent::setId($value);
    }

    public function getCreateDate()
    {
        $this->__load();
        return parent::getCreateDate();
    }

    public function setCreateDate($value)
    {
        $this->__load();
        return parent::setCreateDate($value);
    }

    public function getUpdateDate()
    {
        $this->__load();
        return parent::getUpdateDate();
    }

    public function setUpdateDate($value)
    {
        $this->__load();
        return parent::setUpdateDate($value);
    }

    public function getSamples()
    {
        $this->__load();
        return parent::getSamples();
    }

    public function setSamples($value)
    {
        $this->__load();
        return parent::setSamples($value);
    }

    public function getPlayer()
    {
        $this->__load();
        return parent::getPlayer();
    }

    public function setPlayer($value)
    {
        $this->__load();
        return parent::setPlayer($value);
    }

    public function getServer()
    {
        $this->__load();
        return parent::getServer();
    }

    public function setServer($value)
    {
        $this->__load();
        return parent::setServer($value);
    }

    public function removed($caller)
    {
        $this->__load();
        return parent::removed($caller);
    }


    public function __sleep()
    {
        return array('__isInitialized__', 'id', 'createDate', 'updateDate', 'samples', 'server', 'player');
    }

    public function __clone()
    {
        if (!$this->__isInitialized__ && $this->_entityPersister) {
            $this->__isInitialized__ = true;
            $class = $this->_entityPersister->getClassMetadata();
            $original = $this->_entityPersister->load($this->_identifier);
            if ($original === null) {
                throw new \Doctrine\ORM\EntityNotFoundException();
            }
            foreach ($class->reflFields as $field => $reflProperty) {
                $reflProperty->setValue($this, $reflProperty->getValue($original));
            }
            unset($this->_entityPersister, $this->_identifier);
        }
        
    }
}