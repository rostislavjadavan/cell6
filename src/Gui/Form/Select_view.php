<label for="<?php echo $id ?>"><?php echo $label ?></label>

<?php if (!\System\Utils\Text::isEmpty($note)): ?>
<p class="help-block"><?php echo $note ?></p>
<?php endif ?>

<select name="<?php echo $id ?>" class="form-control form-control-size-<?php echo $size ?> <?php echo $class ?>" id="form-field-<?php echo $id ?>">
	<?php foreach($options as $optionValue => $optionCaption) : ?>
	<option <?php if (strval($optionValue) == strval($value)) echo 'selected="selected"'?> value="<?php echo $optionValue ?>"><?php echo $optionCaption ?></option>
	<?php endforeach ?>
</select>
