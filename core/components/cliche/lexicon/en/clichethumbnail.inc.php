<?php
/**
 * Default English Lexicon Entries for ClicheThumbnail
 *
 * @package cliche
 * @subpackage lexicon
 */

$_lang['clichethumbnail.main_default_text'] = '<h4>Click the button above to select an image</h4>The preview of the selected image will replace this text';
$_lang['clichethumbnail.main_your_preview'] = 'Thumbnail preview';

$_lang['clichethumbnail.window_title'] = 'Thumbnail Manager';

$_lang['clichethumbnail.breadcrumb_album'] = 'Album browser';
$_lang['clichethumbnail.breadcrumb_crop'] = 'Crop Image';
$_lang['clichethumbnail.breadcrumb_crop_desc'] = '<h3>Cropping Tool</h3><p>Use the left image (original size) to adjust the selection to the right thumbnail (thumbnail preview)</p>';
$_lang['clichethumbnail.breadcrumb_root'] = 'Album list';
$_lang['clichethumbnail.breadcrumb_root_desc'] = '<h3>Thumbnail Preview</h3><p>Select an image from the dedicated album</p>';
$_lang['clichethumbnail.breadcrumb_root_desc_with_thumb'] = '<h3>Thumbnail Preview</h3><p>You can select a specific part of the image by clicking on the button "Crop Image"</p>';
$_lang['clichethumbnail.breadcrumb_upload'] = 'Add images';

$_lang['clichethumbnail.btn_add_photo'] = 'Add Images';
$_lang['clichethumbnail.btn_back_to_album'] = 'Back to album';
$_lang['clichethumbnail.btn_back_to_main'] = 'Back to main panel';
$_lang['clichethumbnail.btn_browse'] = 'Browse...';
$_lang['clichethumbnail.btn_browse_album'] = 'Browse album';
$_lang['clichethumbnail.btn_crop_thumbnail'] = 'Crop Image';
$_lang['clichethumbnail.btn_crop_validate'] = 'Commit Changes';
$_lang['clichethumbnail.btn_replace_thumbnail'] = 'Select another image';
$_lang['clichethumbnail.btn_remove_thumbnail'] = 'Reset';
$_lang['clichethumbnail.btn_select_image'] = 'Select this image';
$_lang['clichethumbnail.btn_start_upload'] = 'Start Upload';

$_lang['clichethumbnail.main_empty_msg'] = '<h4>There is no thumbnail set yet for this document</p>';

$_lang['clichethumbnail.album_desc'] = '<div class="album-infos"><tpl if="cover_id == 0">
		<span class="no-preview">No preview</span>
	</tpl>
	<tpl if="cover_id">
		<tpl if="thumbnail">
			<img src="{thumbnail}" title="Image Cover for the album {name}" alt="Image Cover for the album {name}" />
		</tpl>
		<tpl if="!thumbnail">
			<span class="no-preview error"><strong>Error</strong>Image not found</span>
		</tpl>
	</tpl>
	<div class="album-name"><h3>Album : <span>{name}</span></h3>
		Created on <strong>{createdon}</strong> by <strong>{createdby}</strong><br/>
		Album id : #<strong>{id}</strong>
	</div>
	<div class="album-meta">
		<span>{total}</span>
		Images
	</div></div>
	<tpl if="description">
	</div>
	<div class="album-desc">
		<p class="ct_info">This album is reserved for clichethumbnail. You cannot edit or remove it from this panel.<br/> 
		However you can still upload pictures to be used for the template variable attached.
		</p>
	</tpl>';
$_lang['clichethumbnail.album_empty_msg'] = '<h4>There are no images in this album</h4><p>Upload images by clicking on "Add images"</p>';
$_lang['clichethumbnail.album_loading'] = '<div class="centered empty-msg">Loading...</div>';

$_lang['clichethumbnail.image_cover_alt_msg'] = '{name} preview';
$_lang['clichethumbnail.image_created_by'] = 'Created by';
$_lang['clichethumbnail.image_created_on'] = 'Created on';
$_lang['clichethumbnail.image_desc_title'] = 'Description';
$_lang['clichethumbnail.image_informations_title'] = 'Informations';
$_lang['cliche.image_unselected_msg'] = '<div class="empty-msg">Select an image from the main column to see its description</div>';
$_lang['clichethumbnail.image_no_desc'] = '<em>No description</em>';
$_lang['clichethumbnail.image_no_preview'] = 'No preview';

$_lang['clichethumbnail.upload_cancel'] = 'Cancel';
$_lang['clichethumbnail.upload_desc'] = '<h4>Select files from your computer</h4><p>You can select several files at a time by holding the shift key.</p>';
$_lang['clichethumbnail.upload_in_progress'] = 'Upload in progress...'; 
$_lang['clichethumbnail.upload_ready_msg'] = 'Files ready to be uploaded :';

$_lang['clichethumbnail.cropper_empty_msg'] = '<h4>Loading...</p>';