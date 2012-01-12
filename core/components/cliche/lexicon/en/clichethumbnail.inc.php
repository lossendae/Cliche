<?php
/**
 * Default English Lexicon Entries for ClicheThumbnail
 *
 * @package cliche
 * @subpackage lexicon
 */
$_lang['cliche.title'] = 'Cliche';
$_lang['cliche.saving_msg'] = 'Saving, please Wait...';
$_lang['cliche.back_to_album_list'] = 'Back to albums list';
$_lang['cliche.back_to_album'] = 'Back to album';
$_lang['cliche.add_photo'] = 'Add Images';

$_lang['cliche.items_empty_msg'] = '<h4>There are no image in this album</h4><p>Add images by clicking the green button "Add Images"</p>';
$_lang['cliche.items_empty_col_msg'] = '<div class="empty-msg">Select an image from the main column to see its description</div>';
$_lang['cliche.album_cover_alt_msg'] = '{name} preview';
$_lang['cliche.view_total_pics'] = 'Total Images';

$_lang['cliche.breadcrumbs_album_msg'] = '<div class="album-desc"><tpl if="cover_id == 0">
		<span class="no-preview">No preview</span>
	</tpl>
	<tpl if="cover_id">
		<img src="{thumbnail}" title="Image Cover for the album {name}" alt="Image Cover for the album {name}" />
	</tpl>
	<div class="album_name"><h3>Album : <span>{name}</span></h3>
		Created on <strong>{createdon}</strong> by <strong>{createdby}</strong><br/>
		Album id : #<strong>{id}</strong>
	</div>
	<div class="album_meta">
		<span>{total}</span>
		Images
	</div></div>';

//Breadcrumbs
$_lang['cliche.breadcrumb_root'] = 'Album list';

//buttons
$_lang['cliche.close_btn'] = 'Cancel';
$_lang['cliche.thumb_select_btn'] = 'Select this image';

//misc
$_lang['cliche.actions'] = 'Actions';
$_lang['cliche.sort_by'] = 'Sort by';
$_lang['cliche.created_by'] = 'Created by';
$_lang['cliche.created_on'] = 'Created on';
$_lang['cliche.desc_title'] = 'Description';
$_lang['cliche.informations_title'] = 'Informations';
$_lang['cliche.no_desc'] = '<em>No description</em>';
$_lang['cliche.no_preview'] = 'No preview';
$_lang['cliche.loading'] = '<div class="centered empty-msg">Loading...</div>';
$_lang['cliche.album-empty-col-msg'] = 'Select an item from the main column to view its description';

/* CARD - UPLOAD ITEMS */

//view
$_lang['cliche.upload_desc'] = '<h4>Select files from your computer</h4><p>You can select several files at a time by holding the shift key.</p>';
$_lang['cliche.upload_ready_msg'] = 'Files ready to be uploaded :';
$_lang['cliche.upload_cancel_msg'] = 'Cancel';

//buttons
$_lang['cliche.browse'] = 'Browse...';
$_lang['cliche.start_upload'] = 'Start Upload';

//msg
$_lang['cliche.breadcrumbs_upload_pictures_msg'] = 'Upload Images'; 
$_lang['cliche.upload_in_progress'] = 'Upload in progress...'; 
$_lang['cliche.upload_items_for'] = '<h3>Images Uploader</h3><p>Upload new images for the album <strong>'; 
$_lang['cliche.upload_successful'] = 'Files uploaded successfully'; 