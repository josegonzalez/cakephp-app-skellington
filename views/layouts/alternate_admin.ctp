<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Web App Theme</title>
	<?php echo $this->Html->css(array('admin/base', 'admin/themes/lighthouseapp/style')); ?>
	<?php echo $this->Html->script(array('jquery/jquery-1.3.min', 'jquery/jquery.scrollTo', 'jquery/jquery.localscroll')); ?>
	<script type="text/javascript" charset="utf-8">
		// <![CDATA[
		$(document).ready(function() {
			$.localScroll();
		});
		// ]]>
	</script>
</head>
<body>
	<div id="container">
		<div id="wrapper" class="wat-cf">
			<div id="box">
				<h1>Web App Theme</h1>
				<?php echo $content_for_layout; ?>
			</div>
		</div>
	</div>
</body>
</html>