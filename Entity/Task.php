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

/**
 * Task
 *
 * @ORM\Table(name="Task")
 * @ORM\Entity(repositoryClass="SGL\FLTSBundle\Entity\TaskRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Task
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
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(name="identification", type="string", length=50)
     */
    protected $identification;

    /**
     * @var float
     *
     * @ORM\Column(name="estimated_hours", type="decimal", precision=5, scale=1, nullable=true)
     */
    protected $estimated_hours;

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
    * @ORM\ManyToOne(targetEntity="SGL\FLTSBundle\Entity\Part", inversedBy="tasks")
    * @ORM\JoinColumn(name="id_part", referencedColumnName="id", nullable=false, onDelete="CASCADE")
    */
    protected $part;

    /**
     * @ORM\OneToMany(targetEntity="SGL\FLTSBundle\Entity\Work", mappedBy="task")
     * @ORM\OrderBy({"worked_at" = "DESC", "started_at": "DESC"})
     */
    protected $works;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->works = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
     * @return Task
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
     * @return Task
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
     * Set estimated_hours
     *
     * @param float $estimatedHours
     * @return Task
     */
    public function setEstimatedHours($estimatedHours)
    {
        $this->estimated_hours = $estimatedHours;
    
        return $this;
    }

    /**
     * Get estimated_hours
     *
     * @return float 
     */
    public function getEstimatedHours()
    {
        return $this->estimated_hours;
    }

    /**
     * Set created_at
     *
     * @param \DateTime $createdAt
     * @return Task
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
     * @return Task
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
     * Set part
     *
     * @param \SGL\FLTSBundle\Entity\Part $part
     * @return Task
     */
    public function setPart(\SGL\FLTSBundle\Entity\Part $part = null)
    {
        $this->part = $part;
    
        return $this;
    }

    /**
     * Get part
     *
     * @return \SGL\FLTSBundle\Entity\Part 
     */
    public function getPart()
    {
        return $this->part;
    }

    /**
     * Set rank
     *
     * @param integer $rank
     * @return Task
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
    
    /**
     * Add works
     *
     * @param \SGL\FLTSBundle\Entity\Work $works
     * @return Task
     */
    public function addWork(\SGL\FLTSBundle\Entity\Work $works)
    {
        $this->works[] = $works;
    
        return $this;
    }

    /**
     * Remove works
     *
     * @param \SGL\FLTSBundle\Entity\Work $works
     */
    public function removeWork(\SGL\FLTSBundle\Entity\Work $works)
    {
        $this->works->removeElement($works);
    }

    /**
     * Get works
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getWorks()
    {
        return $this->works;
    }

    /**
     * Get most recent works
     *
     * @return \SGL\FLTSBundle\Entity\Work
     */
    public function getLastWork()
    {
        return $this->works->last();
    }

    /**
     * Get the first works
     *
     * @return \SGL\FLTSBundle\Entity\Work
     */
    public function getFirstWork()
    {
        return $this->works->first();
    }

    /**
     * Get works to bill
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getWorksToBill()
    {
        return $this->works->filter(function($work) {
            return $work->getDoNotBill() === false;
        });
    }

    /**
     * Get worked time (in hours)
     * Returns all works if no bill is used
     *
     * @param Bill $bill
     * @return float
     */
    public function getWorkedHours(Bill $bill=null)
    {
        $hours = 0;
        foreach ($this->getWorks() as $work) {
            if ($bill == null || ($bill == $work->getBill())) {
                $hours += $work->getHours();
            }
        }

        return $hours;
    }

    /**
     * Get billed time (in hours)
     *
     * @return float
     */
    public function getBilledHours()
    {
        $hours = 0;
        foreach ($this->getWorks() as $work) {
            if ($work->getBilled())
                $hours += $work->getHours();
        }

        return $hours;
    }

    /**
     * Get paid time (in hours)
     *
     * @return float
     */
    public function getPaidHours()
    {
        $hours = 0;
        foreach ($this->getWorks() as $work) {
            if ($work->getPaid())
                $hours += $work->getHours();
        }

        return $hours;
    }
}