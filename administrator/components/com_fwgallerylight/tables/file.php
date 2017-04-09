<?php
/**
 * FW Gallery Light 3.5.0
 * @copyright (C) 2017 Fastw3b
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.fastw3b.net/ Official website
 **/

defined( '_JEXEC' ) or die( 'Restricted access' );

class TableFile extends JTable {
    var $id = null,
    	$ordering = null,
    	$published = null,
    	$project_id = null,
    	$type_id = 1,
    	$user_id = null,
    	$created = null,
    	$name = null,
    	$descr = null,
    	$filename = null,
		$original_filename = null,
    	$selected = null,
    	$hits = null,
    	$longitude = null,
    	$latitude = null,
    	$copyright = null,
		$size = null,
		$height = null,
		$width = null;

    var $_user_id = 0,
    	$_user_name = '',
    	$_type_name = '',
    	$_plugin_name = '',
    	$_is_type_published = '',
    	$_gallery_name = '',
    	$_is_gallery_published = '';

    function __construct(&$db) {
        parent::__construct('#__fwg_files', 'id', $db);
    }

    function load($oid = NULL, $reset = true) {
        if ($oid and is_numeric($oid)) {
        	$db = JFactory::getDBO();
        	$user = JFactory::getUser();
	        $params = JComponentHelper::getParams('com_fwgallerylight');
	        $app = JFactory::getApplication();

        	$db->setQuery('
SELECT
	f.*,
	u.name AS _user_name,
	p.user_id AS _user_id,
	p.name AS _gallery_name,
	p.published AS _is_gallery_published,
	t.name AS _type_name,
	t.plugin AS _plugin_name,
	t.published AS _is_type_published
FROM
	#__fwg_files AS f
	LEFT JOIN #__users AS u ON u.id = f.user_id
	LEFT JOIN #__fwg_projects AS p ON p.id = f.project_id
	LEFT JOIN #__fwg_types AS t ON t.id = f.type_id
    LEFT JOIN #__usergroups AS pg ON pg.id = p.gid
WHERE
	'.(($app->isSite() and !$user->authorise('core.login.admin'))?('(
		p.gid = 0
		OR
		p.gid IS NULL
'.($user->id?('
		OR
		EXISTS(SELECT * FROM #__usergroups AS ug WHERE pg.lft=ug.lft AND ug.id IN ('.implode(',',$user->getAuthorisedGroups()).'))'):'').'
	)
	AND
	'):'').'
	f.id = '.(int)$oid
			);
			if ($obj = $db->loadObject()) {
				foreach ($obj as $key=>$val) $this->$key = $val;
	            return true;
			} else $this->setError(JText::_('FWG_FILE_NOT_FOUND'));
        }
    }

    function clockwise($cid) {
    	if ($cid) foreach ($cid as $id) if ($this->load((int)$id) and JFHelper::isFileExists($this)) {
			JFHelper::clearImageCache($id);
            $img_path = FWG_STORAGE_PATH.'files'.'/'.$this->_user_id.'/';
    		GPMiniImg::rotate($img_path, $this->original_filename, 270);
    	}
    }

    function counterclockwise($cid) {
    	if ($cid) foreach ($cid as $id) if ($this->load((int)$id) and JFHelper::isFileExists($this)) {
			JFHelper::clearImageCache($id);
            $img_path = FWG_STORAGE_PATH.'files'.'/'.$this->_user_id.'/';
    		GPMiniImg::rotate($img_path, $this->original_filename, 90);
    	}
    }

    function select($cid) {
        if ($id = JArrayHelper::getValue($cid, 0)) {
            if ($this->load($id)) {
		    	$db = JFactory::getDBO();
                $db->setQuery('UPDATE #__fwg_files SET selected = 0 WHERE project_id = '.(int)$this->project_id);
                $db->query();

                $this->selected = 1;
                return $this->store();
            }
        }
    }

    function unselect($cid) {
        if ($id = JArrayHelper::getValue($cid, 0)) {
            if ($this->load($id)) {
                $this->selected = 0;
                return $this->store();
            }
        }
        return false;
    }

    function check() {
		if (!$this->project_id) {
			$this->setError(JText::_('FWG_ERROR_SELECT_GALLERY'));
			return false;
		}
		jimport('joomla.filesystem.file');
		$db = JFactory::getDBO();

		$db->setQuery('
SELECT
	(SELECT p.user_id FROM #__fwg_projects AS p WHERE p.id = '.(int)$this->project_id.') AS new_user_id,
	(SELECT p.user_id FROM #__fwg_files AS f, #__fwg_projects AS p WHERE p.id = f.project_id AND f.id = '.(int)$this->id.') AS old_user_id');
		$user_data = $db->loadObject();
		$img_path = FWG_STORAGE_PATH.'files'.'/'.$user_data->new_user_id.'/';
/* create destination folder if needed */
		if (!file_exists($img_path)) JFile::write($img_path.'index.html', $html='<html><body></body></html>');

/* check project change */
		if ($this->original_filename and $user_data->new_user_id and $user_data->old_user_id and $user_data->new_user_id != $user_data->old_user_id) {
			$src_img_path = FWG_STORAGE_PATH.'files'.'/'.$user_data->old_user_id.'/';
			$error_moving = false;
/* move image files */
			if (file_exists($src_img_path.$this->original_filename) and !JFile::move($src_img_path.$this->original_filename, $img_path.$this->original_filename)) {
				$this->setError(JText::_('FWG_ERROR_MOVING_FILES'));
				return false;
			}
		}

		if (!$this->id) {
/* storing user_id */
			if (!$this->user_id) {
				$user = JFactory::getUser();
				$this->user_id = $user->id;
			}
			if (!$this->ordering) {
				$db->setQuery('SELECT MAX(ordering) FROM #__fwg_files AS f WHERE f.project_id = '.(int)$this->project_id);
				$this->ordering = (int)$db->loadResult()+1;
			}
		}
/* checking selected */
		$input = JFactory::getApplication()->input;
		$this->selected = $input->getInt('selected');
		if ($this->selected) {
			$db->setQuery('UPDATE #__fwg_files SET selected = 0 WHERE project_id = '.(int)$this->project_id);
			$db->query();
		}

/* file upload */
		ini_set('memory_limit', '512M');
		$filename = $input->files->get('filename');
		if ($filename and $original_filename = GPMiniImg::imageProcessing('filename', $img_path)) {
/* Delete previous image file */
			if ($this->original_filename and file_exists($img_path.$this->original_filename)) {
				JFile::delete($img_path.$this->original_filename);
			}

			$this->filename = empty($filename['name'])?'':$filename['name'];
			$this->original_filename = $original_filename;
			$this->size = filesize($img_path.$this->original_filename);
			$data = (array)getimagesize($img_path.$this->original_filename);
			$this->width = JArrayHelper::getValue($data, 0);
			$this->height = JArrayHelper::getValue($data, 1);

			if (function_exists('exif_read_data') and $exif = @exif_read_data($img_path.$this->original_filename)) {
/* copyright */
				if ($copyright = JArrayHelper::getValue($exif, 'Copyright')) $this->copyright = $copyright;
				if ($date = JArrayHelper::getValue($exif, 'DateTime')) $this->created = date('Y-m-d H:i:s', strtotime($date));
			}
		}
		return true;
    }

    function delete($oid = null) {
    	if ($this->load($oid)) {
	        if (parent::delete($oid)) {
	            if ($this->_user_id) {
					jimport('joomla.filesystem.file');
	                $path = FWG_STORAGE_PATH.'files'.'/'.$this->_user_id.'/';
	                if ($this->original_filename and file_exists($path.$this->original_filename)) {
	                    JFile::delete($path.$this->original_filename);
	                }
	            }
				JFHelper::clearImageCache($this->id);
	            return true;
	        }
    	}
    }
}
