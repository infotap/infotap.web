<?php

namespace Infotap\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Feeds
 */
class Feeds
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $message;

    /**
     * @var integer
     */
    private $aadharId;

    /**
     * @var boolean
     */
    private $gender;

    /**
     * @var boolean
     */
    private $ageFrom;

    /**
     * @var boolean
     */
    private $ageTo;

    /**
     * @var string
     */
    private $location;

    /**
     * @var string
     */
    private $area;

    /**
     * @var string
     */
    private $city;

    /**
     * @var string
     */
    private $state;

    /**
     * @var integer
     */
    private $pincode;

    /**
     * @var string
     */
    private $latitude;

    /**
     * @var string
     */
    private $longitude;

    /**
     * @var \Infotap\AdminBundle\Entity\Department
     */
    private $dept;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Feeds
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set message
     *
     * @param string $message
     * @return Feeds
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return string 
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set aadharId
     *
     * @param integer $aadharId
     * @return Feeds
     */
    public function setAadharId($aadharId)
    {
        $this->aadharId = $aadharId;

        return $this;
    }

    /**
     * Get aadharId
     *
     * @return integer 
     */
    public function getAadharId()
    {
        return $this->aadharId;
    }

    /**
     * Set gender
     *
     * @param boolean $gender
     * @return Feeds
     */
    public function setGender($gender)
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * Get gender
     *
     * @return boolean 
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * Set ageFrom
     *
     * @param boolean $ageFrom
     * @return Feeds
     */
    public function setAgeFrom($ageFrom)
    {
        $this->ageFrom = $ageFrom;

        return $this;
    }

    /**
     * Get ageFrom
     *
     * @return boolean 
     */
    public function getAgeFrom()
    {
        return $this->ageFrom;
    }

    /**
     * Set ageTo
     *
     * @param boolean $ageTo
     * @return Feeds
     */
    public function setAgeTo($ageTo)
    {
        $this->ageTo = $ageTo;

        return $this;
    }

    /**
     * Get ageTo
     *
     * @return boolean 
     */
    public function getAgeTo()
    {
        return $this->ageTo;
    }

    /**
     * Set location
     *
     * @param string $location
     * @return Feeds
     */
    public function setLocation($location)
    {
        $this->location = $location;

        return $this;
    }

    /**
     * Get location
     *
     * @return string 
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * Set area
     *
     * @param string $area
     * @return Feeds
     */
    public function setArea($area)
    {
        $this->area = $area;

        return $this;
    }

    /**
     * Get area
     *
     * @return string 
     */
    public function getArea()
    {
        return $this->area;
    }

    /**
     * Set city
     *
     * @param string $city
     * @return Feeds
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string 
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set state
     *
     * @param string $state
     * @return Feeds
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state
     *
     * @return string 
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set pincode
     *
     * @param integer $pincode
     * @return Feeds
     */
    public function setPincode($pincode)
    {
        $this->pincode = $pincode;

        return $this;
    }

    /**
     * Get pincode
     *
     * @return integer 
     */
    public function getPincode()
    {
        return $this->pincode;
    }

    /**
     * Set latitude
     *
     * @param string $latitude
     * @return Feeds
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Get latitude
     *
     * @return string 
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set longitude
     *
     * @param string $longitude
     * @return Feeds
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Get longitude
     *
     * @return string 
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Set dept
     *
     * @param \Infotap\AdminBundle\Entity\Department $dept
     * @return Feeds
     */
    public function setDept(\Infotap\AdminBundle\Entity\Department $dept = null)
    {
        $this->dept = $dept;

        return $this;
    }

    /**
     * Get dept
     *
     * @return \Infotap\AdminBundle\Entity\Department 
     */
    public function getDept()
    {
        return $this->dept;
    }
    /**
     * @var \DateTime
     */
    protected $creationTime;
    /**
     * Set creationTime
     *
     * @param \DateTime $creationTime
     * @return Reason
     */
    public function setCreationTime($creationTime)
    {
        $this->creationTime = $creationTime;

        return $this;
    }

    /**
     * Get creationTime
     *
     * @return \DateTime 
     */
    public function getCreationTime()
    {
        return $this->creationTime;
    }
}
