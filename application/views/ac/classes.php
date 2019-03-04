<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
?>
		<div id="main">
			<div id="scrollable-container">
				<table border="0" cellpadding="4" cellspacing="0">
					<thead>
						<tr>
							<th><?php echo lang('number'); ?></th>
							<th><?php echo lang('letter'); ?></th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td><input id="number" type="text" size="2" maxlength="2" required=""></td>
							<td><input id="letter" type="text" size="1" maxlength="1" required=""></td>
							<td><button onclick="saveDivision(2)"><?php echo lang('save'); ?></button></td>
						</tr>
						<?php foreach ($divs as $div): ?>
						<tr>
							<td><?php echo $div->number; ?></td>
							<td><?php echo $div->letter; ?></td>
							<td><button onclick="deleteDivision(<?php echo $div->id; ?>)"><?php echo lang('delete'); ?></button></td>
						</tr>
					<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		</div>
