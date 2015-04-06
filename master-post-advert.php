<?php
/*
 * Plugin Name: Master Post Advert
 * Plugin URI:  http://www.bbproject.net/moje-projekty/inne/master-post-advert
 * Description: Display advertising between the introduction and post content.
 * Author:      BBPROJECT.NET
 * Author URI:  http://www.bbproject.net
 * Version:     1.0.1
 */

// -----------------------------------------------------------------------------

class MasterPostAdvert
{

	// ---------------------------------------------------------------------------

	private $name       = 'master_post_advert';
	private $plugin_dir = '';
	private $options;

	// ---------------------------------------------------------------------------

	/**
	 * Pobranie ustawien
	 *
	 * @return array
	 */
	private function getOptions()
	{

		if (!isset($this->options)) {

			$default_options = array(
				'align' => 'none',
				'title' => '',
				'width' => 0,
				'code'  => ''
			);
			$this->options = get_option($this->name);

			if (!is_array($this->options)) {
				$this->options = array();
			}
			foreach ($default_options as $name => $value) {
				if (!isset($this->options[$name])) {
					$this->options[$name] = $value;
				}
			}

		}

		return $this->options;

	}

	// ---------------------------------------------------------------------------

	/**
	 * Konstruktor
	 *
	 * return void
	 */
	public function __construct()
	{
		$this->plugin_dir = WP_PLUGIN_DIR.'/'.str_replace(basename(__FILE__), '', plugin_basename(__FILE__));
		load_plugin_textdomain($this->name, false, str_replace(WP_PLUGIN_DIR, '', $this->plugin_dir).'languages');
		add_action('admin_menu', array($this, 'actionAdminMenu'));
		add_filter('the_content', array($this, 'filterTheContent'));
	}

	// ---------------------------------------------------------------------------

	/**
	 * Zdarzenie
	 *
	 * @return void
	 */
	public function actionAdminMenu()
	{
		add_action('admin_init', array($this, 'actionAdminInit'));
		add_options_page(__('Master Post Advert Settings', $this->name), 'Master Post Advert', 'install_plugins', basename(__FILE__), array($this, 'callbackOptions'));
	}

	// ---------------------------------------------------------------------------

	/**
	 * Zdarzenie
	 *
	 * @return void
	 */
	public function actionAdminInit()
	{
		register_setting($this->name.'_options', $this->name, array($this, 'callbackValidate'));
	}

	// ---------------------------------------------------------------------------

	/**
	 * Formularz ustawien
	 *
	 * @return void
	 */
	public function callbackOptions()
	{
		$options = $this->getOptions();
		include $this->plugin_dir.'options.php';
	}

	// ---------------------------------------------------------------------------

	/**
	 * Walidacja danych formularza ustawien
	 *
	 * @param  array $data
	 * @return array
	 */
	public function callbackValidate($data)
	{
		$data['align'] = trim(strtolower($data['align']));
		$data['title'] = trim($data['title']);
		$data['width'] = (int)$data['width'];
		$data['code']  = trim($data['code']);
		return $data;
	}

	// ---------------------------------------------------------------------------

	/**
	 * Parsowanie tresci
	 *
	 * @param  string $content
	 * @return string
	 */
	public function filterTheContent($content)
	{

		if (is_feed()) {

			return $content;

		} else if (stripos($content, '<span id="more-') === false) {

			if (stripos($content, 'more-link') === false) {
				return $content;
			} else {
				return preg_replace('/#more-[0-9]+/i', '', $content);
			}

		} else {

			$options = $this->getOptions();
			if ($this->options['code']) {
				return preg_replace_callback(
					'/(<[a-z0-9]+.*?>)?(<span id="more-[0-9]+"><\/span>)(<\/[a-z0-9]+>)?/i',
					function($matches) use ($options) {
						switch ($options['align']) {
							case 'left':   $margin = 'margin: 10px auto 10px 0px;'; break;
							case 'center': $margin = 'margin: 10px auto;'; break;
							case 'right':  $margin = 'margin: 10px 0px 10px auto;'; break;
							default:       $margin = 'margin: 10px 0px;';
						}
						$width = $options['width'] > 0 ? " width:{$options['width']}px;" : '';
						$title = $options['title'] ? "<div>{$options['title']}</div>\n" : '';
						$ad =
							"<div class=\"master_post_advert\" style=\"{$margin}{$width}\">\n".
								$title.$options['code']."\n".
							"</div>";
						list($all, $open_tag, $more_tag, $close_tag) = $matches;
						if ($open_tag && $close_tag) {
							return $ad."\n".$all;
						} else if ($open_tag) {
							return $ad."\n".$open_tag.$more_tag;
						} else if ($close_tag) {
							return $more_tag.$close_tag."\n".$ad;
						} else {
							return "\n".$ad."\n".$more_tag;
						}
					},
					$content
				);
			} else {
				return $content;
			}

		}

	}

}

// -----------------------------------------------------------------------------

add_action('init', function() {
	new MasterPostAdvert();
});