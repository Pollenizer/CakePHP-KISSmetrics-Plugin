<?php
/**
 * KISSmetrics View Helper 
 *
 * A CakePHP View Helper class for KISSmetrics tracking
 *
 * PHP version 5
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the below copyright notice.
 *
 * @author     Kevin Nguyen <salty@pollenizer.com>
 * @copyright  Copyright 2012, Pollenizer Pty. Ltd. (http://pollenizer.com/)
 * @license    MIT License (http://www.opensource.org/licenses/mit-license.php)
 * @version    1.0
 */

class KissmetricsHelper extends AppHelper {

    /**
     * The access key
     *
     * @var string $key
     * @access private 
     */
    private $key;

    public  $helpers = array('Session', 'Html');

    /**
     * Construct a KissmetricsHelper with a key 
     *
     * @return null
     */
    public function __construct(View $View, $settings = array())
    {
        parent::__construct($View, $settings);
        if (empty($settings)) {
            Configure::load('kissmetrics');
            $settings = Configure::read('Kissmetrics');
        }

        if (empty($settings)) {
            throw new Exception('KISSmetrics configuration required');
        }

        $this->setKey($settings['key']);
    }

    /**
     * Generate the tracking code to allow KISSmetrics to record events. The tracking code will be generated with     * the KISSmetric key. The code will automatically identify the user by Id if the user is logged in.
     *
     * @param bool $inline Whether or not the $script should be added to $scripts_for_layout
     * @return string containg tracking code
     */
    public function trackingCode($inline = true)
    {
        $script = '';
        if ($inline) {
            $script .= $this->Html->scriptStart(array('inline' => false));
        }

        $script .= 'var _kmq = _kmq || [];';
        $script .= 'function _kms(u){';
        $script .= 'setTimeout(function(){';
        $script .= 'var s = document.createElement(\'script\'); var f = document.getElementsByTagName(\'script\')[0]; s.type = \'text/javascript\'; s.async = true;';
        $script .= 's.src = u; f.parentNode.insertBefore(s, f);';
        $script .= '}, 1);';
        $script .= '}';
        $script .= '_kms(\'//i.kissmetrics.com/i.js\');_kms(\'//doug1izaerwt3.cloudfront.net/' . $this->getKey() . '.1.js\');';
        if ($this->Session->check('Auth.User.id')) {
            $script .= '_kmq.push([\'identify\', \'' .  $this->Session->read('Auth.User.id') . '\']);';
        }

        if ($inline) {
            $script .= $this->Html->scriptEnd();
        }

        $scriptBlock = $this->Html->scriptBlock($script, array('inline' => $inline, 'safe' => false));
        return $scriptBlock;
    }

    /**
     * Set the key
     *
     * @return object KissmetricsHelper
     */
    private function setKey($key)
    {
        $this->_key = $key;
        return $this;
    }

    /**
     * Get the key
     *
     * @return string $key
     */
    public function getKey()
    {
        return $this->_key;
    }
}
