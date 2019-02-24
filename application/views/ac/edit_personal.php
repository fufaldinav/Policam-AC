<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
            <div id="main">
                <div class="container main-container">
                    <div id="menu" onclick="tree_toggle(arguments[0]);">
                        <?php echo $menu;?>
                    </div>
                    <div id="info">
                        <div id="info-container">
                            <div id="info-item-photo" class="info-item">
                                <div id="photo_bg" class="photo">
                                    <div id="photo_del" data-title="<?php echo lang('delete')?>" hidden>
                                        <img src="/img/delete.png" />
                                    </div>
                                </div>
                                <input id="photo" name="photo" type="file" hidden /><br />
                                <?php echo lang('class');?><br />
                                <?php echo form_dropdown('class', $classes, '0', $class_attr);?>
                            </div>
                            <div id="info-item-f" class="info-item">
                                <?php echo lang('f');?><br />
                                <input maxlength="20" id="f" name="f" size="30" type="text" required readonly />
                            </div>
                            <div id="info-item-i" class="info-item">
                                <?php echo lang('i');?><br />
                                <input maxlength="20" id="i" name="i" size="30" type="text" required readonly />
                            </div>
                            <div id="info-item-o" class="info-item">
                                <?php echo lang('o');?><br />
                                <input maxlength="20" id="o" name="o" size="30" type="text" readonly />
                            </div>
                            <div id="info-item-birthday" class="info-item">
                                <?php echo lang('birthday');?><br />
                                <input maxlength="20" id="birthday" name="birthday" size="15" type="date" required readonly />
                            </div>
                            <div id="info-item-address" class="info-item">
                                <?php echo lang('address');?><br />
                                <input maxlength="50" id="address" name="address" size="60" type="text" readonly />
                            </div>
                            <div id="info-item-phone" class="info-item">
                                <?php echo lang('phone');?><br />
                                <input maxlength="10" id="phone" name="phone" size="15" type="text" readonly />
                            </div>
                            <div id="info-item-card" class="info-item">
                                <?php echo lang('card');?><br />
                                <div id="cards"></div>
                                <div id="card_selector">
                                    <?php echo form_dropdown('card_menu', $cards, '0', $card_attr);?>
                                </div>
                            </div>
                            <div id="info-item-button1" class="info-item">
                                <button id="save" type="button"><?php echo lang('save');?></button>
                            </div>
                            <div id="info-item-button2" class="info-item">
                                <button id="delete" type="button"><?php echo lang('delete');?></button>
                            </div>
                        </div>
                    </div>
                </div>
                <input id="id" name="id" type="text" hidden readonly />
            </div>
