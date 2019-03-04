<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8" />
	<?php foreach ($css_list as $css): ?>
		<link rel="stylesheet" href="/css/<?php echo $css ?>.css" />
	<?php endforeach; ?>
	<script src="/js/jquery-3.3.1.min.js"></script>
	<?php foreach ($js_list as $js): ?>
		<script src="/js/ac/<?php echo $js ?>-0.0.5.js"></script>
	<?php endforeach; ?>
</head>

<body>
	<div id="container">
		<div id="header">
			<div id="header-container">
				<div id="header-left">
					<?php echo lang('school');?>
					<?php echo $org_name;?>
				</div>
				<div id="header-content">
					<a class="nav" href="/"><?php echo lang('observ') ?></a>
					<?php if ($this->ion_auth->in_group(2)): ?>
						<a class="nav" href="/ac/add_person"><?php echo lang('adding') ?></a>
						<a class="nav" href="/ac/edit_persons"><?php echo lang('editing') ?></a>
						<a class="nav" href="/ac/classes"><?php echo lang('classes') ?></a>
					<?php endif; ?>
				</div>
				<div id="header-right">
					<a class="nav" href="/auth/logout">
						<?php echo lang('exit');?></a>
				</div>
			</div>
		</div>
