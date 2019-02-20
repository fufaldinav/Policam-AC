<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<html>
   <head>
      <meta charset="utf-8"/>
      <?php echo $css;?>
      <?php echo $js;?>
   </head>
   <body>
      <div id="header">
        <div id="header-container">
          <div id="header-content">
            <?php echo $nav;?>
          </div>
        </div>
        <div id="header-left">
          <?php echo lang('school');?> <?php echo $school;?>
        </div>
        <div id="header-right">
          <a class="nav" href="/auth/logout"><?php echo lang('exit');?></a>
        </div>
      </div>
