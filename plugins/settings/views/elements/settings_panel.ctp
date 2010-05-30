<h2> <?php __d('settings', 'Configure Settings'); ?></h2>
<?php
$headers = array(
	__d('settings', 'key', true),
	__d('settings', 'type', true),
	__d('settings', 'value', true)
);
$rows = array();

echo "<h3>Neat Array</h3>";
echo $toolbar->makeNeatArray($content);

echo "<h3>Summary</h3>";
foreach ($content as $key => $value) {
	if (!is_array($value)) {
		$rows['General'][] = array($key, gettype($value), $value);
		continue;
	}
	foreach ($value as $k => $v) {
		if (is_object($v) || is_array($v)) continue;
		$rows[$key][] = array($k, gettype($v), $v);
	}
}

foreach ($rows as $title => $row) {
	echo "<h4>{$title}</h4>";
	echo $toolbar->table($row, $headers, array('title' => $title));
}

?>
