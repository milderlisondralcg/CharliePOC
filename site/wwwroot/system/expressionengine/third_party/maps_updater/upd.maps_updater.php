<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 *
 * @package		Maps_updater
 * @subpackage	ThirdParty
 * @category	Modules
 * @author		Loren Miller, C. Eric Miller
 * @link		http://uprog.com/
 */
class Maps_updater_upd {

var $version = '1.0';
var $module_name = "maps_updater";
public $EE;

    public function  __construct( $switch = TRUE ) 
    { 
		$this->EE =& get_instance();
    } 
    function install()
    {

        $data = array(
    'module_name' => "maps_updater",
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
/* End of file upd.maps_updater.php */
/* Location: ./system/expressionengine/third_party/maps_updater/upd.maps_updater.php */