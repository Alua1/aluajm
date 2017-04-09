<?php
/**
 * FW Gallery Light 3.5.0
 * @copyright (C) 2017 Fastw3b
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.fastw3b.net/ Official website
 **/

defined( '_JEXEC' ) or die( 'Restricted access' );

class fwGallerylightModelConfig extends JModelLegacy {
    function loadObj() {
    	$obj = new stdclass;
    	$obj->params = JComponentHelper::getParams('com_fwgallerylight');
        return $obj;
    }

    function save() {
    	$params = JComponentHelper::getParams('com_fwgallerylight');
		$input = JFactory::getApplication()->input;
		$data = (array)$input->getVar('config');

    	$fields = array(
			'display_descr_gallery',
			'display_descr_image',
			'allow_frontend_galleries_management',
			'im_just_shrink',
			'use_watermark',
			'display_total_galleries',
			'display_owner_gallery',
			'display_owner_image',
			'display_date_gallery',
			'display_gallery_sorting',
			'display_name_gallery',
			'display_name_image',
			'display_date_image',
			'display_image_views',
			'allow_print_button',
			'hide_bottom_image',
			'display_user_copyright',
			'display_social_sharing'
		);
		foreach ($fields as $field) $data[$field] = $input->getVar($field);

	   	$params->loadArray($data);
		$cache = JFactory::getCache('_system', 'callback');
    	$cache->clean();

		JFHelper::clearImageCache();

		$wmf = $params->get('watermark_file');
		if ($input->getInt('delete_watermark') and $wmf) {
			if (file_exists(FWG_STORAGE_PATH.$wmf)) @unlink(FWG_STORAGE_PATH.$wmf);
			$params->set('watermark_file', '');
			$wmf = '';
		}

    	if ($file = $input->files->get('watermark_file')
    	 and $name = JArrayHelper::getValue($file, 'name')
    	  and empty($file['error']) and preg_match('/\.png$/i', $name)
    	   and move_uploaded_file(JArrayHelper::getValue($file, 'tmp_name'), FWG_STORAGE_PATH.$name)) {
			if ($wmf and $name != $wmf and file_exists(FWG_STORAGE_PATH.$wmf)) {
				@unlink(FWG_STORAGE_PATH.$wmf);
			}
    		$params->set('watermark_file', $name);
    	}

    	$db = JFactory::getDBO();
		$db->setQuery('UPDATE #__update_sites SET extra_query = '.$db->quote('&code='.$params->get('update_code')).' WHERE name = \'FW Gallery Light\'');
		$db->execute();

    	$db->setQuery('UPDATE #__extensions SET params = '.$db->quote($params->toString()).' WHERE `element` = \'com_fwgallerylight\' AND `type` = \'component\'');
    	return $db->query();
    }
	function loadImages() {
		$db = JFactory::getDBO();
		$db->setQuery('SELECT f.id, p.user_id, f.filename, p.name FROM #__fwg_projects AS p, #__fwg_files AS f WHERE f.project_id = p.id AND filename <> \'\'');
		return $db->loadObjectList();
	}
}
