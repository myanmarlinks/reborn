<?php

namespace Reborn\Util;

use Module;

/**
 * Menu class for Reborn Admin Panel
 *
 * @package Reborn\Util
 * @author Myanmar Links Professional Web Development Team
 **/
class Menu
{

	/**
	 * Current URL variable for menu item
	 *
	 * @var string
	 **/
	public $current;

	/**
	 * Menu items for Reborn Admin Panel
	 *
	 * @var array
	 **/
	public $items = array();

	/**
	 * Active Parent Menu
	 *
	 * @var string
	 **/
	public $activeParent;

	/**
	 * Menu Default Icon Name
	 *
	 * @var string
	 **/
	protected $default_icon = '';

	/**
	 * Default constructor method
	 *
	 * @return void
	 **/
	public function __construct()
	{
		$this->modules = Module::getAll();

		$this->items = \Config::get('adminmenu.default');

		$this->collect();
	}

	/**
	 * Set the ActiveParent Menu Name
	 *
	 * @return void
	 **/
	public function activeParent($name)
	{
		$this->activeParent = $name;
	}

	/**
	 * Generate the Menu HTML block.
	 *
	 * @return string
	 **/
	public function generate()
	{
		$class = '';

		if ((count(\Uri::segments()) == 1) and \Uri::segment(1) == ADMIN_URL) {
			$class = 'active';
		}
		$icon = '<i class="icon-globe icon-grey"></i>';
		$output = '<li id="dashboard-dashboard" class="first '.$class.'">';
		$output .= '<a href="'.adminUrl().'" >'.$icon.'Dashboard</a>';
		$output .= '</li>';

		foreach ($this->items as $order => $child) {

			if (empty($child)) {
				continue;
			}

			// URI String
			$uri = \Uri::segments();
			array_shift($uri);
			$uri = (count($uri) > 0) ? implode('/', $uri) : $uri;

			foreach ($child as $k => $name) {
				$icon = $this->getIcon($k, $child[$k]);
				if ( isset($name['child']) and empty($name['child'])) {
					$class = ($name['link'] == $uri) ? 'active' : '';
					$output .= '<li id="dashboard-'.$k.'" class="first '.$class.'">';
					$output .= '<a href="'.adminUrl().$name['link'].'" >';
					$output .= $icon.ucfirst($name['title']).'</a>';
					$output .= '</li>';
				} else {
					if (isset($child[$k]['child'])) {
						$class = ($this->activeParent == $k) ? 'active' : '';
						$output .= '<li id="dashboard-'.$k.'" class="first am_has_child '.$class.'">';
						$display = isset($name['title']) ? $name['title'] : static::displayName($k);
						$output .= '<a href="'.adminUrl().'#" >'.$icon.ucfirst($display).'</a>';
						$output .= '<ul class="dashboard-second-level">';
						foreach ($child[$k]['child'] as $key => $val) {
							$klass = ($val['link'] == $uri) ? 'active' : '';
							$output .= '<li class="'.$klass.'">';
							$output .= '<a href="'.adminUrl().$val['link'].'">';
							$output .= $val['title'];
							$output .= '</a>';
							$output .= '</li>';
						}
						$output .= '</ul>';
						$output .= '</li>';
					}
				}
				$class = $klass = '';
			}
		}

		return $output;
	}

	/**
	 * Add the new menu item.
	 *
	 * @param string $name Menu Item Name
	 * @param string $title Menu Item Title(display on View)
	 * @param string $link Menu Item URI string
	 * @param string $parent Parent menu name for this item
	 * @param integer $order Menu Order number
	 * @return void
	 **/
	public function add($name, $title, $link = "", $parent = null, $icon = null, $order = 35)
	{
		// Check $icon is int. It is order value
		if (is_int($icon)) {
			$order = $icon;
			$icon = null;
		}

		if (is_null($parent)) {
			$this->items[$order][$name] = array(
					'title' => $title,
					'link' => $link,
					'icon' => $icon,
					'child' => array()
				);
		} else {
			$parent = strtolower($parent);
			$linkArr = array(
						'title' => $title,
						'link' => $link,
						'icon' => $icon,
						'order' => $order
					);
			foreach ($this->items as $o => $n) {
				if (array_key_exists($parent, $n)) {
					$this->items[$o][$parent]['child'][$name] = $linkArr;
				}
			}
		}

		foreach ($this->items as $k => $val) {
			$name = array_keys($val);
			$name = $name[0];
			if (!empty($this->items[$k][$name]['child'])) {
				usort($this->items[$k][$name]['child'], array($this, 'sorting'));
			}
		}


		ksort($this->items);
	}

	/**
	 * Get the Menu Icon
	 *
	 * @param string $name Menu name
	 * @param array $menu Menu data array
	 * @return string
	 **/
	protected function getIcon($name, $menu)
	{
		if (! isset($menu['icon']) or is_null($menu['icon'])) {
			$icon_name = \Config::get('adminmenu.icons.'.$name, 'icon-globe');
		} else {
			$icon_name = $menu['icon'];
		}

		if(preg_match('/<img/', $icon_name)) {
				$icon = $icon_name;
		} else {
			$icon = '<i class="'.$icon_name.' icon-grey"></i>';
		}

		return $icon;
	}

	/**
	 * Convert snake case to Display name for menu name.
	 *
	 * @param string $name
	 * @return string
	 **/
	protected function displayName($name)
	{
		return ucwords(str_replace('_', ' ', $name));
	}

	/**
	 * Sorting the menu item order
	 *
	 * @return void
	 **/
	protected function sorting($a, $b)
	{
		$v1 = $a['order'];
		$v2 = $b['order'];
		return ($v1 < $v2) ? -1 : 1;
	}

	/**
	 * Collect the admin menu item forn the modules.
	 *
	 * @todo Need to check the user permission for module
	 * @return void
	 **/
	protected function collect()
	{
		foreach ($this->modules as $name => $mod) {
			if (Module::isEnabled($name)) {
				if (user_has_access(strtolower($name))) {
					Module::adminMenu($this, $name);
				}
			}
		}
	}

} // END class Menu
