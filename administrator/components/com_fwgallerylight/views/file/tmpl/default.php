<?php
/**
 * FW Gallery Light 3.5.0
 * @copyright (C) 2017 Fastw3b
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.fastw3b.net/ Official website
 **/

defined( '_JEXEC' ) or die( 'Restricted access' );

JToolBarHelper::title(JText::_('FWG_IMAGE').' <small>['.JText::_($this->obj->id?'Edit':'New').']</small>', 'fwgallery-image.png');
JToolBarHelper::apply();
JToolBarHelper::save();
JToolBarHelper::cancel();

JHTML :: _('behavior.formvalidation');
$editor = JFactory::getEditor();
?>
<form action="index.php?option=com_fwgallerylight&amp;view=file" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data" class="form-validate">
    <fieldset class="adminform">
        <legend><?php echo JText::_('FWG_DETAILS'); ?></legend>
        <table class="table">
	        <tr>
	            <td>
	                <?php echo JText::_('FWG_NAME'); ?> :
	            </td>
	            <td>
	                <input id="name" class="required inputbox" type="text" name="name" size="50" maxlength="100" value="<?php echo $this->escape($this->obj->name);?>" />
	            </td>
	        </tr>
	        <tr>
				<td>
					<?php echo JText::_('FWG_AUTHOR'); ?>:
				</td>
				<td>
					<?php echo JHTML::_('select.genericlist', (array)$this->clients, 'user_id', 'class="required"', 'id', 'name', $this->obj->user_id?$this->obj->user_id:$this->user->id); ?>
				</td>
	        </tr>
	        <tr class="fwgallery_image_field">
	            <td>
	                <?php echo JText::_('FWG_GALLERY_DEFAULT'); ?>:
	            </td>
	            <td>
					<fieldset class="radio btn-group">
	                	<?php echo JHTML :: _('select.booleanlist', 'selected', '', $this->obj->selected); ?>
					</fieldset>
	            </td>
	        </tr>
	        <tr>
	            <td>
	                <?php echo JText::_('FWG_PUBLISHED'); ?>:
	            </td>
	            <td>
					<fieldset class="radio btn-group">
	                	<?php echo JHTML :: _('select.booleanlist', 'published', '', $this->obj->published); ?>
					</fieldset>
	            </td>
	        </tr>
	        <tr>
	            <td>
	                <?php echo JText::_('FWG_DATE'); ?>:
	            </td>
	            <td>
	                <?php echo JHTML::_('calendar', substr($this->obj->created?$this->obj->created:date('Y-m-d'), 0, 10), 'created', 'created', '%Y-%m-%d', array('class'=>'inputbox', 'size'=>'25',  'maxlength'=>'19')); ?>
	            </td>
	        </tr>
	        <tr>
	            <td>
	                <?php echo JText::_('FWG_COPYRIGHT'); ?>:
	            </td>
	            <td>
	                <input id="copyright" type="text" name="copyright" size="50" maxlength="100" value="<?php echo $this->escape($this->obj->copyright);?>" />
	            </td>
	        </tr>
	        <tr>
	            <td>
	                <?php echo JText::_('FWG_GALLERY'); ?>:
	            </td>
	            <td>
	                <?php echo JHTML :: _('fwGallerylightCategory.getCategories', 'project_id', $this->obj->project_id, 'class="required"', $multiple=false, $first_option=''); ?>
	            </td>
	        </tr>
	        <tr>
	            <td>
	                <?php echo JText::_('FWG_FILE'); ?>:
	            </td>
	            <td>
                    <p><?php echo JText :: _('FWG_FILE_UPLOAD_SIZE_LIMIT').' '.ini_get('post_max_size'); ?></p>
	                <img src="<?php echo JURI::root(true); ?>/index.php?option=com_fwgallerylight&amp;view=image&amp;layout=image&amp;format=raw&amp;id=<?php echo $this->obj->id; ?>&amp;w=370&amp;h=256&amp;js=1"/><br/>
	                <?php echo JText::_('FWG_FILENAME').':'.$this->obj->filename; ?><br/>
	                <input id="filename" type="file" name="filename"/>
	            </td>
	        </tr>
	        <tr>
	            <td>
	                <?php echo JText::_('FWG_DESCRIPTION'); ?>:
	            </td>
	            <td>
                    <?php echo $editor->display('descr',  $this->obj->descr, '600', '350', '75', '20', false); ?>
	            </td>
	        </tr>
        </table>
    </fieldset>
	<input type="hidden" name="type_id" value="1" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="id" value="<?php echo $this->obj->id; ?>" />
</form>
<script type="text/javascript">
Joomla.submitbutton = function(task) {
	if (task == 'cancel') {
		location = 'index.php?option=com_fwgallerylight&view=files';
		return;
	}
	var form = document.adminForm;
	if ((task == 'apply' || task == 'save') && !document.formvalidator.isValid(form)) {
		alert('<?php echo JText :: _('FWG_NOT_ALL_REQUIRED_FIELDS_FILLED', true); ?>');
	} else {
		form.task.value = task;
		form.submit();
	}
}
</script>
