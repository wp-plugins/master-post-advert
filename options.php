<div class="wrap">
	<h2><?php _e('Master Post Advert Settings', $this->name); ?></h2>
	<p><?php _e('Settings of Your ad', $this->name); ?>.</p>
	<form method="post" action="options.php">
		<?php settings_fields($this->name.'_options'); ?>
		<?php $options = $this->get_options(); ?>
		<table class="form-table">
			<tr valign="top">
				<th scope="row"><?php _e('Advert area alignment', $this->name); ?>:</th>
				<td>
					<fieldset>
						<?php foreach(array('none', 'left', 'center', 'right') as $align): ?>
						<input id="master-post-advert-align-<?php echo $align; ?>" type="radio" name="master_post_advert[align]" value="<?php echo $align; ?>"<?php if ($align == $options['align']) echo ' checked="checked"'; ?> />
						<label for="master-post-advert-align-<?php echo $align; ?>" style="margin-right: 10px;"><?php _e(ucfirst($align)); ?></label>
						<?php endforeach; ?>
					</fieldset>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php _e('Advert area title', $this->name); ?>:</th>
				<td><input type="text" name="master_post_advert[title]" value="<?php echo htmlspecialchars($options['title']); ?>" class="regular-text code" /> <span class="description"><?php _e('HTML enabled', $this->name); ?></span></td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php _e('Advert area width', $this->name); ?>:</th>
				<td><input type="text" name="master_post_advert[width]" value="<?php echo $options['width']; ?>" class="small-text" /> <span class="description">px, 0 = <?php _e('automatic', $this->name); ?></span></td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php _e('Advert code', $this->name); ?>:</th>
				<td><textarea name="master_post_advert[code]" cols="50" rows="10" class="large-text code"><?php echo htmlspecialchars($options['code']); ?></textarea></td>
			</tr>
		</table>
		<p class="submit">
			<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
		</p>
	</form>
	<p>Copyright &copy;2010 <a href="http://www.bbproject.net">BBProject.net</a></p>
	<form action="https://www.paypal.com/cgi-bin/webscr" method="post" />
		<input type="hidden" name="cmd" value="_s-xclick" />
		<input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----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-----END PKCS7-----" />
		<input type="image" src="https://www.paypal.com/en_GB/i/btn/btn_donate_SM.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online." />
		<img alt="" border="0" src="https://www.paypal.com/pl_PL/i/scr/pixel.gif" width="1" height="1" />
	</form>
</div>