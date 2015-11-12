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
 * Bill
 *
 * @ORM\Table(name="Bill")
 * @ORM\Entity(repositoryClass="SGL\FLTSBundle\Entity\BillRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Bill
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
     * @Assert\NotBlank(message = "flts.bill.name.not_blank")
     */
    protected $name;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="billed_at", type="datetime", nullable=true)
     */
    protected $billed_at;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="sent_at", type="datetime", nullable=true)
     */
    protected $sent_at;

    /**
     * @var integer
     *
     * @ORM\Column(name="number", type="integer", unique=true)
     * @Assert\Type(type="integer", message="flts.bill.number.integer")
     * @Assert\GreaterThan(value = 0, message="flts.bill.number.integer")
     * @Assert\NotNull(message="flts.bill.number.not_null")
     */
    protected $number;

    /**
     * @var float
     *
     * @ORM\Column(name="extra_hours", type="decimal", precision=5, scale=1, nullable=true)
     */
    protected $extra_hours;

    /**
     * @var float
     *
     * @ORM\Column(name="extra_fees", type="decimal", precision=5, scale=2, nullable=true)
     */
    protected $extra_fees;

    /**
     * @var boolean
     *
     * @ORM\Column(name="taxable", type="boolean", nullable=true)
     */
    protected $taxable;

    /**
     * @var float
     *
     * @ORM\Column(name="gst", type="decimal", precision=5, scale=3, nullable=true)
     */
    protected $gst;

    /**
     * @var float
     *
     * @ORM\Column(name="pst", type="decimal", precision=5, scale=3, nullable=true)
     */
    protected $pst;

    /**
     * @var float
     *
     * @ORM\Column(name="hst", type="decimal", precision=5, scale=3, nullable=true)
     */
    protected $hst;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    protected $description;

    /**
     * @var string
     *
     * @ORM\Column(name="body_content", type="text", nullable=true)
     */
    protected $body_content;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text", nullable=true)
     */
    protected $content;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="paid_at", type="datetime", nullable=true)
     */
    protected $paid_at;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="deposited_at", type="datetime", nullable=true)
     */
    protected $deposited_at;

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
    * @ORM\ManyToOne(targetEntity="SGL\FLTSBundle\Entity\Part", inversedBy="bills")
    * @ORM\JoinColumn(name="id_part", referencedColumnName="id", nullable=false)
    * @Assert\NotNull(message="flts.bill.part.not_null")
    */
    protected $part;

    /**
    * @ORM\ManyToOne(targetEntity="SGL\FLTSBundle\Entity\Rate")
    * @ORM\JoinColumn(name="id_rate", referencedColumnName="id", nullable=false)
    * @Assert\NotNull(message="flts.bill.rate.not_null")
    */
    protected $rate;

    /**
     * @ORM\OneToMany(targetEntity="SGL\FLTSBundle\Entity\Work", mappedBy="bill")
     * @ORM\OrderBy({"worked_at" = "ASC", "started_at": "ASC"})
     */
    protected $works;

    /**
     * @var string
     *
     * @ORM\Column(name="note", type="text", nullable=true)
     */
    protected $note;

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
     * @return Bill
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
     * Get fullname
     *
     * @return string
     */
    public function getFullname()
    {
        return $this->number. ' - '. $this->name;
    }

    /**
     * Set billed_at
     *
     * @param \DateTime $billedAt
     * @return Bill
     */
    public function setBilledAt($billedAt)
    {
        $this->billed_at = $billedAt;
    
        return $this;
    }

    /**
     * Get billed_at
     *
     * @return \DateTime 
     */
    public function getBilledAt()
    {
        return $this->billed_at;
    }

    /**
     * Set sent_at
     *
     * @param \DateTime $sentAt
     * @return Bill
     */
    public function setSentAt($sentAt)
    {
        $this->sent_at = $sentAt;
    
        return $this;
    }

    /**
     * Get sent_at
     *
     * @return \DateTime 
     */
    public function getSentAt()
    {
        return $this->sent_at;
    }

    /**
     * Get sent
     *
     * @return boolean
     */
    public function getSent()
    {
        return $this->sent_at == null ? false : true;
    }

    /**
     * Set number
     *
     * @param integer $number
     * @return Bill
     */
    public function setNumber($number)
    {
        $this->number = $number;
    
        return $this;
    }

    /**
     * Get number
     *
     * @return integer 
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Get number padded
     *
     * @return integer
     */
    public function getNumberPadded()
    {
        return str_pad($this->number,6,'0',STR_PAD_LEFT);
    }

    /**
     * Set extra_hours
     *
     * @param float $extraHours
     * @return Bill
     */
    public function setExtraHours($extraHours)
    {
        $this->extra_hours = $extraHours;
    
        return $this;
    }

    /**
     * Get extra_hours
     *
     * @return float 
     */
    public function getExtraHours()
    {
        return $this->extra_hours;
    }

    /**
     * Set extra_fees
     *
     * @param float $extraFees
     * @return Bill
     */
    public function setExtraFees($extraFees)
    {
        $this->extra_fees = $extraFees;
    
        return $this;
    }

    /**
     * Get extra_fees
     *
     * @return float 
     */
    public function getExtraFees()
    {
        return $this->extra_fees;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Bill
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
      * Set body content
      *
      * @param string $body_content
      * @return Bill
      */
     public function setBodyContent($body_content)
     {
         $this->body_content = $body_content;

         return $this;
     }

     /**
      * Get body content
      *
      * @return string
      */
     public function getBodyContent()
     {
         return $this->body_content;
     }

    /**
      * Set content
      *
      * @param string $content
      * @return Bill
      */
     public function setContent($content)
     {
         $this->content = $content;

         return $this;
     }

     /**
      * Get content
      *
      * @return string
      */
     public function getContent()
     {
         return $this->content;
     }

    /**
     * Set paid_at
     *
     * @param \DateTime $paidAt
     * @return Bill
     */
    public function setPaidAt($paidAt)
    {
        $this->paid_at = $paidAt;
    
        return $this;
    }

    /**
     * Get paid_at
     *
     * @return \DateTime 
     */
    public function getPaidAt()
    {
        return $this->paid_at;
    }

    /**
     * Set created_at
     *
     * @param \DateTime $createdAt
     * @return Bill
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
     * @ORM\PrePersist
     */
    public function prePersist() {
        $this->created_at = new \DateTime;
        $this->updated_at = new \DateTime;
    }

    /**
     * @ORM\PreUpdate
     */
    public function preUpdate() {
        $this->updated_at = new \DateTime;
    }
    
    /**
     * Set part
     *
     * @param \SGL\FLTSBundle\Entity\Part $part
     * @return Bill
     */
    public function setPart(\SGL\FLTSBundle\Entity\Part $part)
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
     * Set rate
     *
     * @param \SGL\FLTSBundle\Entity\Rate $rate
     * @return Bill
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
     * Add works
     *
     * @param \SGL\FLTSBundle\Entity\Work $works
     * @return Bill
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
     * Set deposited_at
     *
     * @param \DateTime $depositedAt
     * @return Bill
     */
    public function setDepositedAt($depositedAt)
    {
        $this->deposited_at = $depositedAt;
    
        return $this;
    }

    /**
     * Get deposited_at
     *
     * @return \DateTime 
     */
    public function getDepositedAt()
    {
        return $this->deposited_at;
    }

    /**
     * Get works + extra hours
     *
     * @return float
     */
    public function getHours() {
        $extra = 0;

        if ($this->getExtraHours() != null)
            $extra = $this->getExtraHours();

        return $extra + $this->getWorksHours();
    }

    /**
     * Get works hours
     *
     * @return float
     */
    public function getWorksHours() {
        $hours = 0;
        foreach ($this->works as $work) {
            $hours += $work->getHours();
        }

        return $hours;
    }

    /**
     * Set updated_at
     *
     * @param \DateTime $updatedAt
     * @return Bill
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
     * Set gst
     *
     * @param float $gst
     * @return Bill
     */
    public function setGst($gst)
    {
        $this->gst = $gst;
    
        return $this;
    }

    /**
     * Get gst
     *
     * @return float 
     */
    public function getGst()
    {
        return $this->gst;
    }

    /**
     * Set pst
     *
     * @param float $pst
     * @return Bill
     */
    public function setPst($pst)
    {
        $this->pst = $pst;

        return $this;
    }

    /**
     * Get pst
     *
     * @return float
     */
    public function getPst()
    {
        return $this->pst;
    }

    /**
     * Set hst
     *
     * @param float $hst
     * @return Bill
     */
    public function setHst($hst)
    {
        $this->hst = $hst;

        return $this;
    }

    /**
     * Get hst
     *
     * @return float
     */
    public function getHst()
    {
        return $this->hst;
    }

    /**
     * Set taxable
     *
     * @param boolean $taxable
     * @return Bill
     */
    public function setTaxable($taxable)
    {
        $this->taxable = $taxable;
    
        return $this;
    }

    /**
     * Get taxable
     *
     * @return boolean 
     */
    public function getTaxable()
    {
        return $this->taxable;
    }

    /**
     * Get works hours subtotal (works hours only)
     *
     * @return float $subtotal
     */
    public function getWorksHoursSubtotal() {
        $subtotal = 0;
        foreach ($this->works as $work) {
            $subtotal += ($work->getHours() * $work->getRate()->getRate());
        }
        return $subtotal;
    }

    /**
     * Get works hours and extra hours subtotal (works hours + extra hours)
     *
     * @return float $subtotal
     */
    public function getWorksAndExtraHoursSubtotal() {
        $subtotal = $this->getWorksHoursSubtotal();

        if ($this->getExtraHours()) {
            $subtotal += ($this->getExtraHours() * $this->getRate()->getRate());
        }
        return $subtotal;
    }

    /**
     * Get subtotal (works hours + extra hours + extra fees)
     *
     * @return float $subtotal
     */
    public function getSubtotal() {
        return $this->getWorksAndExtraHoursSubtotal() + $this->getExtraFees();
    }

    /**
     * Get GST fees (if applicable)
     *
     * @return float $fees
     */
    public function getGstFees() {
        if ($this->taxable) {
            return round($this->getSubtotal() * ($this->getGst() / 100),3);
        } else {
            return 0;
        }
    }

    /**
     * Get PST fees (if applicable)
     *
     * @return float $fees
     */
    public function getPstFees() {
        if ($this->taxable) {
            return round($this->getSubtotal() * ($this->getPst() / 100),3);
        } else {
            return 0;
        }
    }

    /**
     * Get PST fees (if applicable)
     *
     * @return float $fees
     */
    public function getHstFees() {
        if ($this->taxable) {
            return round($this->getSubtotal() * ($this->getHst()/100),3);
        } else {
            return 0;
        }
    }

    public function getTotal() {
        if ($this->taxable) {
            return round($this->getSubtotal(),2) + round($this->getGstFees(),2) + round($this->getPstFees(),2) + round($this->getHstFees(),2);
        } else {
            return round($this->getSubtotal(),2);
        }
    }

    /**
     * Get client name
     *
     * @return string
     */
    public function getClientName()
    {
        return $this->getPart()->getProject()->getClient()->getName();
    }

    public function hasEmptyContent() {
        return !$this->getBodyContent();
    }

    /**
      * Set note
      *
      * @param string $note
      * @return Bill
      */
     public function setNote($note)
     {
         $this->note = $note;

         return $this;
     }

     /**
      * Get note
      *
      * @return string
      */
     public function getNote()
     {
         return $this->note;
     }
}