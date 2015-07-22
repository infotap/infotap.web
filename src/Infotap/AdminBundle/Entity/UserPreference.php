<?php

namespace Infotap\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserPreference
 */
class UserPreference
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Infotap\AdminBundle\Entity\Department
     */
    private $dept;

    /**
     * @var \Infotap\AdminBundle\Entity\User
     */
    private $user;


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
     * Set dept
     *
     * @param \Infotap\AdminBundle\Entity\Department $dept
     * @return UserPreference
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
     * Set user
     *
     * @param \Infotap\AdminBundle\Entity\User $user
     * @return UserPreference
     */
    public function setUser(\Infotap\AdminBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Infotap\AdminBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }
}
