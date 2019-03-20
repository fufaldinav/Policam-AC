<?php defined('BASEPATH') or exit('No direct script access allowed');?>
		<div id="main">
			<div id="scrollable-container">
				<table border="0" cellpadding="4" cellspacing="0">
					<thead>
						<tr>
							<th><?php echo lang('number');?></th>
							<th><?php echo lang('letter');?></th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td><input id="number" type="text" size="2" maxlength="2" required=""></td>
							<td><input id="letter" type="text" size="1" maxlength="1" required=""></td>
							<td><button onclick="saveDivision(<?php echo $org_id;?>)"><?php echo lang('save');?></button></td>
						</tr>
						<?php foreach ($divs as $div):?>
						<?php if ($div->type != 1) { continue; } ?>
						<tr>
							<?php $name = explode(' ', $div->name);?>
							<td><?php echo $name[0];?></td>
							<td><?php echo $name[1] ?? '';?></td>
							<td><button onclick="deleteDivision(<?php echo $div->id;?>)"><?php echo lang('delete');?></button></td>
						</tr>
					<?php endforeach;?>
					</tbody>
				</table>
			</div>
		</div>
