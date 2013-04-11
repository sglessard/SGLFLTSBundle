<?php

/*
 * This file is part of the SGLFLTSBundle package.
 *
 * (c) Simon Guillem-Lessard <s.g.lessard@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SGL\FLTSBundle\Twig;


class TwigSGLFLTSExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            'price' => new \Twig_Filter_Method($this, 'twig_price_filter'),
            'hours' => new \Twig_Filter_Method($this, 'twig_hours_filter'),
            'relativeTime' => new \Twig_Filter_Method($this, 'twig_relative_time_filter'),
            'truncate' => new \Twig_Filter_Method($this,'twig_truncate_filter', array('needs_environment' => true)),
            'substring' => new \Twig_Filter_Method($this, 'twig_substring_filter', array('needs_environment' => true)),
            'localizeddate' => new \Twig_Filter_Method($this, 'twig_localized_date_filter', array('needs_environment' => true))
        );
    }

    public function getFunctions()
    {
        return array(
            'getControllerName' => new \Twig_Function_Method($this,'twig_get_controller_name'),
            'getActionName' => new \Twig_Function_Method($this,'twig_get_action_name'),
        );
    }

    public function twig_price_filter($number, $decimals = 2, $decPoint = ',', $thousandsSep = ' ')
    {
        $price = number_format($number, $decimals, $decPoint, $thousandsSep);
        $price = '$ ' . $price;

        return $price;
    }

    public function twig_hours_filter($string)
    {
        // Do not print '0' decimal
        if ($string == intval($string))
            return intval($string) . ' h';
        else
            return number_format($string,1,',',' ') . ' h';
    }

    /*
     * Relative time filter
     * Adapted from John McClumphaSGL (http://www.weberdev.com/get_example.php3?ExampleID=4769)
     *
     * @param integer
     * @return string
     */
    public function twig_relative_time_filter($diff){

        $periods = array("second", "minute", "hour", "day", "week", "month", "years", "decade");
        $lengths = array("60","60","24","7","4.35","12","10");

        $starting = "";
        $ending = "";

        for($j = 0; $diff >= $lengths[$j]; $j++) $diff /= $lengths[$j];

        $diff = round($diff);
        if ($diff != 1) {
            if (preg_match("/[^s]$/",$periods[$j]))
                $periods[$j].= "s";
        }
        $text = $starting.$diff." ".$periods[$j].$ending;

        return $text;
    }

    /**
     * From Twig_Extensions_Extension_Text
     *
     * @author Henrik Bjornskov <hb@peytz.dk>
     * @package Twig
     * @subpackage Twig-extensions
     *
     * @param Twig_Environment $env
     * @param $value
     * @param int $length
     * @param bool $preserve
     * @param string $separator
     * @return string
     */
    public function twig_truncate_filter(\Twig_Environment $env, $value, $length = 30, $preserve = false, $separator = '...')
    {
        if (strlen($value) > $length) {
            if ($preserve) {
                if (false !== ($breakpoint = strpos($value, ' ', $length))) {
                    $length = $breakpoint;
                }
            }

            return rtrim(substr($value, 0, $length)) . $separator;
        }

        return $value;
    }

    public function twig_substring_filter(\Twig_Environment $env, $value,$length)
    {
        return $this->twig_truncate_filter($env, $value, $length, false, '');
    }

    public function twig_get_controller_name($request_attributes){
        $pattern = "/Controller\\\\([a-zA-Z]*)Controller/";
        $matches = array();
        preg_match($pattern, $request_attributes->get("_controller"), $matches);

        return $matches[1];
    }

    public function twig_get_action_name($request_attributes){
        $pattern = "/Controller::([a-zA-Z]*)Action/";
        $matches = array();
        preg_match($pattern, $request_attributes->get("_controller"), $matches);

        return $matches[1];
    }

    /*
     * This method is part of Twig.
     *
     * (c) 2010 Fabien Potencier
     *
     * For the full copyright and license information, please view the LICENSE
     * file that was distributed with this source code.
     */
    public function twig_localized_date_filter(\Twig_Environment $env, $date, $dateFormat = 'medium', $timeFormat = 'medium', $locale = null, $timezone = null, $format = null)
    {
        if (!class_exists('IntlDateFormatter')) {
            throw new \RuntimeException('The intl extension is needed to use intl-based filters.');
        }

        $date = twig_date_converter($env, $date, $timezone);

         $formatValues = array(
             'none'   => \IntlDateFormatter::NONE,
             'short'  => \IntlDateFormatter::SHORT,
             'medium' => \IntlDateFormatter::MEDIUM,
             'long'   => \IntlDateFormatter::LONG,
             'full'   => \IntlDateFormatter::FULL,
         );

         $formatter = \IntlDateFormatter::create(
             $locale !== null ? $locale : \Locale::getDefault(),
             $formatValues[$dateFormat],
             $formatValues[$timeFormat],
             $date->getTimezone()->getName(),
             \IntlDateFormatter::GREGORIAN,
             $format
         );

         return $formatter->format($date->getTimestamp());
    }

    public function getName()
    {
        return 'flts_extension';
    }
}