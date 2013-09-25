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
use Doctrine\ORM\EntityRepository;

/**
 * Part
 *
 * @ORM\Table(name="Part")
 * @ORM\Entity(repositoryClass="SGL\FLTSBundle\Entity\PartRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Part
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
     * @ORM\Column(name="po", type="string", length=50, nullable=true)
     */
    protected $po;

    /**
     * @var string
     *
     * @ORM\Column(name="identification", type="string", length=50, nullable=true)
     */
    protected $identification;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    protected $name;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="started_at", type="datetime")
     */
    protected $started_at;

    /**
     * @var float
     *
     * @ORM\Column(name="estimated_hours", type="decimal", precision=5, scale=1, nullable=true)
     */
    protected $estimated_hours;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="closed_at", type="datetime", nullable=true)
     */
    protected $closed_at;

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
    * @ORM\ManyToOne(targetEntity="SGL\FLTSBundle\Entity\Project", inversedBy="parts")
    * @ORM\JoinColumn(name="id_project", referencedColumnName="id", nullable=false)
    */
    protected $project;

    /**
     * @ORM\OneToMany(targetEntity="SGL\FLTSBundle\Entity\Task", mappedBy="part")
     * @ORM\OrderBy({"rank" = "ASC"})
     */
    protected $tasks;

    /**
     * @ORM\OneToMany(targetEntity="SGL\FLTSBundle\Entity\Bill", mappedBy="part")
     * @ORM\OrderBy({"number" = "ASC"})
     */
    protected $bills;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->tasks = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set po
     *
     * @param string $po
     * @return Part
     */
    public function setPo($po)
    {
        $this->po = $po;
    
        return $this;
    }

    /**
     * Get po
     *
     * @return string 
     */
    public function getPo()
    {
        return $this->po;
    }

    /**
     * Set identification
     *
     * @param string $identification
     * @return Part
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
     * @return Part
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
     * Set started_at
     *
     * @param \DateTime $startedAt
     * @return Part
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
     * Set estimated_hours
     *
     * @param float $estimatedHours
     * @return Part
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
     * Get closed
     *
     * @return boolean 
     */
    public function getClosed()
    {
        return $this->closed_at ? true : false;
    }

    /**
     * Set closed_at
     *
     * @param \DateTime $closedAt
     * @return Part
     */
    public function setClosedAt($closedAt)
    {
        $this->closed_at = $closedAt;
    
        return $this;
    }

    /**
     * Get closed_at
     *
     * @return \DateTime 
     */
    public function getClosedAt()
    {
        return $this->closed_at;
    }

    /**
     * Set created_at
     *
     * @param \DateTime $createdAt
     * @return Part
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
     * @return Part
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
     * Set project
     *
     * @param \SGL\FLTSBundle\Entity\Project $project
     * @return Part
     */
    public function setProject(\SGL\FLTSBundle\Entity\Project $project = null)
    {
        $this->project = $project;
    
        return $this;
    }

    /**
     * Get project
     *
     * @return \SGL\FLTSBundle\Entity\Project 
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * Add tasks
     *
     * @param \SGL\FLTSBundle\Entity\Task $tasks
     * @return Part
     */
    public function addTask(\SGL\FLTSBundle\Entity\Task $tasks)
    {
        $this->tasks[] = $tasks;
    
        return $this;
    }

    /**
     * Remove tasks
     *
     * @param \SGL\FLTSBundle\Entity\Task $tasks
     */
    public function removeTask(\SGL\FLTSBundle\Entity\Task $tasks)
    {
        $this->tasks->removeElement($tasks);
    }

    /**
     * Get tasks
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTasks()
    {
        return $this->tasks;
    }

    /**
     * Get part's first rank task
     *
     * @return \SGL\FLTSBundle\Entity\Task
     */
    public function getFirstRankTask() {
        return $this->getTasks()->first();
    }

    /**
     * Get client name
     *
     * @return string
     */
    public function getClientName()
    {
        if (null === $this->getProject()) {
            return 'No project';
        }
        if (null === $this->getProject()->getClient()) {
            return "No client";
        }
        return $this->getProject()->getClient()->getName();
    }

    /**
     * Get First Work
     * (Not true: Task works have to be order by workedAt DESC)
     *
     * @return \SGL\FLTSBundle\Entity\Work
     */
    public function getFirstWork() {
        $work = null;
        foreach ($this->tasks as $task) {
            if ($task_work = $task->getFirstWork()) {
                $task_work_datetime = new \DateTime($task_work->getWorkedAt()->format('Y-m-d').' '.$task_work->getStartedAt()->format('H:i:s'));
                if ($work) {
                    $work_datetime = new \DateTime($work->getWorkedAt()->format('Y-m-d').' '.$work->getStartedAt()->format('H:i:s'));
                    if ($task_work_datetime->getTimestamp() < $work_datetime->getTimestamp()) {
                        $work = $task_work;
                    }
                } else {
                    $work = $task_work;
                }
            }
        }

        return $work;
    }

    /**
     * Get last Work
     * (Not true: Task works have to be order by workedAt DESC)
     *
     * @return \SGL\FLTSBundle\Entity\Work
     */
    public function getLastWork() {
        $work = null;
        foreach ($this->tasks as $task) {
            if ($task_work = $task->getLastWork()) {
                $task_work_datetime = new \DateTime($task_work->getWorkedAt()->format('Y-m-d').' '.$task_work->getStartedAt()->format('H:i:s'));
                if ($work) {
                    $work_datetime = new \DateTime($work->getWorkedAt()->format('Y-m-d').' '.$work->getStartedAt()->format('H:i:s'));
                    if ($task_work_datetime->getTimestamp() > $work_datetime->getTimestamp()) {
                        $work = $task_work;
                    }
                } else {
                    $work = $task_work;
                }
            }
        }

        return $work;
    }

    /**
     * Get duration since last job (in seconds)
     *
     * @return integer
     */
    public function getDurationSinceLastJob() {
        if ($last_work = $this->getLastWork()) {
            $now = new \DateTime('now');
            return $now->getTimeStamp() - $last_work->getWorkedAt()->getTimestamp();
        } else {
            return 0;
        }
    }

    /**
     * Get duration since creation (in seconds)
     *
     * @return integer
     */
    public function getDurationSinceCreation() {
        $now = new \DateTime('now');
        return $now->getTimeStamp() - $this->getStartedAt()->getTimeStamp();
    }

    /**
     * Get estimated time (in hours)
     *
     * @return float
     */
    public function getEstimation() {
        if ($this->getEstimatedHours()) {
            return $this->getEstimatedHours();
        } else {
            $hours = 0;
            foreach ($this->getTasks() as $task) {
                $hours += $task->getEstimatedHours();
            }
            return $hours;
        }
    }

    /**
     * Get worked time (in hours)
     *
     * @return float
     */
    public function getWorkedHours()
    {
        $hours = 0;
        foreach ($this->getTasks() as $task) {
            $hours += $task->getWorkedHours();
        }

        return $hours;
    }

    /**
     * Get workedPourcentage
     *
     * @return integer
     */
    public function getWorkedPourcentage() {
        if ($this->getEstimation() > 0) {
            return round(($this->getWorkedHours() / $this->getEstimation()) * 100,2);
        } else {
            return 0;
        }
    }

    /**
     * Get billedPourcentage
     *
     * @return integer
     */
    public function getBilledPourcentage() {
        if ($this->getWorkedHours() == 0)
            return 0;
        return round(($this->getBilledHours() / $this->getWorkedHours()) * 100,2);
    }

    /**
    * Get billed time (in hours)
    *
    * @return float
    */
    public function getBilledHours()
    {
        $hours = 0;
        foreach ($this->getTasks() as $task) {
           $hours += $task->getBilledHours();
        }

        // Add extra hours
        foreach ($this->getBills() as $bill) {
            if ($bill->getExtraHours())
                $hours += $bill->getExtraHours();
        }

       return $hours;
    }

    /**
    * Get worked days (in days)
    * We include do_not_bill works (do_not_bill works are not displayed in part report view)
    *
    * @return integer
    */
   public function getDays()
   {
       $first_work = $this->getFirstWork();
       $last_work = $this->getLastWork();

       if (!$first_work || !$last_work) {
           return 0;
       }

       $interval = $last_work->getWorkedAt()->diff($first_work->getWorkedAt());
       return $interval->format('%a');

   }


    /**
     * Add bills
     *
     * @param \SGL\FLTSBundle\Entity\Bill $bills
     * @return Part
     */
    public function addBill(\SGL\FLTSBundle\Entity\Bill $bills)
    {
        $this->bills[] = $bills;
    
        return $this;
    }

    /**
     * Remove bills
     *
     * @param \SGL\FLTSBundle\Entity\Bill $bills
     */
    public function removeBill(\SGL\FLTSBundle\Entity\Bill $bills)
    {
        $this->bills->removeElement($bills);
    }

    /**
     * Get bills
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getBills()
    {
        return $this->bills;
    }
}