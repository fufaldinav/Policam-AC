<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
            <div id="main">
                <div class="info-container">
                    <div class="info-item info-item-photo">
                        <div id="photo_bg" class="photo"></div>
                        <?php echo lang('class');?><br />
                        <input id="class" name="class" size="3" type="text" readonly />
                    </div>
                    <div class="info-item info-item-f">
                        <?php echo lang('f');?><br />
                          <input id="f" name="f" size="30" type="text" readonly />
                    </div>
                    <div class="info-item info-item-i">
                        <?php echo lang('i');?><br />
                          <input id="i" name="i" size="30" type="text" readonly />
                    </div>
                    <div class="info-item info-item-o">
                        <?php echo lang('o');?><br />
                          <input id="o" name="o" size="30" type="text" readonly />
                    </div>
                    <div class="info-item info-item-birthday">
                        <?php echo lang('birthday');?><br />
                          <input id="birthday" name="birthday" size="15" type="date" readonly />
                    </div>
                    <div class="info-item info-item-address">
                        <?php echo lang('address');?><br />
                          <input id="address" name="address" size="60" type="text" readonly />
                    </div>
                    <div class="info-item info-item-phone">
                        <?php echo lang('phone');?><br />
                          <input id="phone" name="phone" size="15" type="text" readonly />
                    </div>
                    <div class="info-item info-item-uid">
                        <?php echo lang('uid');?><br />
                          <input id="id" name="id" size="15" type="text" readonly />
                    </div>
                    <div class="info-item info-item-button">
                        <button id="entrance_wo_card" type="button" onclick="toggleMenu();"><?php echo lang('entrance_wo_card');?></button>
                    </div>
                </div>
            </div>
