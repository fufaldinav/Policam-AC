<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
            <div id="main">
                <div class="container">
                    <div id="info-container">
                        <div id="info-item-photo" class="info-item">
                            <div id="photo_bg" class="photo"></div>
                        </div>
                        <div id="info-item-f" class="info-item">
                            <?php echo lang('f');?><br />
                              <input id="f" name="f" size="30" type="text" readonly />
                        </div>
                        <div id="info-item-i" class="info-item">
                            <?php echo lang('i');?><br />
                              <input id="i" name="i" size="30" type="text" readonly />
                        </div>
                        <div id="info-item-o" class="info-item">
                            <?php echo lang('o');?><br />
                              <input id="o" name="o" size="30" type="text" readonly />
                        </div>
                        <div id="info-item-class" class="info-item">
                            <?php echo lang('class');?><br />
                            <input id="class" name="class" size="3" type="text" readonly />
                        </div>
                        <div id="info-item-birthday" class="info-item">
                            <?php echo lang('birthday');?><br />
                            <input id="birthday" name="birthday" size="15" type="date" readonly />
                        </div>
                        <div id="info-item-address" class="info-item">
                            <?php echo lang('address');?><br />
                              <input id="address" name="address" size="60" type="text" readonly />
                        </div>
                        <div id="info-item-phone" class="info-item">
                            <?php echo lang('phone');?><br />
                              <input id="phone" name="phone" size="15" type="text" readonly />
                        </div>
                        <div id="info-item-uid" class="info-item">
                            <?php echo lang('uid');?><br />
                              <input id="id" name="id" size="15" type="text" readonly />
                        </div>
                    </div>
                </div>
            </div>
