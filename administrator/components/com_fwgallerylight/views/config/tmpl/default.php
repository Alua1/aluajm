<?php
/**
 * FW Gallery Light 3.5.0
 * @copyright (C) 2017 Fastw3b
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.fastw3b.net/ Official website
 **/

defined( '_JEXEC' ) or die( 'Restricted access' );
JToolBarHelper :: title(JText::_('FWG_CONFIG'), 'fwgallery-config.png' );
JToolBarHelper :: custom('edit', 'edit', 'edit', 'edit', false);

?>
<strong><?php echo JText :: _('FWG_CONFIGURATION_HINT'); ?></strong>
<?php
if (!file_exists(FWG_STORAGE_PATH) and !is_writable(JPATH_SITE.'/images')) {
?>
<p style="color:#f00;"><?php echo JText :: sprintf('FWG_IMAGES_FOLDER_NOT_WRITABLE', JPATH_SITE.'/images') ?></p>
<?php
}
if (file_exists(FWG_STORAGE_PATH) and !is_writable(FWG_STORAGE_PATH)) {
?>
<p style="color:#f00;"><?php echo JText :: sprintf('FWG_IMAGES_FOLDER_NOT_WRITABLE', FWG_STORAGE_PATH) ?></p>
<?php
}
if (!function_exists('exif_read_data')) {
?>
<p style="color:#f00;"><?php echo JText :: _('FWG_EXIF_DOES_NOT_ENABLED') ?></p>
<?php
}
?>
<div class="span4">
	<fieldset class="adminform">
		<legend><?php echo JText::_('FWG_LAYOUT_SETTINGS'); ?></legend>
		<table class="table">
			<tr>
				<td>
					<?php echo JText::_('FWG_GALLERIES_IN_A_ROW'); ?>:
				</td>
				<td>
					<?php echo $this->obj->params->get('galleries_a_row'); ?>
				</td>
			</tr>

			<tr>
				<td>
					<?php echo JText::_('FWG_GALLERIES_ROWS_PER_PAGE'); ?>:
				</td>
				<td>
					<?php echo $this->obj->params->get('galleries_rows', 4); ?>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo JText::_('FWG_DEFAULT_GALLERIES_ORDERING'); ?>:
				</td>
				<td>
					<?php echo JText :: _('FWG_ORDERING_'.$this->obj->params->get('ordering_galleries', 'order')); ?>
				</td>
			</tr>

			<tr>
				<td>
					<?php echo JText::_('FWG_IMAGES_IN_A_ROW'); ?>:
				</td>
				<td>
					<?php echo $this->obj->params->get('images_a_row'); ?>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo JText::_('FWG_IMAGES_ROWS_PER_PAGE'); ?>:
				</td>
				<td>
					<?php echo $this->obj->params->get('images_rows', 4); ?>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo JText::_('FWG_DEFAULT_IMAGES_ORDERING'); ?>:
				</td>
				<td>
					<?php echo JText :: _('FWG_ORDERING_'.$this->obj->params->get('ordering_images', 'order')); ?>
				</td>
			</tr>
		</table>
	</fieldset>
	<fieldset class="adminform">
		<legend><?php echo JText::_('FWG_WATERMARK'); ?></legend>
		<table class="table">
			<tr>
				<td>
					<?php echo JText::_('FWG_USE_WATERMARK'); ?>:
				</td>
				<td>
					<?php echo JText :: _($this->obj->params->get('use_watermark')?'FWG_Yes':'FWG_No'); ?>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo JText::_('FWG_WATERMARK_POSITION'); ?>:
				</td>
				<td>
					<?php echo JText :: _($this->obj->params->get('watermark_position', 'left bottom')); ?>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo JText::_('FWG_WATERMARK_FILE'); ?>:
				</td>
				<td>
<?php
if ($this->obj->params->get('watermark_file')) {
	if ($path = JFHelper :: getWatermarkFilename()) {
?>
					<img src="<?php echo JURI :: root(true); ?>/<?php echo $path; ?>" /><br/>
<?php
	} else {
?>
					<p style="color:#f00;"><?php echo JText :: _('FWG_WATERMARK_FILE_NOT_FOUND_'); ?></p>
<?php
	}
}
?>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo JText::_('FWG_WATERMARK_TEXT'); ?>:
				</td>
				<td>
					<?php echo $this->obj->params->get('watermark_text'); ?>
				</td>
			</tr>
		</table>
	</fieldset>
</div>

<div class="span4">
	<fieldset class="adminform">
		<legend><?php echo JText::_('FWG_DISPLAYING_SETTINGS'); ?></legend>
		<table class="table">
			<tr>
				<td>
					<?php echo JText::_('FWG_DISPLAY_TOTAL_GALLERIES_COUNTER'); ?>:
				</td>
				<td>
					<?php echo JText :: _($this->obj->params->get('display_total_galleries')?'FWG_Yes':'FWG_No'); ?>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo JText::_('FWG_DISPLAY_EMPTY_GALLERIES'); ?>:
				</td>
				<td>
					<?php echo JText :: _($this->obj->params->get('display_empty_gallery')?'FWG_Yes':'FWG_No'); ?>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo JText::_('FWG_DISPLAY_GALLERY_NAME'); ?>:
				</td>
				<td>
					<?php echo JText :: _($this->obj->params->get('display_name_gallery')?'FWG_Yes':'FWG_No'); ?>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo JText::_('FWG_DISPLAY_GALLERY_OWNER_NAME'); ?>:
				</td>
				<td>
					<?php echo JText :: _($this->obj->params->get('display_owner_gallery')?'FWG_Yes':'FWG_No'); ?>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo JText::_('FWG_DISPLAY_GALLERY_DATE'); ?>:
				</td>
				<td>
					<?php echo JText :: _($this->obj->params->get('display_date_gallery')?'FWG_Yes':'FWG_No'); ?>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo JText::_('FWG_DISPLAY_GALLERY_DESCR'); ?>:
				</td>
				<td>
					<?php echo JText :: _($this->obj->params->get('display_descr_gallery')?'FWG_Yes':'FWG_No'); ?>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo JText::_('FWG_DISPLAY_ORDER_BY_OPTION'); ?>:
				</td>
				<td>
					<?php echo JText :: _($this->obj->params->get('display_gallery_sorting')?'FWG_Yes':'FWG_No'); ?>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo JText::_('FWG_DISPLAY_IMAGE_NAME'); ?>:
				</td>
				<td>
					<?php echo JText :: _($this->obj->params->get('display_name_image')?'FWG_Yes':'FWG_No'); ?>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo JText::_('FWG_DISPLAY_IMAGE_OWNER_NAME'); ?>:
				</td>
				<td>
					<?php echo JText :: _($this->obj->params->get('display_owner_image')?'FWG_Yes':'FWG_No'); ?>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo JText::_('FWG_DISPLAY_IMAGE_DESCR'); ?>:
				</td>
				<td>
					<?php echo JText :: _($this->obj->params->get('display_descr_image')?'FWG_Yes':'FWG_No'); ?>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo JText::_('FWG_DISPLAY_IMAGE_DATE'); ?>:
				</td>
				<td>
					<?php echo JText :: _($this->obj->params->get('display_date_image')?'FWG_Yes':'FWG_No'); ?>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo JText::_('FWG_DISPLAY_IMAGE_VIEWS'); ?>:
				</td>
				<td>
					<?php echo JText :: _($this->obj->params->get('display_image_views')?'FWG_Yes':'FWG_No'); ?>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo JText::_('FWG_DISPLAY_USERS_COPYRIGHT'); ?>:
				</td>
				<td>
					<?php echo JText :: _($this->obj->params->get('display_user_copyright')?'FWG_Yes':'FWG_No'); ?>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo JText::_('FWG_DATE_FORMAT'); ?>:
				</td>
				<td>
					<?php echo $this->obj->params->get('date_format'); ?>
					&nbsp;<a href="http://docs.joomla.org/How_do_you_change_the_date_format%3F" target="_blank"><?php echo JText :: _('FWG_DATE_OPTIONS'); ?></a>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo JText::_('FWG_DISPLAY_SOCIAL_SHARING'); ?>:
				</td>
				<td>
					<?php echo JText :: _($this->obj->params->get('display_social_sharing')?'FWG_Yes':'FWG_No'); ?>
				</td>
			</tr>
		</table>
	</fieldset>
	<fieldset class="adminform">
		<legend><?php echo JText::_('FWG_JOOMLA_UPDATE'); ?></legend>
		<table class="table">
			<tr>
				<td>
					<?php echo JText::_('FWG_UPDATE_ACCESS_CODE'); ?>:
				</td>
				<td>
					<?php echo $this->obj->params->get('update_code'); ?>
				</td>
			</tr>
		</table>
	</fieldset>
</div>
<div class="span4">
	<fieldset class="adminform">
		<legend><?php echo JText::_('FWG_FW_GALLERY'); ?></legend>
		<img style="margin: 0 30px 15px 0; float: left;" src="components/com_fwgallerylight/assets/images/fw_gallery_light.png" />
		<div><strong>Current component version:</strong> 4.0.1</div>
	    <div><strong>Release date:</strong> 02 Feb 2017</div>
		<div><strong>Tested on</strong>: Joomla 3.6.5</div>
		<div><strong>License</strong>: <a href="https://www.gnu.org/licenses/" target="_blank">GPLv3</a></div>
		<p>&nbsp;</p>
		<p>Beautiful and simple Joomla gallery extension with responsive design and social sharing options.</p>
		<div style="clear:both;"></div>
		<p><strong>What to do next</strong></p>
		<ol>
		<li>Check Configuration section to make sure settings match your desired configuration.</li>
		<li>Create a gallery in Galleries section.</li>
		<li>Add images to a gallery in Images section.</li>
		<li>Add a menu item with one of the gallery layouts to view gallery on front-end.</li>
		<li>Check documentation in <a href="http://fastw3b.net/client-section?layout=extensions" target="_blank">My Extensions section of Fastw3b</a> website if you couldn't get it to work.</li>
		<li><a href="http://fastw3b.net/client-section?layout=support" target="_blank">Suggest your idea here</a> if you feel like plugin usage can be simplified or better explained.</li>
		<li><a href="http://fastw3b.net/client-section?layout=support" target="_blank">Report a bug here</a> if you think something must be working and it doesn't.</li>
		<li><a href="https://extensions.joomla.org/extensions/extension/photos-a-images/galleries/fw-gallery
		" target="_blank">Leave a positive review on JED</a> if your experience was pleasant and you want to share it with others.</li>
		</ol>
		<p><strong>Useful links</strong></p>
		<div><img src="components/com_fwgallerylight/assets/images/bullet-green.png" style="margin-right: 5px; vertical-align: bottom;" /><a href="https://fastw3b.net/joomla-extensions/gallery" title="Joomla Gallery Page" target="_blank">FW Gallery page</a> on fastw3b.net.</div>
		<div><img src="components/com_fwgallerylight/assets/images/bullet-green.png" style="margin-right: 5px; vertical-align: bottom;" />Fastw3b <a href="http://fastw3b.net/client-section" title="Fasw3b Profile Page" target="_blank">Profile page</a> - check your membership status and billing info.</div>
		<div><img src="components/com_fwgallerylight/assets/images/bullet-green.png" style="margin-right: 5px; vertical-align: bottom;" /><a href="http://fastw3b.net/client-section?view=user&layout=transactions&id=8" title="Transactions Page" target="_blank">Transactions page</a> - check current status of your recent transactions.</div>
		<div><img src="components/com_fwgallerylight/assets/images/bullet-green.png" style="margin-right: 5px; vertical-align: bottom;" />Follow us on Twitter and Facebook to know latest news and updates.</div>
	</fieldset>
</div>
<div style="clear:both;"></div>
<form action="index.php?option=com_fwgallerylight&amp;view=config" method="post" name="adminForm" id="adminForm">
	<input type="hidden" name="task" value="" />
</form>
