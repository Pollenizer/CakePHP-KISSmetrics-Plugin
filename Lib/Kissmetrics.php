<?php
/**
 * KISSmetrics Library
 *
 * A CakePHP class for interfacing with the KISSmetrics PHP SDK
 *
 * PHP version 5
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the below copyright notice.
 *
 * @author     Robert Love <robert@pollenizer.com>
 * @copyright  Copyright 2012, Pollenizer Pty. Ltd. (http://pollenizer.com/)
 * @license    MIT License (http://www.opensource.org/licenses/mit-license.php)
 * @version    1.0
 * @since      File available since Release 2.1.0
 * @link       http://developers.facebook.com/docs/reference/php/
 */

/**
 * KISSmetrics PHP SDK
 *
 * @see https://github.com/kissmetrics/KISSmetrics
 */
App::uses('km', 'Kissmetrics.Vendor');
class Kissmetrics
{
    /**
     * KISSmetrics
     * 
     * @var object KISSmetrics
     */
    private $km;


    /**
     * Constructor
     * 
     * @return null
     */
    public function __construct()
    {
        Configure::load('kissmetrics');
        $settings = Configure::read('Kissmetrics');
        $this->km = new km();
        $this->km->init($settings['key'], array(
                'log_dir'  => TMP,
                'use_cron' => true,
                'to_stderr' => true, 
            )
        );
    }

    /**
     * Call
     *
     * @param string $name The name of the method being called
     * @param array $arguments The arguments being passed to the method
     * @return mixed
     */
    public function __call($name, $arguments = array())
    {
        $count = count($arguments);
        if ($count == 0) {
            return $this->km->$name();
        }

        return call_user_func_array(array($this->km, $name), $arguments);
    }
    
}
