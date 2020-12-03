<footer class="main-footer">
	<?php if (ENVIRONMENT=='development'): ?>
		<div class="pull-right hidden-xs">
			<!-- CI Bootstrap Version: <strong><?php // echo CI_BOOTSTRAP_VERSION; ?></strong>, -->			Operator Admin Version: <strong>Build v0.0.1</strong>,
			CI Version: <strong><?php echo CI_VERSION; ?></strong>, 
			Elapsed Time: <strong>{elapsed_time}</strong> seconds, 
			Memory Usage: <strong>{memory_usage}</strong>
		</div>
	<?php endif; ?>
	<strong>&copy; <?php echo date('Y'); ?></strong> All rights reserved.  <a class="dark-grey-text" href="http://mytechbus.com">MyTechBus.com</a> developed and maintained by <a class="dark-grey-text" href="http://softctrl.in">SoftCtrl</a> for <a class="dark-grey-text" href="http://hpie.in">H.P.I.E</a> 
</footer>