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
</div>