<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 *
 * @package		contact_us
 * @subpackage	ThirdParty
 * @category	Modules
 * @author		Loren Miller, C. Eric Miller
 * @link		http://uprog.com/
 */
class contact_us_upd {

var $version = '1.0';
var $module_name = "contact_us";
public $EE;

    public function  __construct( $switch = TRUE ) 
    { 
		$this->EE =& get_instance();
    } 
    function install()
    {

        $data = array(
    'module_name' => "contact_us",
    'module_version' => $this->version,
    'has_cp_backend' => 'y',
    'has_publish_fields' => 'n'
        );
        $this->EE->db->insert('modules', $data);

        return TRUE;
    }
    function uninstall(){
        $this->EE->db->select('module_id');
        $query = $this->EE->db->get_where('modules', array('module_name' => $this->module_name));
    
        $this->EE->db->where('module_id', $query->row('module_id'));
        $this->EE->db->delete('module_member_groups');
    
        $this->EE->db->where('module_name', $this->module_name);
        $this->EE->db->delete('modules');

        return TRUE;
    }

    function update($current = '')
    {
        if ($current == $this->version)
        {
            return FALSE;
        }
    }

}
/* End of file upd.contact_us.php */
/* Location: ./system/expressionengine/third_partycontact_us/upd.contact_us.php */