<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Maps_updater_mcp {
    var $base;			// the base url for this module			
	var $form_base;		// base url for forms
	var $module_name = "maps_updater";	
    public function __construct( $switch = TRUE )
	{
		$this->EE =& get_instance(); 
		$this->base	 	 = BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module='.$this->module_name;
		$this->form_base = 'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module='.$this->module_name;
		$this->EE->cp->set_right_nav(array(
			'home'			=> $this->base,
			'publish'		=> "#",
		));

		$this->EE->load->add_package_path(PATH_THIRD.'maps_updater/');

		$this->EE->cp->load_package_css('bootstrap');
		$this->EE->cp->load_package_css('trumbowyg');
		$this->EE->cp->load_package_css('style');
		$this->EE->cp->load_package_css('selectize');
		
		$this->EE->lang->loadfile('maps_updater');
		
		$this->EE->cp->load_package_js('scripts');
		$this->EE->cp->load_package_js('jquery');
		$this->EE->cp->load_package_js('bootstrap');
		$this->EE->cp->load_package_js('trumbowyg');
		$this->EE->cp->load_package_js('selectize');
		
	}

	function index() 
	{
		return $this->content_wrapper('index', 'maps_updater_welcome');
    }
    function content_wrapper($content_view, $lang_key)
	{
		$vars['content_view'] = $content_view;
		$vars['_base'] = $this->base;
		$vars['_form_base'] = $this->form_base;

        $this->EE->view->cp_page_title = lang($lang_key);

		$this->EE->cp->set_breadcrumb($this->base, lang('maps_updater_module_name'));
		return $this->EE->load->view('_wrapper', $vars, TRUE);
	}
}
// END CLASS

/* End of file mcp.maps_updater.php */