<?php $this->Html->h2(__('Application Activity', true)); ?>
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
					<strong>
						<?php
							if ($this->Time->isToday($a_log['Log']['created'])) {
								echo "<mark>" . __('Today', true) . "</mark>";
							} elseif ($this->Time->wasYesterday($a_log['Log']['created'])) {
								__('Yesterday');
							} else {
								echo $this->Time->format('d M', $a_log['Log']['created']);
							}
						?>
					</strong>
				</td>
				</tr>
			<tr>
			<?php endif; ?>
				<td class="frontpage-type">
					<span class="<?php echo strtolower($a_log['Log']['model']);?>" style="<?php echo $this->Log->stylize($a_log['Log']['model']); ?>"><?php echo $a_log['Log']['model']; ?></span>
				</td>
				<td class="frontpage-title">
					<?php echo $this->Html->link($a_log['Log']['title'], array(
						'controller' => Inflector::pluralize(Inflector::camelize($a_log['Log']['model'])),
						'action' => 'view',
						'id' => $a_log['Log']['model_id'])); ?>
				</td>
				<td class="frontpage-owner">
					<?php
						$message = __('Created by %s ', true);
						switch ($a_log['Log']['action']) {
							case 'add' :
								$message = __('Created by %s ', true);
								break;
							case 'delete' :
								$message = __('Deleted by %s ', true);
								break;
							default :
								$message = __('Updated by %s ', true);
								break;
						}
					?>
					<?php $user = (isset($activity['User']['name'])) ? $activity['User']['name'] : __('System', true); ?>
					<?php echo sprintf($message, "<span class=\"username\">$user</span>"); ?>
				</td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>