<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class product_db_admin_tab {

    public function __construct()
    {
        $this->EE =& get_instance();
        $this->EE->lang->loadfile('product_db_admin'); //names the tab Product Specification Editor
    }
    
	function publish_tabs($channel_id, $entry_id = '')
	{
		$settings = array();
		$selected = array();
		$existing_files = array();

		$query = $this->EE->db->get('download_files');
		
		foreach ($query->result() as $row)
		{
			$existing_files[$row->file_id] = $row->file_name;
		}

		if ($entry_id != '')
		{
			$query = $this->EE->db->get_where('download_posts', array('entry_id' => $entry_id));

			foreach ($query->result() as $row)
			{
				$selected[] = $row->file_id;
			}
		}

		$id_instructions = lang('id_field_instructions');
		
		// Load the module lang file for the field label
		$this->EE->lang->loadfile('download');

		$settings[] = array(
				'field_id'		=> 'download_field_ids',
				'field_label'		=> $this->EE->lang->line('download_files'),
				'field_required'	=> 'n',
				'field_data'		=> $selected,				
				'field_list_items'	=> $existing_files,
				'field_fmt'		=> '',
				'field_instructions' 	=> $id_instructions,
				'field_show_fmt'	=> 'n',
				'field_pre_populate'	=> 'n',
				'field_text_direction'	=> 'ltr',
				'field_type' 		=> 'multi_select'
			);

		return $settings;
	}

    function validate_publish($params)
    {
        return TRUE;
    }

    /**
     * Save the data to the db
     *
     * @param  $params
     * @return void
     */
    function publish_data_db($params)
    {
        $seo_lite_data = $params['mod_data'];
        $site_id = $params['meta']['site_id'];
        $entry_id = $params['entry_id'];

        $content = array(
            'site_id' => $site_id,
            'entry_id' => $entry_id,
            'title' => $seo_lite_data['seo_lite_title'],
            'keywords' => isset($seo_lite_data['product_db_admin_keywords']) ? $product_db_admin['product_db_admin_keywords'] : '',
            'description' => $seo_lite_data['product_db_admin_description'],
        );

        $table_name = 'productdbadmin_content';
        $where = array(
             'entry_id' => $entry_id,
             'site_id' => $site_id
        );

        $default_where = $where;
        $default_content = $content;
        $default_table_name = $table_name;

        // -------------------------------------------
        // Allows one to modify the SEO Lite saved in the tab (ie. for translation addons)
        //
        // Params sent in:
        // - $where - an array of where (activerecord) on UPDATE .. already contains 'entry_id' and 'site_id'
        // - $table_name - the name of the table to pull data from (without db prefix, defaults to 'seolite_content')
        // - $content - the current content saved (an array of site_id, entry_id, title, keywords, description)
        //
        // Return value:
        // Please return nothing at all or an array which contains 'where' and/or 'table_name' and/or 'content' to
        // replace any of these.
        //
        // But remember the content must contain 'site_id', 'entry_id', 'title', 'keywords', 'description'
        //
        // -------------------------------------------
        if ($this->EE->extensions->active_hook('product_db_admin_tab_content_save') === TRUE) {

            $hook_result = $this->return_data = $this->EE->extensions->call('product_db_admin_tab_content_save', $where, $table_name, $content);
            if($hook_result && isset($hook_result['where'])) {
                $where = $hook_result['where'];
            }
            if($hook_result && isset($hook_result['table_name'])) {
                $table_name = $hook_result['table_name'];
            }
            if($hook_result && isset($hook_result['content'])) {
                $content = $hook_result['content'];
            }

            if ($this->EE->extensions->end_script === TRUE) return;
        }

        $q = $this->EE->db->get_where($table_name, $where);

        if($q->num_rows())
        {
            $this->EE->db->where($where);
            $this->EE->db->update($table_name, $content);
        }
        else
        {
            $this->EE->db->insert($table_name, $content);
        }

        /**
         * If the data was stored to another table (ie if a third party addon took control over this, we still just
         * store the content in case that third_party addon is uninstalled later. Note that this may cause problems
         * with addons that store multiple versions for the same entry_id (ie. Publisher). If so SEO Lite will end
         * up with the latest stored version (which could be in language 1 or language 2 etc.) .. but in cases like
         * these a lot of data won't make sense anyway so .. in other cases, where the addon uses a different entry_id
         * for each type of content everything should work just fine if uninstalling that addon.
         */
        if($table_name != $default_table_name) {
            $q = $this->EE->db->get_where($default_table_name, $default_where);

            if($q->num_rows())
            {
                $this->EE->db->where($default_where);
                $this->EE->db->update($default_table_name, $default_content);
            }
            else
            {
                $this->EE->db->insert($default_table_name, $default_content);
            }
        }
    }

    /**
     * Delete seo data if entry is deleted
     *
     * @param  $params
     * @return void
     */
    function publish_data_delete_db($params)
    {
        foreach($params['entry_ids'] as $i => $entry_id)
        {
            $this->EE->db->where('entry_id', $entry_id);
            $this->EE->db->delete('productdbadmin_content');
        }
    }

}
