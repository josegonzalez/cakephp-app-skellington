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
		<?php foreach ($logs as $log) : ?>
			<tr>
			<?php if ($this->Log->checkIfChanged(date('Y-m-d', strtotime($log['Log']['created'])))) : ?>
				<td colspan="3">
					<strong>
						<?php
							if ($this->Time->isToday($log['Log']['created'])) {
								echo "<mark>" . __('Today', true) . "</mark>";
							} elseif ($this->Time->wasYesterday($log['Log']['created'])) {
								__('Yesterday');
							} else {
								echo $this->Time->format('d M', $log['Log']['created']);
							}
						?>
					</strong>
				</td>
				</tr>
			<tr>
			<?php endif; ?>
				<td class="frontpage-type">
					<span class="<?php echo strtolower($log['Log']['model']);?>" style="<?php echo $this->Log->stylize($log['Log']['model']); ?>"><?php echo $log['Log']['model']; ?></span>
				</td>
				<td class="frontpage-title">
					<?php echo $this->Html->link($log['Log']['title'], array(
						'controller' => Inflector::pluralize(Inflector::camelize($log['Log']['model'])),
						'action' => 'view',
						'id' => $log['Log']['model_id'])); ?>
				</td>
				<td class="frontpage-owner">
					<?php
						$message = __('Created by %s ', true);
						switch ($log['Log']['action']) {
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