<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 *
 * @copyright     Copyright 2010, Dreaming Mind (http://dreamingmind.org)
 * @link          http://dreamingmind.com
 * @package       bindery
 * @subpackage    bindery.model
 */
/**
 * DispatchGallery Model
 * 
 * @package       bindery
 * @subpackage    bindery.model
 * 
 */
class DispatchGallery extends AppModel {
	var $name = 'DispatchGallery';
        
        var $belongsTo = array('Dispatch', 'Gallery');
}
?>