<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
            <div id="main">
                <div class="info-container">
                    <div class="info-item info-item-photo">
                        <div id="photo_bg" class="photo">
                              <div id="photo_del" data-title="<?php echo lang('delete')?>" hidden>
                                  <img src="/img/delete.png" />
                              </div>
                        </div>
                        <input id="photo" name="photo" type="file" onchange="handleFiles(this.files)" /><br />
                        <?php echo lang('class');?><br />
                        <?php echo form_dropdown('class', $classes, '0', $class_attr);?>
                    </div>
                    <div class="info-item info-item-f">
                        <?php echo lang('f');?><br />
                        <input maxlength="20" id="f" name="f" size="30" type="text" required />
                    </div>
                    <div class="info-item info-item-i">
                        <?php echo lang('i');?><br />
                        <input maxlength="20" id="i" name="i" size="30" type="text" required />
                    </div>
                    <div class="info-item info-item-o">
                        <?php echo lang('o');?><br />
                        <input maxlength="20" id="o" name="o" size="30" type="text" />
                    </div>
                    <div class="info-item info-item-birthday">
                        <?php echo lang('birthday');?><br />
                        <input maxlength="20" id="birthday" name="birthday" size="15" type="date" required />
                    </div>
                    <div class="info-item info-item-address">
                        <?php echo lang('address');?><br />
                        <input maxlength="50" id="address" name="address" size="60" type="text" />
                    </div>
                    <div class="info-item info-item-phone">
                        <?php echo lang('phone');?><br />
                        <input maxlength="10" id="phone" name="phone" size="15" type="text" />
                    </div>
                    <div class="info-item info-item-card">
                        <?php echo lang('card');?><br />
                          <?php echo form_dropdown('card_menu', $cards, '0', $card_attr);?>
                    </div>
                    <div class="info-item info-item-button1">
                        <button type="button" onclick="savePersInfo(true);"><?php echo lang('save');?></button>
                    </div>
                    <div class="info-item info-item-button2">
                        <button type="button" onclick="savePersInfo(false);"><?php echo lang('clear');?></button>
                    </div>
                </div>
            </div>
