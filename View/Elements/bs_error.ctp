<?php /**
* Cakestrap error flash element
*
* Style's flash messages with Bootstrap
* 
* PHP 5/CakePHP 2.0
*
* Cakestrap: https://github.com/calmsu/cakestrap
* Copyright 2012, Michigan State University Board of Trustees
*
* Licensed under The MIT License
* Redistributions of files must retain the above copyright notice.
*
* @copyright     Copyright 2012, Michigan State University Board of Trustees
* @link          http://github.com/calmsu/cakestrap
* @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
*/
?>
<div class="alert alert-danger">
  <button data-dismiss="alert" class="close">&times;</button>
  <strong><?php echo __('Oops!');?></strong> <?php echo $message ?>
  
</div>