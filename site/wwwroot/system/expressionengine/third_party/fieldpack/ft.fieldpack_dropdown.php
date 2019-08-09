<?php if (! defined('BASEPATH')) exit('No direct script access allowed');


if (! class_exists('Fieldpack_Fieldtype'))
{
	require PATH_THIRD.'fieldpack/fieldpack_fieldtype.php';
}


/**
 * Field Pack - Dropdown Class
 *
 * @package   P&T Field Pack
 * @author    Pixel & Tonic Inc. <support@pixelandtonic.com>
 * @copyright Copyright (c) 2013 Pixel & Tonic, Inc
 */
class Fieldpack_dropdown_ft extends Fieldpack_Multi_Fieldtype {

	var $info = array(
		'name'     => 'Field Pack - Dropdown',
		'version'  => FIELDPACK_VER
	);

	var $class = 'fieldpack_dropdown';
	var $total_option_levels = 2;

	// --------------------------------------------------------------------

	/**
	 * Install
	 */
	function install()
	{
		if (! class_exists('FF2EE2'))
		{
			require PATH_THIRD.'fieldpack/ff2ee2/ff2ee2.php';
		}

		new FF2EE2(array('ff_select', 'fieldpack_dropdown'));

		$this->helper->convert_types('pt_dropdown', 'fieldpack_dropdown');
		$this->helper->uninstall_fieldtype('pt_dropdown');
		$this->helper->disable_extension();

		return array();
	}

	// --------------------------------------------------------------------

	/**
	 * Prep Field Data
	 */
	function prep_field_data(&$data)
	{
		if (is_array($data))
		{
			$data = array_shift($data);
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Display Field
	 */
	function _display_field($data, $field_name)
	{
		if (empty($this->settings['options']))
		{
			return $this->no_options_set();
		}

		$this->prep_field_data($data);

		return form_dropdown($field_name, $this->settings['options'], $data);
	}

	/**
	 * Display Cell
	 */
	function display_cell($data)
	{
		if (is_string($data)) $data = str_replace('"', '&quot;', html_entity_decode($data, ENT_QUOTES));

		return $this->_display_field($data, $this->cell_name);
	}

	/**
	 * Display the element.
	 *
	 * @param $data
	 * @return mixed
	 */
	function display_element($data)
	{
		$this->_include_ce_icon('dropdown');
		return $this->display_field($data);
	}


	// --------------------------------------------------------------------

	/**
	 * Replace Tag
	 */
	function replace_tag($data, $params = array(), $tagdata = FALSE)
	{
		if (! isset($this->settings['options']) || ! $this->settings['options'])
		{
			return $data;
		}

		if (! $tagdata)
		{
			return $this->replace_ul($data, $params);
		}

		$this->prep_field_data($data);
		$r = '';

		if ($this->settings['options'] && $data)
		{
			// optional sorting
			if (isset($params['sort']) && $params['sort'])
			{
				$sort = strtolower($params['sort']);

				if ($sort == 'asc')
				{
					sort($data);
				}
				else if ($sort == 'desc')
				{
					rsort($data);
				}
			}

			// offset and limit
			if (isset($params['offset']) || isset($params['limit']))
			{
				$offset = isset($params['offset']) ? $params['offset'] : 0;
				$limit = isset($params['limit']) ? $params['limit'] : count($data);
				$data = array_splice($data, $offset, $limit);
			}

			// parse {total_selections} up front
			$tagdata = $this->EE->TMPL->swap_var_single('total_selections', (string)count($data), $tagdata);

			// prepare for {switch} and {count} tags
			$this->prep_iterators($tagdata);

			foreach($data as $option_name)
			{
				if (($option = $this->_find_option($option_name, $this->settings['options'])) !== FALSE)
				{
					// copy $tagdata
					$option_tagdata = $tagdata;

					// simple var swaps
					$option_tagdata = $this->EE->TMPL->swap_var_single('option', $option, $option_tagdata);
					$option_tagdata = $this->EE->TMPL->swap_var_single('option_name', $option_name, $option_tagdata);

					// seems there was a mix-up in the documentation and things where ambiguous. Let's make it crystal clear.
					$option_tagdata = $this->EE->TMPL->swap_var_single('option_value', $option_name, $option_tagdata);
					$option_tagdata = $this->EE->TMPL->swap_var_single('option_label', $option, $option_tagdata);

					// parse {switch} and {count} tags
					$this->parse_iterators($option_tagdata);

					$r .= $option_tagdata;
				}
			}

			if (isset($params['backspace']) && $params['backspace'])
			{
				$r = substr($r, 0, -$params['backspace']);
			}
		}

		return $r;
	}

	// --------------------------------------------------------------------

	/**
	 * Option Label
	 */
	function replace_label($data)
	{
		$this->prep_field_data($data);

		$label = $this->_find_label($data, $this->settings['options']);
		return $label ? $label : '';
	}

	/**
	 * Render the element.
	 *
	 * @param $data
	 * @param array $params
	 * @param $tagdata
	 * @return bool
	 */
	function replace_element_tag($data, $params = array(), $tagdata)
	{
		$label = $data;

		// Defensively load label value
		if (isset($this->settings['options'][$data]))
		{
			$label = $this->settings['options'][$data];
		}

		$value = $data;

		$replace = array(
			'value' => $value,
			'label' => $label,
			'element_name' => $this->element_name
		);

		return $this->EE->functions->var_swap($tagdata, $replace);
	}

	/**
	 * Find Label
	 */
	private function _find_label($data, $options)
	{
		foreach($options as $name => $label)
		{
			if (is_array($label) && ($sublabel = $this->_find_label($data, $label)) !== FALSE)
			{
				return $sublabel;
			}
			else if ((string) $data === (string) $name)
			{
				return $label;
			}
		}
		return FALSE;
	}
}
