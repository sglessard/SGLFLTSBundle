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
 * Project
 *
 * @ORM\Table(name="Project")
 * @ORM\Entity(repositoryClass="SGL\FLTSBundle\Entity\ProjectRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Project
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
     * @ORM\Column(name="identification", type="string", length=255)
     * @Assert\NotBlank(message = "flts.project.identification.not_blank")
     */
    protected $identification;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     * @Assert\NotBlank(message = "flts.project.name.not_blank")
     */
    protected $name;

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
     * @ORM\ManyToOne(targetEntity="SGL\FLTSBundle\Entity\Client", inversedBy="projects")
     * @ORM\JoinColumn(name="id_client", referencedColumnName="id", nullable=false)
     */
    protected $client;

    /**
     * @ORM\OneToMany(targetEntity="SGL\FLTSBundle\Entity\Part", mappedBy="project")
     * @ORM\OrderBy({"identification" = "ASC", "name" = "ASC"})
     */
    protected $parts;


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
     * Set identification
     *
     * @param string $identification
     * @return Project
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
     * Set name
     *
     * @param string $name
     * @return Project
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
     * Set created_at
     *
     * @param \DateTime $createdAt
     * @return Project
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
     * @return Project
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
     * Set client
     *
     * @param \SGL\FLTSBundle\Entity\Client $client
     * @return Project
     */
    public function setClient(\SGL\FLTSBundle\Entity\Client $client = null)
    {
        $this->client = $client;
    
        return $this;
    }

    /**
     * Get client
     *
     * @return \SGL\FLTSBundle\Entity\Client 
     */
    public function getClient()
    {
        return $this->client;
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
     * Constructor
     */
    public function __construct()
    {
        $this->parts = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add parts
     *
     * @param \SGL\FLTSBundle\Entity\Part $parts
     * @return Project
     */
    public function addPart(\SGL\FLTSBundle\Entity\Part $parts)
    {
        $this->parts[] = $parts;
    
        return $this;
    }

    /**
     * Remove parts
     *
     * @param \SGL\FLTSBundle\Entity\Part $parts
     */
    public function removePart(\SGL\FLTSBundle\Entity\Part $parts)
    {
        $this->parts->removeElement($parts);
    }

    /**
     * Get parts
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getParts()
    {
        return $this->parts;
    }

    /**
     * Has part(s)
     *
     * @return bool
     */
    public function hasParts()
    {
        return $this->parts->count() > 0;
    }

    /**
     * Get closed
     *
     * @return boolean
     */
    public function getClosed()
    {
        $parts = $this->getParts();
        foreach ($this->getParts() as $part) {
            if (!$part->getClosed())
                return false;
        }

        return $parts->count() > 0 ? true : false;
    }
}