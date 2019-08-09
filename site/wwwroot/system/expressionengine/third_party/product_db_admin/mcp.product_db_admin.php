

<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class product_db_admin_mcp {
    var $base;			// the base url for this module			
	var $form_base;		// base url for forms
	var $module_name = "product_db_admin";	
    public function __construct( $switch = TRUE )
	{
		$this->EE =& get_instance(); 
		$this->base	 	 = BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module='.$this->module_name;
		$this->form_base = 'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module='.$this->module_name;
		$this->EE->cp->set_right_nav(array(
			'home'			=> $this->base,
			'publish'		=> "#",
		));
        
//        $user["something"] = $this->EE->session->userdata(‘logged_in_user’);

		$this->EE->load->add_package_path(PATH_THIRD.'product_db_admin/');
		$this->EE->cp->load_package_css('bootstrap');
		$this->EE->cp->load_package_css('main');
        $this->EE->cp->load_package_css('datatables');
		
		$this->EE->lang->loadfile('product_db_admin');

		$this->EE->cp->load_package_js('bootstrap.min');
        
        $this->EE->cp->load_package_js('jquery.min');
        $this->EE->cp->load_package_js('datatables.min');
		$this->EE->cp->load_package_js('sisyphus.min');
        $this->EE->cp->load_package_js('main');
        $this->EE->cp->load_package_js('products');
        $this->EE->cp->load_package_js('edit');
//        $this->EE->cp->load_package_js('close');

        // include('D:/home/site/wwwroot/system/expressionengine/third_party/product_db_admin/assets/DataTables/datatables.min.js');
	}

	function index() 
	{
		return $this->content_wrapper('index', 'product_db_admin_welcome');
		print 'hello :' . $this->EE->session->userdata('group_id');
    }
    function content_wrapper($content_view, $lang_key)
	{
		$vars['content_view'] = $content_view;
		$vars['_base'] = $this->base;
		$vars['_form_base'] = $this->form_base;

        $this->EE->view->cp_page_title = lang($lang_key);

		$this->EE->cp->set_breadcrumb($this->base, lang('Product Specifications'));
		return $this->EE->load->view('_wrapper', $vars, TRUE);
	}
}
// END CLASS

/* End of file mcp.product_db_admin.php */