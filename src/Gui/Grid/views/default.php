
<table class="table">
	<thead>
		<tr>
			<?php foreach ($columns as $col): ?>
				<th><?php echo $col->getTitle() ?></th>
			<?php endforeach ?>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($data as $row): ?>
			<tr>
				<?php foreach ($columns as $col): ?>
				<td><?php echo $col->render($row) ?></td>
				<?php endforeach ?>
			</tr>
		<?php endforeach ?>
	</tbody>
</table>