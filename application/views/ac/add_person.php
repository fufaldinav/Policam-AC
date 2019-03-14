<?php defined('BASEPATH') or exit('No direct script access allowed');?>
		<div id="main"  class="main-grid-container">
			<div id="menu">
				<?php foreach ($divs as $div): ?>
				<div id="div<?php echo $div->id?>" class="menu-item" onclick="setDiv(<?php echo $div->id?>);">
					<?php echo $div->name?>
				</div>
				<?php endforeach;?>
			</div>
			<div id="info">
				<div id="info-photo" class="info-item">
					<div id="photo_bg" class="photo">
						<div id="photo_del" data-title="<?php echo lang('delete')?>" hidden>
							<img src="/img/delete.png" />
						</div>
					</div>
					<input id="photo" name="photo" type="file" onchange="handleFiles(this.files)" /><br />
				</div>
				<div id="info-f" class="info-item">
					<?php echo lang('f');?><br />
					<input maxlength="20" id="f" name="f" size="30" type="text" onchange="checkData(this);" required />
				</div>
				<div id="info-i" class="info-item">
					<?php echo lang('i');?><br />
					<input maxlength="20" id="i" name="i" size="30" type="text" onchange="checkData(this);" required />
				</div>
				<div id="info-o" class="info-item">
					<?php echo lang('o');?><br />
					<input maxlength="20" id="o" name="o" size="30" type="text" />
				</div>
				<div id="info-div" class="info-item">
					<!-- CLASS TO DELETE -->
				</div>
				<div id="info-birthday" class="info-item">
					<?php echo lang('birthday');?><br />
					<input maxlength="20" id="birthday" name="birthday" size="15" type="date" onchange="checkData(this);" required />
				</div>
				<div id="info-address" class="info-item">
					<?php echo lang('address');?><br />
					<input maxlength="50" id="address" name="address" size="60" type="text" />
				</div>
				<div id="info-phone" class="info-item">
					<?php echo lang('phone');?><br />
					<input maxlength="10" id="phone" name="phone" size="15" type="text" />
				</div>
				<div id="info-card" class="info-item">
					<?php echo lang('card');?><br />
					<?php echo form_dropdown('cards', $cards, '0', $cards_attr);?>
				</div>
				<div id="info-button1" class="info-item">
					<button type="button" onclick="savePersonInfo();">
						<?php echo lang('save');?></button>
				</div>
				<div id="info-button2" class="info-item">
					<button type="button" onclick="clearPersonInfo();">
						<?php echo lang('clear');?></button>
				</div>
			</div>
		</div>
