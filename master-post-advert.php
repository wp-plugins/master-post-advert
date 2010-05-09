<?php
/*
Plugin Name: Master Post Advert
Plugin URI: http://www.bbproject.net/moje-projekty/inne/master-post-advert
Description: Display advertising between the introduction and post content.
Author: M@ster
Version: 1.0.0
Author URI: http://www.bbproject.net
*/

// -----------------------------------------------------------------------------

class MasterPostAdvert
{

  // ---------------------------------------------------------------------------

  private $name = 'master_post_advert';
  private $plugin_dir = '';
  private $options;

  // ---------------------------------------------------------------------------

  /**
   * Pobranie ustawien
   *
   * @return array
   */
  private function get_options()
  {
    if ( ! isset($this->options))
    {
      $default_options = array
      (
        'align' => 'none',
        'title' => '',
        'width' => 0,
        'code' => ''
      );
      $this->options = get_option($this->name);
      if ( ! is_array($this->options))
      {
        $this->options = array();
      }
      foreach ($default_options as $name => $value)
      {
        if ( ! isset($this->options[$name]))
        {
          $this->options[$name] = $value;
        }
      }
    }
    return $this->options;
  }

  // ---------------------------------------------------------------------------

  /**
   * Zamiana znacznika "more" tresci na reklame
   *
   * @param array $matches
   * @return string
   */
  private function the_content_preg_callback($matches)
  {
    switch ($this->options['align'])
    {
      case 'left':   $margin = 'margin:10px auto 10px 0px;'; break;
      case 'center': $margin = 'margin:10px auto;'; break;
      case 'right':  $margin = 'margin:10px 0px 10px auto;'; break;
      default:       $margin = 'margin:10px 0px;';
    }
    $width = $this->options['width'] > 0 ? " width:{$this->options['width']}px;" : '';
    $ad =
      "<div class=\"{$this->name}\" style=\"{$margin}{$width}\">\n".
        "<div>{$this->options['title']}</div>\n".
        "{$this->options['code']}\n".
      "</div>";
    list($all, $open_tag, $more_tag, $close_tag) = $matches;
    if ($open_tag && $close_tag)
    {
      return $ad."\n".$all;
    }
    else if ($open_tag)
    {
      return $ad."\n".$open_tag.$more_tag;
    }
    else if ($close_tag)
    {
      return $more_tag.$close_tag."\n".$ad;
    }
    else
    {
      return "\n".$ad."\n".$more_tag;
    }
  }

  // ---------------------------------------------------------------------------

  /**
   * Konstruktor
   *
   * return void
   */
  public function __construct()
  {
    $this->plugin_dir = WP_PLUGIN_DIR.'/'.str_replace(basename( __FILE__), '', plugin_basename(__FILE__));
    load_plugin_textdomain($this->name, FALSE, str_replace(WP_PLUGIN_DIR, '', $this->plugin_dir).'languages');
    add_action('admin_menu', array($this, 'admin_menu'));
    add_filter('the_content', array($this, 'the_content'));
  }

  // ---------------------------------------------------------------------------

  /**
   * Zdarzenie
   *
   * @return void
   */
  public function admin_menu()
  {
    add_action('admin_init', array($this, 'admin_init'));
    add_options_page(__('Master Post Advert Settings', $this->name), 'Master Post Advert', 'install_plugins', basename(__FILE__), array($this, 'options'));
  }

  // ---------------------------------------------------------------------------

  /**
   * Zdarzenie
   *
   * @return void
   */
  public function admin_init()
  {
    register_setting($this->name.'_options', $this->name, array($this, 'validate'));
  }

  // ---------------------------------------------------------------------------

  /**
   * Walidacja danych formularza ustawien
   *
   * @param array $data
   * @return array
   */
  public function validate($data)
  {
    $data['align'] = trim(strtolower($data['align']));
    $data['title'] = trim($data['title']);
    $data['width'] = (integer)$data['width'];
    $data['code'] = trim($data['code']);
    return $data;
  }

  // ---------------------------------------------------------------------------

  /**
   * Formularz ustawien
   *
   * @return void
   */
  public function options()
  {
    include $this->plugin_dir.'options.php';
  }

  // ---------------------------------------------------------------------------

  /**
   * Parsowanie tresci
   *
   * @param string $content
   * @return string
   */
  public function the_content($content)
  {
    if (stripos($content, '<span id="more-') === FALSE)
    {
      if (stripos($content, 'more-link') === FALSE)
      {
        return $content;
      }
      else
      {
        return preg_replace('/#more-[0-9]+/i', '', $content);
      }
    }
    else
    {
      $this->get_options();
      if ($this->options['code'])
      {
        return preg_replace_callback
        (
          '/(<[a-z0-9]+.*?>)?(<span id="more-[0-9]+"><\/span>)(<\/[a-z0-9]+>)?/i',
          array($this, 'the_content_preg_callback'),
          $content
        );
      }
      else
      {
        return $content;
      }
    }
  }

}

// -----------------------------------------------------------------------------

/**
 * Inicjalizacja
 *
 * @global object $master_post_advert
 * @return void
 */
function master_post_advert_init()
{
  global $master_post_advert;
	$master_post_advert = new MasterPostAdvert();
}

// -----------------------------------------------------------------------------

add_action('init', 'master_post_advert_init');