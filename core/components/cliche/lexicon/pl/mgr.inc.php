<?php
/**
 * Default Manager Polish Lexicon Entries for Cliche
 *
 * @package cliche
 * @subpackage lexicon
 */
 
$_lang['cliche.main_title'] = 'Cliche';

$_lang['cliche.breadcrumb_album_list_desc'] = '<h3>Lista albumów</h3><p>Wybierz album by wyświetlić jego zawartość.</p>';
$_lang['cliche.breadcrumb_root'] = 'Lista albumów';
$_lang['cliche.breadcrumb_upload_images'] = 'Wgraj zdjęcie'; 

$_lang['cliche.field_album_name_label'] = 'Nazwa albumu (wymagane)';
$_lang['cliche.field_album_desc_label'] = 'Krótki opis';
$_lang['cliche.field_image_name_label'] = 'Nazwa zdjęcia (wymagane)';
$_lang['cliche.field_image_desc_label'] = 'Opis';

$_lang['cliche.album_list_empty_msg'] = '<h4>Nie utworzono jeszcze żadnego albumu.</h4>';
$_lang['cliche.album_list_total_pics'] = '{total} Zdjęć';

$_lang['cliche.album_desc'] = '<div class="album-infos"><tpl if="cover_id == 0">
		<span class="no-preview">Brak podglądu</span>
	</tpl>
	<tpl if="cover_id == 0">
		<span class="no-preview"><span>Brak podglądu</span></span>
	</tpl>
	<tpl if="cover_id">
		<tpl if="thumbnail">
			<img src="{thumbnail}" title="Image Cover for the album {name}" alt="Image Cover for the album {name}" />
		</tpl>
		<tpl if="!thumbnail">
			<span class="no-preview error"><span><strong>Błąd</strong>Zdjęcia nie znaleziono</span></span>
		</tpl>
	</tpl>			
	<div class="album-name"><h3>Album : <span>{name}</span></h3>
		Utworzony <strong>{createdon}</strong> by <strong>{createdby}</strong><br/>
		Album id : #<strong>{id}</strong>
	</div>
	<div class="album-meta">
		<span>{total}</span>
		Zdjęć
	</div></div>
	<tpl if="description">
	</div><div class="album-desc"><p>{description}</p></tpl>';
$_lang['cliche.album_item_cover_alt_msg'] = '{name} podgląd';
$_lang['cliche.album_item_created_by'] = 'Stworzony przez';
$_lang['cliche.album_item_created_on'] = 'Utworzony';
$_lang['cliche.album_item_desc_title'] = 'Opis';
$_lang['cliche.album_item_empty_msg'] = '<h4>W albumie nie ma żadnych zdjęć</h4><p>Aby dodać zdjęcia kliknij "Dodaj zdjęcia"</p>';
$_lang['cliche.album_item_id'] = 'ID';
$_lang['cliche.album_item_informations_title'] = 'Informacje';

$_lang['cliche.btn_add_album'] = 'Dodaj album';
$_lang['cliche.btn_add_photo'] = 'Dodaj zdjęcia';
$_lang['cliche.btn_back_to_album_list'] = 'Powrót do listy albumów';
$_lang['cliche.btn_back_to_album'] = 'Powrót do albumu';
$_lang['cliche.btn_browse'] = 'Przeglądaj...';
$_lang['cliche.btn_delete_album'] = 'Usuń album';
$_lang['cliche.btn_delete_image'] = 'Usuń zdjęcie';
$_lang['cliche.btn_edit_image'] = 'Edytuj zdjęcie';
$_lang['cliche.btn_options'] = 'Opcje';
$_lang['cliche.btn_save_album'] = 'Zapisz album';
$_lang['cliche.btn_save_image'] = 'Zapisz zdjęcie';
$_lang['cliche.btn_set_as_album_cover'] = 'Ustaw jako okładka albumu';
$_lang['cliche.btn_start_upload'] = 'Wgraj zdjęcia';
$_lang['cliche.btn_update_album'] = 'Zaktualizuj zdjęcia';

$_lang['cliche.window_create_a_new_album'] = 'Stwórz nowy album';
$_lang['cliche.window_delete_album'] = 'Usuń album';
$_lang['cliche.window_delete_album_msg'] = 'Wszystkie zdjęcia z albumu zostaną usunięte. Ta operacja jest nieodwracalna.';
$_lang['cliche.window_delete_image'] = 'Usuń zdjęcie';
$_lang['cliche.window_delete_image_msg'] = 'Czy jesteś pewien że chcesz usunąć to zdjęcie? Ta operacja jest nieodwracalna.';
$_lang['cliche.window_edit_image'] = 'Edytuj zdjęcie';
$_lang['cliche.window_edit_image_msg'] = 'Edytuj informacje o zdjęciu';
$_lang['cliche.window_set_as_album_cover'] = 'Ustaw jako okładka albumu';
$_lang['cliche.window_set_as_album_cover_msg'] = 'Czy chcesz ustawić jako okładka albumu?';
$_lang['cliche.window_update_album'] = 'Zaktualizuj album';

$_lang['cliche.upload_cancel_msg'] = 'Anuluj';
$_lang['cliche.upload_desc'] = '<h4>Wybierz pliki z komputera</h4><p>Możesz wrzucić kilka na raz przytrzymując klawisz SHIFT.</p>';
$_lang['cliche.upload_in_progress'] = 'Trwa wrzucanie na serwer...'; 
$_lang['cliche.upload_items_for'] = '<h3>Wrzucanie zdjęć na serwer</h3><p>Wgraj nowe zdjęcia do albumu <strong>'; 
$_lang['cliche.upload_ready_msg'] = 'Pliki gotowe do wgrania :';
$_lang['cliche.upload_successful'] = 'Pliki pomyślnie wgrano na serwer'; 

/* file uploader messages */
$_lang['cliche.album_id_error'] = '[Cliche] Album id not specified';
$_lang['cliche.already_exist_error'] = '[Cliche] File already exist';
$_lang['cliche.create_temp_dir_error'] = '[Cliche] Could not create temp directory';
$_lang['cliche.empty_file_error'] = '[Cliche] File appears to be empty : ';
$_lang['cliche.file_too_large_error'] = '[Cliche] File is too large';
$_lang['cliche.increase_post_max_size'] = '[Cliche] Increase post_max_size and upload_max_filesize to ';
$_lang['cliche.invalid_extensions_error'] = '[Cliche] Invalid file extension, only the following extensions are accepted : ';
$_lang['cliche.misc_error'] = '[Cliche] File could not be uploaded';
$_lang['cliche.no_file_error'] = '[Cliche] No file were uploaded';
$_lang['cliche.target_dir_error'] = '[Cliche] Could not create the target directory in : ';
$_lang['cliche.target_dir_write_error'] = '[Cliche] Could not write to directory: ';
$_lang['cliche.uploadxhr_error'] = '[Cliche] Could not load helper class UploadFileXhr';
$_lang['cliche.uploadfileform_error'] = '[Cliche] Could not load helper class UploadFileForm from: ';
$_lang['cliche.xpdozip_not_found'] = '[Cliche] xPDOZip could not be loaded';
$_lang['cliche.zip_error_unpack'] = '[Cliche] Error while unpacking';

/* Common */
$_lang['cliche.album_empty_col_msg'] = 'Wybierz element by wyświetlić jego opis';
$_lang['cliche.loading'] = '<div class="centered empty-msg">Ładuję...</div>';
$_lang['cliche.no_desc'] = '<em>Brak opisu</em>';
$_lang['cliche.no_preview'] = 'Brak podglądu';
$_lang['cliche.saving_msg'] = 'Zapisywanie, proszę czekać ...';
$_lang['cliche.save_new_order'] = 'Zapisz kolejność';
