<?php
/**
 * Default Manager English Lexicon Entries for Cliche
 *
 * @package cliche
 * @subpackage lexicon
 */
 
/* CARD - ALBUMS LIST */

//buttons
$_lang['cliche.add_album_btn'] = 'Create a new album';

//Create or update album window
$_lang['cliche.album_name_label'] = 'Album name (required)';
$_lang['cliche.album_desc_label'] = 'Quick description';
$_lang['cliche.create_album_title'] = 'Your album';
$_lang['cliche.save_album_btn'] = 'Save';

//Error messages from the backend


//view
$_lang['cliche.album-list.empty_msg'] = '<h4>There are no albums created yet</h4><p>Create your first album using the button above, then click the newly created item to view its content.<br/>Use the breadcrumbs to navigate back and forth in the albums (The current green element is not clickable)</p>';
$_lang['cliche.album-empty-col-msg'] = 'Select an item from the main column to view its description';
$_lang['cliche.list.empty_col_msg'] = '<div class="empty-msg">Select an album from the main column to view its description</div>';
$_lang['cliche.main_total_pics'] = '{total} Pictures';

//msg
$_lang['cliche.breadcrumb_album_list_desc'] = 'Albums List.<br/>Select an album to view its content.';


/* CARD - ITEMS LIST */

//View
$_lang['cliche.items_empty_msg'] = '<h4>There are no pictures in this album</h4><p>Add pictures by clicking the button above</p>';
$_lang['cliche.items_empty_col_msg'] = '<div class="empty-msg">Select a picture from the main column to see its description</div>';
$_lang['cliche.album_cover_alt_msg'] = '{name} preview';
$_lang['cliche.view_total_pics'] = 'Total Pictures';
//buttons
$_lang['cliche.add_images'] = 'Add Photos';
$_lang['cliche.update_album'] = 'Update album';
$_lang['cliche.delete_album'] = 'Delete album';

//msg
$_lang['cliche.delete_album_msg'] = 'All pitctures in this album will also be deleted. This operation is irreversible.';
$_lang['cliche.breadcrumbs_album_msg'] = '<div class="album_desc">
	<tpl if="cover_id == 0">
		<span class="no-preview">No preview</span>
	</tpl>
	<tpl if="cover_id">
		<img src="{thumbnail}" title="Image Cover for the album {name}" alt="Image Cover for the album {name}" />
	</tpl>
	<div class="album_name">Album : {name}
		<span>{total} Photos - Created on {createdon} by <b>{createdby}</b></span>
	</div>
	<button class="inline-button green" onclick="Ext.getCmp(\'cliche-album-default\').onaddPhoto(); return false;"/><span class="icon-add-white">&nbsp;</span>Add photo</button>
</div>';

/* CARD - SINGLE ITEM */

//buttons
$_lang['cliche.edit_item'] = 'Edit image';
$_lang['cliche.delete_image'] = 'Delete image';
$_lang['cliche.set_as_album_cover'] = 'Set as Album cover';

//msg
$_lang['cliche.delete_image_title'] = 'Remove picture';
$_lang['cliche.delete_image_msg'] = 'Are you sure you want to delete this image ? This operation is irreversible.';
$_lang['cliche.breadcrumbs_item_msg'] = 'Viewing picture : ';

/* CARD - UPLOAD ITEMS */

//view
$_lang['cliche.upload_desc'] = '<h4>Select files from your computer</h4><p>You can select several files at a time by holding the shift key.</p>';
$_lang['cliche.upload_ready_msg'] = 'Files ready to be uploaded :';
$_lang['cliche.upload_cancel_msg'] = 'Cancel';
$_lang['cliche.upload_success_msg'] = 'Image uploaded sucessfully'; 
$_lang['cliche.upload_fail_msg'] = 'The image could not be saved';

//buttons
$_lang['cliche.browse'] = 'Browse...';
$_lang['cliche.start_upload'] = 'Start Upload';

//msg
$_lang['cliche.breadcrumbs_upload_pictures_msg'] = 'Upload pictures'; 
$_lang['cliche.upload_in_progress'] = 'Upload in progress...'; 
$_lang['cliche.upload_items_for'] = 'Upload Items for the album '; 
$_lang['cliche.upload_successful'] = 'Files uploaded successfully'; 

//NOT USED YET
$_lang['cliche.global_progressbar_loading_item'] = 'Loading item'; 
$_lang['cliche.item_progressbar'] = '% completed...'; 


/* MGR - COMMONS */
//tabs
$_lang['cliche.main_tab'] = 'Manage your albums';
//Breadcrumbs
$_lang['cliche.breadcrumb_root'] = 'Albums list';
//buttons
$_lang['cliche.close_btn'] = 'Cancel';
//misc
$_lang['cliche.actions'] = 'Actions';
$_lang['cliche.sort_by'] = 'Sort by';
$_lang['cliche.created_by'] = 'Created by';
$_lang['cliche.created_on'] = 'Created on';
// $_lang['cliche.sort_by_createdon'] = 'Creation date';
// $_lang['cliche.sort_by_total_pics'] = 'Number of pictures';
// $_lang['cliche.sort_by_alphabetically'] = 'Alphabetically';
$_lang['cliche.desc_title'] = 'Description';
$_lang['cliche.informations_title'] = 'Informations';
$_lang['cliche.no_desc'] = '<em>No description</em>';
$_lang['cliche.no_preview'] = 'No preview';
$_lang['cliche.loading'] = '<div class="centered empty-msg">Loading...</div>';