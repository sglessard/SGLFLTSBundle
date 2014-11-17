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
 * Work
 *
 * @ORM\Table(name="Work")
 * @ORM\Entity(repositoryClass="SGL\FLTSBundle\Entity\WorkRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Work
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
     * @Assert\NotBlank(message = "flts.work.name.not_blank")
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    protected $description;

    /**
     * @var float
     *
     * @ORM\Column(name="hours", type="decimal", precision=5, scale=1)
     */
    protected $hours;

    /**
     * @var integer
     *
     * @ORM\Column(name="revision", type="integer", nullable=true)
     */
    protected $revision;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="worked_at", type="date")
     * @Assert\NotBlank(message = "flts.work.worked_at.not_blank")
     */
    protected $worked_at;

    /**
     * @var boolean
     *
     * @ORM\Column(name="do_not_bill", type="boolean", nullable=true)
     */
    protected $do_not_bill;

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
     * @var \DateTime
     *
     * @ORM\Column(name="started_at", type="time")
     * @Assert\NotBlank(message = "flts.work.started_at.not_blank")
     */
    protected $started_at;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="ended_at", type="time")
     * @Assert\NotBlank(message = "flts.work.ended_at.not_blank")
     */
    protected $ended_at;

    /**
     * @ORM\ManyToOne(targetEntity="SGL\FLTSBundle\Entity\Task", inversedBy="works")
     * @ORM\JoinColumn(name="id_task", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     * @Assert\NotNull(message = "flts.work.task.not_null")
     */
    protected $task;

    /**
     * @ORM\ManyToOne(targetEntity="SGL\FLTSBundle\Entity\User", inversedBy="works")
     * @ORM\JoinColumn(name="id_user", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    protected $user;

    /**
     * @ORM\ManyToOne(targetEntity="SGL\FLTSBundle\Entity\Rate")
     * @ORM\JoinColumn(name="id_rate", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    protected $rate;

    /**
     * @ORM\ManyToOne(targetEntity="SGL\FLTSBundle\Entity\Bill", inversedBy="works")
     * @ORM\JoinColumn(name="id_bill", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    protected $bill;


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
     * @return Work
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
     * Set description
     *
     * @param string $description
     * @return Work
     */
    public function setDescription($description)
    {
        $this->description = $description;
    
        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set hours
     *
     * @param float $hours
     * @return Work
     */
    public function setHours($hours)
    {
        $this->hours = $hours;
    
        return $this;
    }

    /**
     * Get hours
     *
     * @return float 
     */
    public function getHours()
    {
        return $this->hours;
    }

    /**
     * Set revision
     *
     * @param integer $revision
     * @return Work
     */
    public function setRevision($revision)
    {
        $this->revision = $revision;
    
        return $this;
    }

    /**
     * Get revision
     *
     * @return integer 
     */
    public function getRevision()
    {
        return $this->revision;
    }

    /**
     * Get billed
     *
     * @return boolean 
     */
    public function getBilled()
    {
        return $this->bill ? true : false;
    }

    /**
     * Get sent
     *
     * @return boolean
     */
    public function getSent()
    {
        if ($this->bill) {
            if ($this->bill->getSentAt())
                return true;
        }
        return false;
    }

    /**
     * Get paid
     *
     * @return boolean 
     */
    public function getPaid()
    {
        if ($this->bill) {
            if ($this->bill->getPaidAt())
                return true;
        }
        return false;
    }

    /**
     * Set do_not_bill
     *
     * @param boolean $doNotBill
     * @return Work
     */
    public function setDoNotBill($doNotBill)
    {
        $this->do_not_bill = $doNotBill;
    
        return $this;
    }

    /**
     * Get do_not_bill
     *
     * @return boolean 
     */
    public function getDoNotBill()
    {
        return $this->do_not_bill;
    }

    /**
     * Set created_at
     *
     * @param \DateTime $createdAt
     * @return Work
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
     * @return Work
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
     * Set worked_at
     *
     * @param \DateTime $workedAt
     * @return Work
     */
    public function setWorkedAt($workedAt)
    {
        $this->worked_at = $workedAt;
    
        return $this;
    }

    /**
     * Get worked_at
     *
     * @return \DateTime 
     */
    public function getWorkedAt()
    {
        return $this->worked_at;
    }

    /**
     * Set started_at
     *
     * @param \DateTime $startedAt
     * @return Work
     */
    public function setStartedAt($startedAt)
    {
        $this->started_at = $startedAt;
    
        return $this;
    }

    /**
     * Get started_at
     *
     * @return \DateTime 
     */
    public function getStartedAt()
    {
        return $this->started_at;
    }

    /**
     * Set ended_at
     *
     * @param \DateTime $endedAt
     * @return Work
     */
    public function setEndedAt($endedAt)
    {
        $this->ended_at = $endedAt;
    
        return $this;
    }

    /**
     * Get ended_at
     *
     * @return \DateTime 
     */
    public function getEndedAt()
    {
        return $this->ended_at;
    }

    /**
     * @ORM\PrePersist
     */
    public function prePersist() {
        $this->created_at = new \DateTime;
        $this->updated_at = new \DateTime;

        // Set hours
        $this->hours = $this->getDuration();
    }

    /**
     * @ORM\PreUpdate
     */
    public function preUpdate() {
        $this->updated_at = new \DateTime;

        // Set hours
        $this->hours = $this->getDuration();
    }

    /**
     * Set task
     *
     * @param \SGL\FLTSBundle\Entity\Task $task
     * @return Work
     */
    public function setTask(\SGL\FLTSBundle\Entity\Task $task)
    {
        $this->task = $task;
    
        return $this;
    }

    /**
     * Get task
     *
     * @return \SGL\FLTSBundle\Entity\Task 
     */
    public function getTask()
    {
        return $this->task;
    }

    /**
     * Set user
     *
     * @param \SGL\FLTSBundle\Entity\User $user
     * @return Work
     */
    public function setUser(\SGL\FLTSBundle\Entity\User $user)
    {
        $this->user = $user;
    
        return $this;
    }

    /**
     * Get user
     *
     * @return \SGL\FLTSBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }


    /**
     * Set rate
     *
     * @param \SGL\FLTSBundle\Entity\Rate $rate
     * @return Work
     */
    public function setRate(\SGL\FLTSBundle\Entity\Rate $rate)
    {
        $this->rate = $rate;
    
        return $this;
    }

    /**
     * Get rate
     *
     * @return \SGL\FLTSBundle\Entity\Rate 
     */
    public function getRate()
    {
        return $this->rate;
    }

    /**
     * get Duration (in hours) between work's start-end time
     * @param void
     * @return float
     */
    public function getDuration() {

        // Diff in seconds
        $interval = $this->getEndedAt()->getTimestamp() - $this->getStartedAt()->getTimestamp();

        // If value is negative, ended_at is the day after
        // Add a day (in seconds)
        if ($interval < 0)
            $interval += (24*60*60);

        // Returns hours
        return $interval / (60*60);
    }

    /**
     * get is morning
     * @param void
     * @return boolean
     */
    public function getIsMorning() {
        return intval($this->getStartedAt()->format('H')) <= 12; // 12 : noon
    }

    /**
     * get is afternoon
     * @param void
     * @return boolean
     */
    public function getIsAfterNoon() {
        return intval($this->getEndedAt()->format('H')) > 12; // 12 : noon
    }

    public function canBeMoved() {
        return !$this->getBill() && !$this->getPart()->getClosed();
    }


    /**
     * get is Billed and paid
     * @param void
     * @return boolean
     */
    public function getIsBilledAndPaid() {
        $bill = $this->getBill();
        return $bill ? $bill->getPaid() : false;
    }

    /**
     * Set bill
     *
     * @param \SGL\FLTSBundle\Entity\Bill $bill
     * @return Work
     */
    public function setBill(\SGL\FLTSBundle\Entity\Bill $bill = null)
    {
        $this->bill = $bill;
    
        return $this;
    }

    /**
     * Get bill
     *
     * @return \SGL\FLTSBundle\Entity\Bill 
     */
    public function getBill()
    {
        return $this->bill;
    }

    /**
     * Get part proxy
     *
     * @return \SGL\FLTSBundle\Entity\Part
     */
    public function getPart()
    {
        return $this->getTask()->getPart();
    }
}