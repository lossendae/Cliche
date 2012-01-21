<?php
/**
 * Manager Czech Lexicon Entries for Cliche
 *
 * @package cliche
 * @subpackage lexicon
 *
 * @author modxcms.cz
 * @updated 2012-01-20
 */

// $_lang['cliche.main_title'] = 'Cliche';
$_lang['cliche.main_title'] = 'Cliche';

// $_lang['cliche.breadcrumb_album_list_desc'] = '<h3>Album List</h3><p>Select an album to view its contents.</p>';
$_lang['cliche.breadcrumb_album_list_desc'] = '<h3>Seznam alb</h3><p>Klikněte na album pro zobrazení jeho obsahu.</p>';
// $_lang['cliche.breadcrumb_root'] = 'Albums list';
$_lang['cliche.breadcrumb_root'] = 'Seznam alb';
// $_lang['cliche.breadcrumb_upload_images'] = 'Upload Images';
$_lang['cliche.breadcrumb_upload_images'] = 'Nahrání obrázků';

// $_lang['cliche.field_album_name_label'] = 'Album name (required)';
$_lang['cliche.field_album_name_label'] = 'Název alba (povinný)';
// $_lang['cliche.field_album_desc_label'] = 'Quick description';
$_lang['cliche.field_album_desc_label'] = 'Popis alba';
// $_lang['cliche.field_image_name_label'] = 'Image name (required)';
$_lang['cliche.field_image_name_label'] = 'Název obrázku (povinný)';
// $_lang['cliche.field_image_desc_label'] = 'Description';
$_lang['cliche.field_image_desc_label'] = 'Popis';

// $_lang['cliche.album_list_empty_msg'] = '<h4>There are no albums created yet</h4><p>Create your first album using the button above, then click the newly created item to view its content.<br/>Use the breadcrumbs to navigate back and forth in the albums (The current green element is not clickable)</p>';
$_lang['cliche.album_list_empty_msg'] = '<h4>Doposud nebylo vytvořeno žádné album</h4><p>Vytvořte své první album kliknutím na tlačítko "Vytvořit nové album", poté klikněte na nově vytvořené album a nahrajte obrázky.<br/>Pro navigaci vpřed a vzad mezi alby lze využít i drobkovou navigaci (aktuální, zelená položka není klikatelná).</p>';
// $_lang['cliche.album_list_total_pics'] = '{total} Images';
$_lang['cliche.album_list_total_pics'] = '{total} Obrázků';

// $_lang['cliche.album_desc'] = '<div class="album-infos"><tpl if="cover_id == 0">
		// <span class="no-preview">No preview</span>
	// </tpl>
	// <tpl if="cover_id">
		// <img src="{thumbnail}" title="Image Cover for the album {name}" alt="Image Cover for the album {name}" />
	// </tpl>
	// <div class="album-name"><h3>Album : <span>{name}</span></h3>
		// Created on <strong>{createdon}</strong> by <strong>{createdby}</strong><br/>
		// Album id : #<strong>{id}</strong>
	// </div>
	// <div class="album-meta">
		// <span>{total}</span>
		// Images
	// </div></div>
	// <tpl if="description">
	// </div><div class="album-desc"><p>{description}</p></tpl>';
$_lang['cliche.album_desc'] = '<div class="album-infos"><tpl if="cover_id == 0">
		<span class="no-preview">Bez náhledu</span>
	</tpl>
	<tpl if="cover_id">		
		<tpl if="thumbnail">
			<img src="{thumbnail}" title="Titulní obrázek alba {name}" alt="Titulní obrázek alba {name}" />
		</tpl>
		<tpl if="!thumbnail">
			<span class="no-preview error"><strong>Error</strong>Image not found</span>
		</tpl>
	</tpl>
	<div class="album-name"><h3>Album : <span>{name}</span></h3>
		Vytvořeno: <strong>{createdon}</strong>, autor: <strong>{createdby}</strong><br/>
		ID alba : #<strong>{id}</strong>
	</div>
	<div class="album-meta">
		<span>{total}</span>
		Images
	</div></div>
	<tpl if="description">
	</div><div class="album-desc"><p>{description}</p></tpl>';
// $_lang['cliche.album_item_cover_alt_msg'] = '{name} preview';
$_lang['cliche.album_item_cover_alt_msg'] = '{name} náhled';
// $_lang['cliche.album_item_created_by'] = 'Created by';
$_lang['cliche.album_item_created_by'] = 'Autor';
// $_lang['cliche.album_item_created_on'] = 'Created on';
$_lang['cliche.album_item_created_on'] = 'Vytvořeno';
// $_lang['cliche.album_item_desc_title'] = 'Description';
$_lang['cliche.album_item_desc_title'] = 'Popis';
// $_lang['cliche.album_item_empty_msg'] = '<h4>There are no image in this album</h4><p>Add images by clicking the green button "Add Images"</p>';
$_lang['cliche.album_item_empty_msg'] = '<h4>Album neobsahuje žádné obrázky</h4><p>Obrázky přidáte kliknutím na zelené tlačítko "Přidat obrázky"</p>';
// $_lang['cliche.album_item_id'] = 'ID';
$_lang['cliche.album_item_id'] = 'ID';
// $_lang['cliche.album_item_informations_title'] = 'Informations';
$_lang['cliche.album_item_informations_title'] = 'Informace';

// $_lang['cliche.btn_add_album'] = 'Add Album';
$_lang['cliche.btn_add_album'] = 'Přidat album';
// $_lang['cliche.btn_add_photo'] = 'Add Images';
$_lang['cliche.btn_add_photo'] = 'Přidat obrázky';
// $_lang['cliche.btn_back_to_album_list'] = 'Back to albums list';
$_lang['cliche.btn_back_to_album_list'] = 'Zpět na seznam alb';
// $_lang['cliche.btn_back_to_album'] = 'Back to album';
$_lang['cliche.btn_back_to_album'] = 'Zpět do alba';
// $_lang['cliche.btn_browse'] = 'Browse...';
$_lang['cliche.btn_browse'] = 'Vybrat obrázky...';
// $_lang['cliche.btn_delete_album'] = 'Delete album';
$_lang['cliche.btn_delete_album'] = 'Odstranit album';
// $_lang['cliche.btn_delete_image'] = 'Delete image';
$_lang['cliche.btn_delete_image'] = 'Odstranit obrázek';
// $_lang['cliche.btn_edit_image'] = 'Edit image';
$_lang['cliche.btn_edit_image'] = 'Upravit obrázek';
// $_lang['cliche.btn_options'] = 'Options';
$_lang['cliche.btn_options'] = 'Možnosti';
// $_lang['cliche.btn_save_album'] = 'Save Album';
$_lang['cliche.btn_save_album'] = 'Uložit album';
// $_lang['cliche.btn_save_image'] = 'Save Image';
$_lang['cliche.btn_save_image'] = 'Uložit obrázek';
// $_lang['cliche.btn_set_as_album_cover'] = 'Set as Album cover';
$_lang['cliche.btn_set_as_album_cover'] = 'Nastavit obrázek jako titulní';
// $_lang['cliche.btn_start_upload'] = 'Start Upload';
$_lang['cliche.btn_start_upload'] = 'Spustit nahrávání';
// $_lang['cliche.btn_update_album'] = 'Update album';
$_lang['cliche.btn_update_album'] = 'Upravit album';

// $_lang['cliche.window_create_a_new_album'] = 'Create a new Album';
$_lang['cliche.window_create_a_new_album'] = 'Vytvořit nové album';
// $_lang['cliche.window_delete_album'] = 'Delete album';
$_lang['cliche.window_delete_album'] = 'Odstranit album';
// $_lang['cliche.window_delete_album_msg'] = 'All pictures in this album will also be deleted. This operation is irreversible.';
$_lang['cliche.window_delete_album_msg'] = 'Všechny obrázky v tomto albu budou také odstraněny. Tato operace je nevratná.';
// $_lang['cliche.window_delete_image'] = 'Delete image';
$_lang['cliche.window_delete_image'] = 'Odstranit obrázek';
// $_lang['cliche.window_delete_image_msg'] = 'Are you sure you want to delete this image ? This operation is irreversible.';
$_lang['cliche.window_delete_image_msg'] = 'Opravdu chcete odstranit tento obrázek? Toto operace je nevratná.';
// $_lang['cliche.window_edit_image'] = 'Edit Your Image';
$_lang['cliche.window_edit_image'] = 'Upravit obrázek';
// $_lang['cliche.window_edit_image_msg'] = 'Edit the informations for your image';
$_lang['cliche.window_edit_image_msg'] = 'Upravit informace o obrázku';
// $_lang['cliche.window_set_as_album_cover'] = 'Set as Album cover';
$_lang['cliche.window_set_as_album_cover'] = 'Nastavit obrázek jako titulní';
// $_lang['cliche.window_set_as_album_cover_msg'] = 'Do you want to set this image as your album cover ?';
$_lang['cliche.window_set_as_album_cover_msg'] = 'Chcete nastavit tento obrázek jako titulní obrázek alba?';
// $_lang['cliche.window_update_album'] = 'Update Current Album';
$_lang['cliche.window_update_album'] = 'Upravit album';

// $_lang['cliche.upload_cancel_msg'] = 'Cancel';
$_lang['cliche.upload_cancel_msg'] = 'Storno';
// $_lang['cliche.upload_desc'] = '<h4>Select files from your computer</h4><p>You can select several files at a time by holding the shift key.</p>';
$_lang['cliche.upload_desc'] = '<h4>Vyberte obrázky, které chcete nahrát na server kliknutím na tlačítko "Vybrat obrázky..."</h4><p>Pokud chcete vybrat zároveň více obrázku můžete použít přidržení klávesy CTRL (pro výběr obrázků) nebo SHIFT (pro sadu obrázků).</p>';
// $_lang['cliche.upload_in_progress'] = 'Upload in progress...';
$_lang['cliche.upload_in_progress'] = 'Probíhá nahrávání...';
// $_lang['cliche.upload_items_for'] = '<h3>Images Uploader</h3><p>Upload new images for the album <strong>';
$_lang['cliche.upload_items_for'] = '<h3>Nahrávač obrázků</h3><p>Přidávání nových obrázků do alba<strong>';
// $_lang['cliche.upload_ready_msg'] = 'Files ready to be uploaded :';
$_lang['cliche.upload_ready_msg'] = 'Obrázky připravené k nahrání:';
// $_lang['cliche.upload_successful'] = 'Files uploaded successfully';
$_lang['cliche.upload_successful'] = 'Náhrávání obrázků dokončeno';


/* Common */
// $_lang['cliche.album_empty_col_msg'] = 'Select an item from the main column to view its description';
$_lang['cliche.album_empty_col_msg'] = 'Pro zobrazení informací o obrázku klikněte na nějaký obrázek ze seznamu.';
// $_lang['cliche.loading'] = '<div class="centered empty-msg">Loading...</div>';
$_lang['cliche.loading'] = '<div class="centered empty-msg">Načítám...</div>';
// $_lang['cliche.no_desc'] = '<em>No description</em>';
$_lang['cliche.no_desc'] = '<em>Bez popisu</em>';
// $_lang['cliche.no_preview'] = 'No preview';
$_lang['cliche.no_preview'] = 'Bez náhledu';
// $_lang['cliche.saving_msg'] = 'Saving, please Wait...';
$_lang['cliche.saving_msg'] = 'Ukládání, momentíček...';
