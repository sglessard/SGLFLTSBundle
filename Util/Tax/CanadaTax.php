<?php

/*
 * This file is part of the SGLFLTSBundle package.
 *
 * (c) Simon Guillem-Lessard <s.g.lessard@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SGL\FLTSBundle\Util\Tax;

/**
 * Canada Tax class
 */
class CanadaTax implements TaxInterface {

    /**
     * Goods and Services Tax
     *
     * @var float
     */
    protected $gst;

    /** Provincial Sales Tax
     *
     * @var float
     */
    protected $pst;

    /**
     * Harmonized Sales Tax
     *
     * @var float
     */
    protected $hst;


    /**
     * Set default GST according to config parameters
     *      If values is a single array, return value
     *      If now.year is in values, return year value
     *      Else return last aray value
     *
     * @param array $values GST Tax rates
     * @return CanadaTax
     */
    public function setDefaultGst(array $values)
    {
        $this->gst = $this->importParameterFromArray($values);

        return $this;
    }

    /**
     * Set default PST according to config parameters
     *      If values is a single array, return value
     *      If now.year is in values, return year value
     *      Else return last aray value
     *
     * @param array $values PST Tax rates
     * @return CanadaTax
     */
    public function setDefaultPst(array $values)
    {
        $this->pst = $this->importParameterFromArray($values);

        return $this;
    }

    /**
     * Set default HST according to config parameters
     *      If values is a single array, return value
     *      If now.year is in values, return year value
     *      Else return last aray value
     *
     * @param array $values HST Tax rates
     * @return CanadaTax
     */
    public function setDefaultHst(array $values)
    {
        $this->hst = $this->importParameterFromArray($values);

        return $this;
    }

    /**
     * Set gst
     *
     * @param float $gst
     * @return CanadaTax
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
     * @return CanadaTax
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
     * @return CanadaTax
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
     * Import tax rate parameter from config
     *     If rates is a single array, return the value
     *     If now->year is in rate values, return year value
     *     Else if year is before 1st year value, return this first value (as first known tax rate)
     *     Else (year is after last year value) return last array value (as last known tax rate)
     *
     * @param array $rates
     * @return float $rate
     */
    private function importParameterFromArray($rates) {
        if (sizeof($rates) < 1) {
            $rate =  0;
        } else if (sizeof($rates) == 1) {
            $rate =  current($rates);
        } else {
            $now = new \DateTime();
            $year = $now->format('Y');
            if (isset($rates[$year])) {
                $rate = $rates[$year];
            } else if ($year < current(array_keys($rates))) {
                $rate = current($rates);
            } else {
                $rate = end($rates);
            }
        }
        return $rate;
    }
}