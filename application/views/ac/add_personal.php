<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
    <div id="container">
      <div id="main">
        <table border="0" cellpadding="0" cellspacing="0">
          <form name="pers_info" target="hidden">
              <tr>
                  <td rowspan="4" align="center">
                      <div id="photo_bg" class="photo"><div id="photo_del" data-title="<?php echo lang('delete')?>" hidden><img src="/img/delete.png" /></div></div>
                      <input id="photo" name="photo" type="file" onchange="handleFiles(this.files)" /><br />
                      <?php echo lang('class');?><br />
                      <?php echo form_dropdown('class', $classes, '0', $class_attr);?>
                  </td>
                  <td>
                      <?php echo lang('f');?><br />
                      <input maxlength="20" id="f" name="f" size="30" type="text" required />
                  </td>
              </tr>
              <tr>
                  <td>
                      <?php echo lang('i');?><br />
                      <input maxlength="20" id="i" name="i" size="30" type="text" required />
                  </td>
              </tr>
              <tr>
                  <td>
                      <?php echo lang('o');?><br />
                      <input maxlength="20" id="o" name="o" size="30" type="text" />
                  </td>
              </tr>
              <tr height="10%">
                  <td align="center">
                      <?php echo lang('birthday');?><br />
                      <input maxlength="20" id="birthday" name="birthday" size="15" type="date" required />
                  </td>
              </tr>
              <tr height="15%">
                <td colspan="2" align="center">
                  <?php echo lang('address');?><br />
                  <input maxlength="50" id="address" name="address" size="60" type="text" />
                </td>
              </tr>
              <tr height="10%">
                 <td align="center">
                    <?php echo lang('phone');?><br />
                    <input maxlength="10" id="phone" name="phone" size="15" type="text" />
                 </td>
                 <td align="center">
                    <?php echo lang('card');?><br />
                    <?php echo form_dropdown('card_menu', $cards, '0', $card_attr);?>
                 </td>
              </tr>
          </form>
              <tr height="10%">
                 <td align="right">
                    <button type="button" onclick="savePersInfo(true);"><?php echo lang('save');?></button>
                 </td>
                 <td align="left">
                    <button type="button" onclick="savePersInfo(false);"><?php echo lang('clear');?></button>
                 </td>
              </tr>
        </table>
      </div>
    </div>
