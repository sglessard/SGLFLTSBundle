<?php

/*
 * This file is part of the SGLFLTSBundle package.
 *
 * (c) Simon Guillem-Lessard <s.g.lessard@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SGL\FLTSBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * FrequentTask
 *
 * @ORM\Table(name="FrequentTask")
 * @ORM\Entity(repositoryClass="SGL\FLTSBundle\Entity\FrequentTaskRepository")
 * @ORM\HasLifecycleCallbacks
 */
class FrequentTask
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     * @Assert\NotBlank(message = "flts.frequenttask.name.not_blank")
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(name="identification", type="string", length=50)
     */
    protected $identification;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    protected $created_at;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime")
     */
    protected $updated_at;

    /**
     * @var integer
     *
     * @ORM\Column(name="rank", type="integer")
     */
    protected $rank;

    /**
    * toString
    *
    * @return string
    */
   public function __toString()
   {
       return $this->name;
   }

    /**
     * fullname
     *
     * @return string
     */
    public function getFullname()
    {
        if ($this->identification)
            return $this->identification.' - '.$this->name;
        else
            return $this->name;
    }

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
     * Set name
     *
     * @param string $name
     * @return FrequentTask
     */
    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set identification
     *
     * @param string $identification
     * @return FrequentTask
     */
    public function setIdentification($identification)
    {
        $this->identification = $identification;
    
        return $this;
    }

    /**
     * Get identification
     *
     * @return string 
     */
    public function getIdentification()
    {
        return $this->identification;
    }

    /**
     * Set created_at
     *
     * @param \DateTime $createdAt
     * @return FrequentTask
     */
    public function setCreatedAt($createdAt)
    {
        $this->created_at = $createdAt;
    
        return $this;
    }

    /**
     * Get created_at
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * Set updated_at
     *
     * @param \DateTime $updatedAt
     * @return FrequentTask
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updated_at = $updatedAt;
    
        return $this;
    }

    /**
     * Get updated_at
     *
     * @return \DateTime 
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    /**
     * @ORM\prePersist
     */
    public function prePersist() {
        $this->created_at = new \DateTime;
        $this->updated_at = new \DateTime;
    }

    /**
     * @ORM\preUpdate
     */
    public function preUpdate() {
        $this->updated_at = new \DateTime;
    }

    /**
     * Set rank
     *
     * @param integer $rank
     * @return FrequentTask
     */
    public function setRank($rank)
    {
        $this->rank = $rank;
    
        return $this;
    }

    /**
     * Get rank
     *
     * @return integer 
     */
    public function getRank()
    {
        return $this->rank;
    }
}