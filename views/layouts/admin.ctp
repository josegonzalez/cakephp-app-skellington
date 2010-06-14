<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Web App Theme</title>
	<?php echo $this->Html->css(array('admin/base')); ?>
	<?php echo $this->Html->css(array('admin/themes/default/style'), null, array('id' => 'current-theme')); ?>
	<?php echo $this->Html->script(array('jquery/jquery-1.3.min', 'jquery/jquery.scrollTo', 'jquery/jquery.localscroll')); ?>
	<script type="text/javascript" charset="utf-8">
		// <![CDATA[
			var Theme = {
				activate: function(name) {
					window.location.hash = 'themes/' + name
					Theme.loadCurrent();
				},
				loadCurrent: function() {
				var hash = window.location.hash;
				if (hash.length > 0) {
					matches = hash.match(/^#themes\/([a-z0-9\-_]+)$/);
					if (matches && matches.length > 1) {
						$('#current-theme').attr('href', 'css/admin/themes/' + matches[1] + '/style.css');
					} else {
						alert('theme not valid');
					}
				}
			}
		}

		$(document).ready(function() {
			Theme.loadCurrent();
			$.localScroll();
			$('.table :checkbox.toggle').each(function(i, toggle) {
				$(toggle).change(function(e) {
					$(toggle).parents('table:first').find(':checkbox:not(.toggle)').each(function(j, checkbox) {
						checkbox.checked = !checkbox.checked;
					})
				});
			});
		});
		// ]]>
	</script>
	<script type="text/javascript" charset="utf-8">
		// <![CDATA[
		$(document).ready(function() {
			$.localScroll();
			$('.table :checkbox.toggle').each(function(i, toggle) {
				$(toggle).change(function(e) {
					$(toggle).parents('table:first').find(':checkbox:not(.toggle)').each(function(j, checkbox) {
						checkbox.checked = !checkbox.checked;
					})
				});
			});
		});
		// ]]>
	</script>
</head>
<body>
	<div id="container">
		<div id="header">
			<h1><?php echo $this->Html->link('Web App Theme', '/'); ?></h1>
			<div id="user-navigation">
				<ul class="wat-cf">
					<li><?php echo $this->Html->link(__('Profile', true), array(
						'controller' => 'users', 'action' => 'profile')); ?></li>
					<li><?php echo $this->Html->link(__('Settings', true), array(
						'plugin' => 'settings', 'controller' => 'settings', 'action' => 'index')); ?></li>
					<li><?php echo $this->Html->link(__('Logout', true), array(
						'controller' => 'users', 'action' => 'logout')); ?></li>
					<li><a class="logout" href="#">Logout</a></li>
				</ul>
			</div>
			<div id="main-navigation">
				<ul class="wat-cf">
					<?php echo $this->element('admin/main_navigation'); ?>
				</ul>
			</div>
		</div>
		<div id="wrapper" class="wat-cf">
			<div id="main">

				<div class="block" id="block-text">
					<div class="secondary-navigation">
						<ul class="wat-cf">
							<?php echo $secondary_navigation_for_layout; ?>
						</ul>
					</div>
					<div class="content">
						<h2 class="title">
							<?php echo (isset($h2_for_layout)) ? $h2_for_layout : $this->params['action']; ?>
						</h2>
						<div class="inner">
							<?php echo $this->Session->flash(); ?>
							<?php if (isset($content_for_layout)) echo $content_for_layout; ?>
						</div>
					</div>
				</div>

				<div id="footer">
					<div class="block">
						<p>Copyright &copy; 2010 Your Site.</p>
					</div>
				</div>
			</div>
			<div id="sidebar">
				<div class="block notice">
					<h4>Notice Title</h4>
					<p>Morbi posuere urna vitae nunc. Curabitur ultrices, lorem ac aliquam blandit, lectus eros hendrerit eros, at eleifend libero ipsum hendrerit urna. Suspendisse viverra. Morbi ut magna. Praesent id ipsum. Sed feugiat ipsum ut felis. Fusce vitae nibh sed risus commodo pulvinar. Duis ut dolor. Cras ac erat pulvinar tortor porta sodales. Aenean tempor venenatis dolor.</p>
				</div>
				<div class="block">
					<div class="sidebar-block">
						<h4>Sidebar Inner block Title</h4>
						<p>Morbi posuere urna vitae nunc. Curabitur ultrices, lorem ac <a href="#">aliquam blandit</a>, lectus eros hendrerit eros, at eleifend libero ipsum hendrerit urna. Suspendisse viverra. Morbi ut magna. Praesent id ipsum. Sed feugiat ipsum ut felis. Fusce vitae nibh sed risus commodo pulvinar. Duis ut dolor. Cras ac erat pulvinar tortor porta sodales. Aenean tempor venenatis dolor.</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
</html>