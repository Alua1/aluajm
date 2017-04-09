<?php
/**
 * FW Gallery Light 3.5.0
 * @copyright (C) 2017 Fastw3b
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.fastw3b.net/ Official website
 **/

defined('_JEXEC') or die('Restricted access');

$ret_link = JRoute::_('index.php?option=com_fwgallerylight&view=gallery&id='.$this->row->project_id.':'.JFilterOutput :: stringURLSafe($this->row->_gallery_name));
$next_link = $this->next_image?JRoute :: _('index.php?option=com_fwgallerylight&view=image&id='.$this->next_image->id.':'.JFilterOutput :: stringURLSafe($this->next_image->name)):'';
$prev_link = $this->previous_image?JRoute :: _('index.php?option=com_fwgallerylight&view=image&id='.$this->previous_image->id.':'.JFilterOutput :: stringURLSafe($this->previous_image->name)):'';
?>
<div id="fwgallery" class="fwg-single-item">
	<div class="row fwg-toolbar">
	    <div class="col-sm-4 text-left">
<?php
if ($prev_link) {
?>
			<a href="<?php echo $prev_link; ?>" class="fwg-prev-post"><?php echo JText::_('FWG_PREVIOUS_IMAGE'); ?></a>
<?php
} else {
?>
			<a href="javascript:" class="fwg-prev-post disabled"><?php echo JText::_('FWG_PREVIOUS_IMAGE'); ?></a>
<?php
}
?>
		</div>
	    <div class="col-sm-4 text-center">
                <a href="<?php echo $ret_link; ?>" class="fwg-back2gallery">
                    <?php echo JText :: sprintf('FWG_RETURN_TO_THE_GALLERY', $this->row->_gallery_name); ?></a>
            </div>
	    <div class="col-sm-4 text-right">
<?php
if ($next_link) {
?>
			<a href="<?php echo $next_link; ?>" class="fwg-next-post"><?php echo JText::_('FWG_NEXT_IMAGE'); ?></a>
<?php
} else {
?>
			<a href="javascript:" class="fwg-next-post disabled"><?php echo JText::_('FWG_NEXT_IMAGE'); ?></a>
<?php
}
?>
		</div>
	</div>
	<div class="row fwg-single-item-wrapper">
        <div class="col-sm-8">
			<figure class="image-fit">
				<img src="<?php echo JRoute::_('index.php?option=com_fwgallerylight&view=image&layout=image&format=raw&id='.$this->row->id); ?>" alt="<?php echo JFHelper :: escape($this->row->name); ?>" />
			</figure>
		</div>
    	<div class="col-sm-4 fwg-image-info">
<?php
if ($this->params->get('display_name_image')) {
?>
        	<h4><?php echo $this->row->name; ?></h4>
<?php
}
if ($this->params->get('display_owner_image') and $this->row->_user_name) {
?>
        	<p><?php echo $this->row->_user_name; ?></p>
<?php
}
if ($this->params->get('display_user_copyright') and $this->row->copyright) {
?>
        	<p><?php echo $this->row->copyright; ?></p>
<?php
}
if ($this->params->get('display_date_image') and $date = JFHelper::encodeDate($this->row->created)) {
?>
        	<p><?php echo $date; ?></p>
<?php
}
if ($this->params->get('display_descr_image') and $this->row->descr) {
?>
        	<p><?php echo $this->row->descr; ?></p>
<?php
}
if ($this->params->get('display_social_sharing')) {
	$share_name = urlencode(JText::sprintf('FWG_SHARE_IMAGE_NAME', $this->row->name, $this->row->_project_name));
	$share_link = urlencode(JURI::getInstance()->toString());
	$share_img = urlencode(JURI::root(false).JFHelper::getFileFilename($this->row));
?>
	        <div class="fwg-social">
	            <h6><?php echo JText::_('FWG_SHARE'); ?></h6>
                    <a target="_blank" href="https://twitter.com/intent/tweet?url=<?php echo $share_link; ?>&amp;text=<?php echo $share_name; ?>"><i class="fa fa-twitter"></i></a>
                    <a target="_blank" href="https://www.facebook.com/sharer.php?u=<?php echo $share_link; ?>"><i class="fa fa-facebook"></i></a>
                    <a target="_blank" href="https://plus.google.com/share?url=<?php echo $share_link; ?>"><i class="fa fa-google-plus"></i></a>
                    <a target="_blank" href="https://pinterest.com/pin/create/bookmarklet/?media=<?php echo $share_img; ?>&amp;url=<?php echo $share_link; ?>&amp;description=<?php echo $share_name; ?>"><i class="fa fa-pinterest"></i></a>
                    <a target="_blank" href="https://www.tumblr.com/widgets/share/tool?canonicalUrl=<?php echo $share_link; ?>&amp;title=<?php echo $share_link; ?>"><i class="fa fa-tumblr"></i></a>
                </div>
<?php
}
?>
	    </div>
	</div>

</div>
