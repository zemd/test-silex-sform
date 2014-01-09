<?php

namespace Webfilter\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Symfony\Component\Validator\Constraints as Assert;

/** @ODM\Document */
class Organization {
    /** @ODM\Id(strategy="UUID") */
    private $id;

    /** @ODM\Collection */
    private $whitelist = array();

    /** @ODM\Collection */
    private $blacklist = array();

    /** @ODM\Collection */
    private $categories = array();

    /** @ODM\Collection */
    private $apps = array();

    /**
     * @param mixed $apps
     */
    public function setApps($apps)
    {
        $this->apps = $apps;
    }

    /**
     * @param mixed $blacklist
     */
    public function setBlacklist($blacklist)
    {
        $this->blacklist = $blacklist;
    }

    /**
     * @param mixed $categories
     */
    public function setCategories($categories)
    {
        $this->categories = $categories;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @param mixed $whitelist
     */
    public function setWhitelist($whitelist)
    {
        $this->whitelist = $whitelist;
    }

    /**
     * @return mixed
     */
    public function getApps()
    {
        return $this->apps;
    }

    /**
     * @return mixed
     */
    public function getBlacklist()
    {
        return $this->blacklist;
    }

    /**
     * @return mixed
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getWhitelist()
    {
        return $this->whitelist;
    }



}

