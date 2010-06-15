<h2><?php __('Application Activity'); ?></h2>
<table id="recent-activity" class="table" cellpadding="0" cellspacing="0">
	<thead class="hide">
		<tr>
			<td><?php __('Type'); ?></td>
			<td><?php __('Title'); ?></td>
			<td><?php __('Owner'); ?></td>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($logs as $a_log) : ?>
			<tr>
			<?php if ($this->Log->checkIfChanged(date('Y-m-d', strtotime($a_log['Log']['created'])))) : ?>
				<td colspan="3">
					<?php echo $this->Log->logDate($a_log['Log']['created']); ?>
				</td>
				</tr>
			<tr>
			<?php endif; ?>
				<td class="frontpage-type">
					<?php echo $this->Log->logType($a_log['Log']); ?>
				</td>
				<td class="frontpage-title">
					<?php echo $this->Log->logTitle($a_log['Log']); ?>
				</td>
				<td class="frontpage-owner">
					<?php echo $this->Log->logOwner($a_log['Log']['action'], $a_log['User']); ?>
				</td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>