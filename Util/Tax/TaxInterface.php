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
 * Tax class
 */
interface TaxInterface
{
    /**
     * Set default GST according to config parameters (@see config service calls)
     *
     * @param array $values GST Tax rates (by years : tax rates change yearly)
     * @return CanadaTax
     */
    public function setDefaultGst(array $values);

    /**
     * Set default PST according to config parameters (@see config service calls)
     *
     * @param array $values PST Tax rates (by years : tax rates change yearly)
     * @return CanadaTax
     */
    public function setDefaultPst(array $values);

    /**
     * Set default HST according to config parameters (@see config service calls)
     *
     * @param array $values HST Tax rates (by years : tax rates change yearly)
     * @return CanadaTax
     */
    public function setDefaultHst(array $values);

    /**
     * Set gst
     *
     * @param float $gst
     * @return CanadaTax
     */
    public function setGst($gst);

    /**
     * Get gst
     *
     * @return float
     */
    public function getGst();
    /**
     * Set pst
     *
     * @param float $pst
     * @return CanadaTax
     */
    public function setPst($pst);

    /**
     * Get pst
     *
     * @return float
     */
    public function getPst();

    /**
     * Set hst
     *
     * @param float $hst
     * @return CanadaTax
     */
    public function setHst($hst);

    /**
     * Get hst
     *
     * @return float
     */
    public function getHst();
}
