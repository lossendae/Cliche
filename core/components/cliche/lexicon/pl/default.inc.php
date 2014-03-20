<?php
/**
 * Default English Lexicon Entries for Cliche
 *
 * @package cliche
 * @subpackage lexicon
 */
/* System settings */
$_lang['setting_cliche.upload_size_limit'] = 'Upload size limit';
$_lang['setting_cliche.upload_size_limit_desc'] = 'Maximum file size for file upload';

$_lang['setting_cliche.upload_allowed_extensions'] = 'Allowed Extensions';
$_lang['setting_cliche.upload_allowed_extensions_desc'] = 'Comma separated list of extension allowed for file upload';
 
/* Top Menu */

$_lang['cliche'] = 'Cliche, Menadżer galerii zdjęć ';
$_lang['cliche.menu'] = 'Cliche';
$_lang['cliche.menu_desc'] = 'Menadżer galerii zdjęć dla systemu MODx Revolution.';

$_lang['setting_cliche.album_mgr_panels'] = 'Panele menadżera';
$_lang['setting_cliche.album_mgr_panels_desc'] = 'Lista paneli do załadowania';

/* file uploader */
$_lang['cliche.album_id_error'] = '[Cliche] Nie określono ID albumu';
$_lang['cliche.already_exist_error'] = '[Cliche] Plik już istnieje';
$_lang['cliche.create_temp_dir_error'] = '[Cliche] Nie mogę utworzyć tymczasowego katalogu';
$_lang['cliche.db_save_item_error'] = '[Cliche] Nie mogę zapisać "[[+filename]]" w bazie danych';
$_lang['cliche.empty_file_error'] = '[Cliche] Plik wygląda na pusty lub uszkodzony : ';
$_lang['cliche.file_too_large_error'] = '[Cliche] Plik jest zbyt duży';
$_lang['cliche.increase_post_max_size'] = '[Cliche] Your upload size limit is set to : [[+size]], you need to increase your php directive "<em>post_max_size</em>" (currently set to [[+directive]])';
$_lang['cliche.increase_upload_max_filesize'] = '[Cliche] Your upload size limit is set to : [[+size]], you need to increase your php directive "<em>upload_max_filesize</em>" (currently set to [[+directive]])';
$_lang['cliche.invalid_extensions_error'] = '[Cliche] Invalid file extension, only the following extensions are accepted : ';
$_lang['cliche.misc_error'] = '[Cliche] Plik nie może zostać wgrany';
$_lang['cliche.no_file_error'] = '[Cliche] Żadne pliki nie zostały wgrane';
$_lang['cliche.target_dir_error'] = '[Cliche] Could not create the target directory in : ';
$_lang['cliche.target_dir_write_error'] = '[Cliche] Could not write to directory: ';
$_lang['cliche.uploadxhr_error'] = '[Cliche] Could not load helper class UploadFileXhr';
$_lang['cliche.uploadfileform_error'] = '[Cliche] Could not load helper class UploadFileForm from: ';
$_lang['cliche.image_upload_success_msg'] = 'Zdjęcie wgrano poyślnie'; 
$_lang['cliche.upload_zip_success'] = 'Zip file uploaded successfully - [[+count]] pictures created';
$_lang['cliche.xpdozip_not_found'] = '[Cliche] xPDOZip could not be loaded';
$_lang['cliche.zip_error_unpack'] = '[Cliche] Error while unpacking';

/* Processors */
$_lang['cliche.album_created_succesfully'] = 'Pomyślnie utworzono album';
$_lang['cliche.album_deleted_successfully'] = 'Pomyślnie usunięto album wraz ze zdjęciami';
$_lang['cliche.album_not_found'] = '[Cliche] Nie znaleziono albumu<br/>';
$_lang['cliche.album_not_specified'] = '[Cliche] Nie określono albumu<br/>';
$_lang['cliche.album_udpated_succesfully'] = 'Album zaktualizowany';
$_lang['cliche.error_album_create_name_already_taken'] = 'Album o tej nazwie już istnieje. Wybierz inną nazwę';
$_lang['cliche.error_album_delete_cancelled'] = 'Error - The album could not be removed. Operation aborted, please contact the webmaster';
$_lang['cliche.error_album_delete_no_id'] = 'Error - The album id was either false or not supplied';
$_lang['cliche.error_delete_item_aborted'] = 'Error - The item could not be removed - Operation aborted, please contact the webmaster';
$_lang['cliche.error_delete_item_no_id'] = 'Error - The item id was either false or not supplied';
$_lang['cliche.error_album_not_created'] = 'Nie można utworzyć albumu';
$_lang['cliche.item_deleted_succesfully'] = 'Element pomyślnie usunięto';
$_lang['cliche.item_not_found'] = '[Cliche] Elementu nie znaleziono<br/>';
$_lang['cliche.item_not_specified'] = '[Cliche] Nie określono elementu<br/>';
$_lang['cliche.item_set_as_cover_succesfully'] = 'Pomyślnie ustawiono zdjęcie jako okładkę albumu';
$_lang['cliche.no_albums'] = '[Cliche] Nie utworzono jeszcze żadnych albumów<br/>';
