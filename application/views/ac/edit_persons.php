<?php defined('BASEPATH') or exit('No direct script access allowed');?>
		<div id="main" class="main-grid-container">
			<div id="menu" onclick="tree_toggle(arguments[0]);">
				<ul class="tree-container">
					<?php $last_div = count($divs) - 1;?>
					<?php foreach ($divs as $k => $div):?>
					<li class="tree-node tree-is-root tree-expand-closed <?php echo $k === $last_div ? 'tree-is-last' : '';?>">
						<div class="tree-expand"></div>
						<div class="tree-content tree-expand-content">
							<?php echo $div->name;?>
						</div>
						<ul class="tree-container">
              <?php $persons = $div->persons->orderBy('f ASC, i ASC, o ASC')->get();?>
							<?php $last_person = count($persons) - 1;?>
							<?php foreach ($persons as $n => $person):?>
							<li id="person<?php echo $person->id?>" class="tree-node tree-expand-leaf <?php echo $n === $last_person ? 'tree-is-last' : '';?>">
								<div class="tree-expand"></div>
								<div class="tree-content">
									<a class="person<?php echo (! $person->cards->get()) ? ' no-card' : ''?>" href="#<?php echo $person->id?>" onClick="getPersonInfo(<?php echo $person->id;?>);"><?php echo "$person->f $person->i";?></a>
								</div>
							</li>
							<?php endforeach;?>
						</ul>
					</li>
					<?php endforeach;?>
				</ul>
			</div>
			<div id="info">
				<div id="info-photo" class="info-item">
					<div id="photo_bg" class="photo">
						<div id="photo_del" data-title="<?php echo lang('delete');?>" hidden>
							<img src="/img/delete.png" />
						</div>
					</div>
					<input id="photo" name="photo" type="file" hidden /><br />
				</div>
				<div id="info-f" class="info-item">
					<?php echo lang('f');?><br />
					<input maxlength="20" id="f" name="f" size="30" type="text" required readonly />
				</div>
				<div id="info-i" class="info-item">
					<?php echo lang('i');?><br />
					<input maxlength="20" id="i" name="i" size="30" type="text" required readonly />
				</div>
				<div id="info-o" class="info-item">
					<?php echo lang('o');?><br />
					<input maxlength="20" id="o" name="o" size="30" type="text" readonly />
				</div>
				<div id="info-div" class="info-item">
					<!-- CLASS TO DELETE -->
				</div>
				<div id="info-birthday" class="info-item">
					<?php echo lang('birthday');?><br />
					<input maxlength="20" id="birthday" name="birthday" size="15" type="date" required readonly />
				</div>
				<div id="info-address" class="info-item">
					<?php echo lang('address');?><br />
					<input maxlength="50" id="address" name="address" size="60" type="text" readonly />
				</div>
				<div id="info-phone" class="info-item">
					<?php echo lang('phone');?><br />
					<input maxlength="10" id="phone" name="phone" size="15" type="text" readonly />
				</div>
				<div id="info-card" class="info-item">
					<?php echo lang('card');?><br />
					<div id="person_cards"></div>
					<div id="unknown_cards">
						<?php echo form_dropdown('cards', $cards, '0', $cards_attr);?>
					</div>
				</div>
				<div id="info-button1" class="info-item">
					<button id="save" type="button">
						<?php echo lang('save');?></button>
				</div>
				<div id="info-button2" class="info-item">
					<button id="delete" type="button">
						<?php echo lang('delete');?></button>
				</div>
			</div>
			<input id="id" name="id" type="text" hidden readonly />
			<input id="type" name="type" type="text" hidden readonly />
		</div>
