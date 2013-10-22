<?php

/*
 * This file is part of the SGLFLTSBundle package.
 *
 * (c) Simon Guillem-Lessard <s.g.lessard@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SGL\FLTSBundle\Tests\Util\Tax;

use SGL\FLTSBundle\Util\Tax\CanadaTax;

class CalculatorTest extends \PHPUnit_Framework_TestCase
{
    public function testImportParameterFromArray()
    {
        $values = array(
            'gst'=>array(5.00),
            'pst'=>array(2010=>7.875, 2011=>8.925, 2012=>9.975),
            'hst'=>array()
        );

        $canTax = new CanadaTax();
        $canTax->setDefaultGst($values['gst']);
        $canTax->setDefaultPst($values['pst']);
        $canTax->setDefaultHst($values['hst']);

        $result = 0;
        if ($canTax->getGst() === 5.00)
            $result++;
        if ($canTax->getPst() === 9.975)
            $result++;
        if ($canTax->getHst() === 0.00)
            $result++;

        $this->assertTrue($canTax->getGst() === 5.000);
        $this->assertTrue($canTax->getPst() === 9.975);
        $this->assertTrue($canTax->getHst() === 0);
    }
}