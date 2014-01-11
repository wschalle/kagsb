<?php

namespace Proxies\__CG__\Entities;

/**
 * THIS CLASS WAS GENERATED BY THE DOCTRINE ORM. DO NOT EDIT THIS FILE.
 */
class Server extends \Entities\Server implements \Doctrine\ORM\Proxy\Proxy
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

    public function getHash()
    {
        $this->__load();
        return parent::getHash();
    }

    public function setHash($value)
    {
        $this->__load();
        return parent::setHash($value);
    }

    public function getFirstSeen()
    {
        $this->__load();
        return parent::getFirstSeen();
    }

    public function setFirstSeen($value)
    {
        $this->__load();
        return parent::setFirstSeen($value);
    }

    public function getLastUpdate()
    {
        $this->__load();
        return parent::getLastUpdate();
    }

    public function setLastUpdate($value)
    {
        $this->__load();
        return parent::setLastUpdate($value);
    }

    public function getIPv4Address()
    {
        $this->__load();
        return parent::getIPv4Address();
    }

    public function setIPv4Address($value)
    {
        $this->__load();
        return parent::setIPv4Address($value);
    }

    public function getIPv6Address()
    {
        $this->__load();
        return parent::getIPv6Address();
    }

    public function setIPv6Address($value)
    {
        $this->__load();
        return parent::setIPv6Address($value);
    }

    public function getPort()
    {
        $this->__load();
        return parent::getPort();
    }

    public function setPort($value)
    {
        $this->__load();
        return parent::setPort($value);
    }

    public function getName()
    {
        $this->__load();
        return parent::getName();
    }

    public function setName($value)
    {
        $this->__load();
        return parent::setName($value);
    }

    public function populate($server)
    {
        $this->__load();
        return parent::populate($server);
    }

    public function update($server)
    {
        $this->__load();
        return parent::update($server);
    }

    public function genHash()
    {
        $this->__load();
        return parent::genHash();
    }

    public function addPlayer($player)
    {
        $this->__load();
        return parent::addPlayer($player);
    }

    public function getGameLink()
    {
        $this->__load();
        return parent::getGameLink();
    }


    public function __sleep()
    {
        return array('__isInitialized__', 'id', 'hash', 'firstSeen', 'lastUpdate', 'IPv4Address', 'IPv6Address', 'Port', 'Name', 'players');
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