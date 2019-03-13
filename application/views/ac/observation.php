<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
		<div id="main" class="main-grid-container">
			<div id="menu">
				<?php foreach ($divs as $div): ?>
				<div id="div<?php echo $div->id?>" class="menu-item" onclick="getPersons(<?php echo $div->id?>);"><?php echo $div->name?></div>
				<?php endforeach;?>
			</div>
			<div id="info">
				<div id="info-photo" class="info-item">
					<div id="photo_bg" class="photo"></div>
				</div>
				<div id="info-f" class="info-item">
					<?php echo lang('f');?><br />
					<input id="f" name="f" size="30" type="text" readonly />
				</div>
				<div id="info-i" class="info-item">
					<?php echo lang('i');?><br />
					<input id="i" name="i" size="30" type="text" readonly />
				</div>
				<div id="info-o" class="info-item">
					<?php echo lang('o');?><br />
					<input id="o" name="o" size="30" type="text" readonly />
				</div>
				<div id="info-div" class="info-item">
					<!-- CLASS TO DELETE -->
				</div>
				<div id="info-birthday" class="info-item">
					<?php echo lang('birthday');?><br />
					<input id="birthday" name="birthday" size="15" type="date" readonly />
				</div>
				<div id="info-address" class="info-item">
					<?php echo lang('address');?><br />
					<input id="address" name="address" size="60" type="text" readonly />
				</div>
				<div id="info-phone" class="info-item">
					<?php echo lang('phone');?><br />
					<input id="phone" name="phone" size="15" type="text" readonly />
				</div>
				<div id="info-uid" class="info-item">
					<?php echo lang('uid');?><br />
					<input id="id" name="id" size="15" type="text" readonly />
				</div>
			</div>
		</div>
