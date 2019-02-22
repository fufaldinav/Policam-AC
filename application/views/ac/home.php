<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
    <div id="container">
      <div id="menu" hidden>
      </div>
      <div id="main">
        <table border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td rowspan="4" align="center">
              <div id="photo_bg" class="photo"></div>
              <?php echo lang('class');?><br />
              <input id="class" name="class" size="3" type="text" readonly />
            </td>
            <td>
              <?php echo lang('f');?><br />
              <input id="f" name="f" size="30" type="text" readonly />
            </td>
          </tr>
          <tr>
            <td>
              <?php echo lang('i');?><br />
              <input id="i" name="i" size="30" type="text" readonly />
            </td>
          </tr>
          <tr>
            <td>
              <?php echo lang('o');?><br />
              <input id="o" name="o" size="30" type="text" readonly />
            </td>
          </tr>
          <tr height="10%">
            <td align="center">
              <?php echo lang('birthday');?><br />
              <input id="birthday" name="birthday" size="15" type="date" readonly />
            </td>
          </tr>
          <tr height="15%">
            <td colspan="2" align="center">
              <?php echo lang('address');?><br />
              <input id="address" name="address" size="60" type="text" readonly />
            </td>
          </tr>
          <tr height="10%">
           <td align="center">
            <?php echo lang('phone');?><br />
            <input id="phone" name="phone" size="15" type="text" readonly />
           </td>
           <td align="center">
            <?php echo lang('uid');?><br />
            <input id="id" name="id" size="15" type="text" readonly />
           </td>
          </tr>
          <tr height="10%">
             <td colspan="2" align="center">
                <button id="entrance_wo_card" type="button" onclick="toggleMenu();"><?php echo lang('entrance_wo_card');?></button>
             </td>
          </tr>
        </table>
      </div>
    </div>
