<?php
/**
 * Default French Lexicon Entries for Cliche
 *
 * @package cliche
 * @subpackage lexicon
 */
/* System settings */
$_lang['setting_cliche.upload_size_limit'] = 'Taille maximale d\'upload';
$_lang['setting_cliche.upload_size_limit_desc'] = 'Taille maximale pour l\'upload de fichier';

$_lang['setting_cliche.upload_allowed_extensions'] = 'Extensions autorisées';
$_lang['setting_cliche.upload_allowed_extensions_desc'] = 'Liste d\'extension autorisée pour l\'upload de fichier séparées par des virgules';

/* TOP MENU */

$_lang['cliche'] = 'Cliche, gestionnaire d\'albums';
$_lang['cliche.menu'] = 'Cliche';
$_lang['cliche.menu_desc'] = 'Un gestionnaire de média pour MODx Revolution.';

$_lang['setting_cliche.album_mgr_panels'] = 'Panneaux de gestion d\'album';
$_lang['setting_cliche.album_mgr_panels_desc'] = 'Liste des encarts à afficher';

/* file uploader */
$_lang['cliche.album_id_error'] = '[Cliche] ID d\'album non indiqué';
$_lang['cliche.already_exist_error'] = '[Cliche] Le fichier existe déjà';
$_lang['cliche.create_temp_dir_error'] = '[Cliche] Impossible de créer le répertoire temporaire';
$_lang['cliche.db_save_item_error'] = '[Cliche] Impossible de sauvegarder "[[+filename]]" dans la base de données';
$_lang['cliche.empty_file_error'] = '[Cliche] Le fichier semble être vide : ';
$_lang['cliche.file_too_large_error'] = '[Cliche] Le fichier est trop volumineux';
$_lang['cliche.increase_post_max_size'] = '[Cliche] La taille maximale d\'upload est fixée à : [[+size]], vous devez augmenter votre directive php "<em>post_max_size</em>" (Actuellement égale à [[+directive]])';
$_lang['cliche.increase_upload_max_filesize'] = '[Cliche] La taille maximale d\'upload est fixée à : [[+size]], vous devez augmenter votre directive php "<em>upload_max_filesize</em>" (Actuellement égale à [[+directive]])';
$_lang['cliche.invalid_extensions_error'] = '[Cliche] Extesion de fichier non valide, seules les extensions suivantes sont acceptées : ';
$_lang['cliche.misc_error'] = '[Cliche] Le fichier n\'a pu être uploadé';
$_lang['cliche.no_file_error'] = '[Cliche] Aucun fichier n\'a été uploadé';
$_lang['cliche.target_dir_error'] = '[Cliche] Impossible de créer le répertoire cible dans : ';
$_lang['cliche.target_dir_write_error'] = '[Cliche] Impossible d\'écrire dans le répertoire : ';
$_lang['cliche.uploadxhr_error'] = '[Cliche] Impossible de charger la classe UploadFileXhr';
$_lang['cliche.uploadfileform_error'] = '[Cliche] Impossible de charger la classe UploadFileForm depuis : ';
$_lang['cliche.image_upload_success_msg'] = 'Image uploadée avec succès';
$_lang['cliche.upload_zip_success'] = 'Archive Zip uploadée avec succès - [[+count]] images crées';
$_lang['cliche.xpdozip_not_found'] = '[Cliche] xPDOZip n\'a pu être chargé';
$_lang['cliche.zip_error_unpack'] = '[Cliche] Erreur lors de la décompression';

/* Processors */
$_lang['cliche.album_created_succesfully'] = 'Album créé avec succès';
$_lang['cliche.album_deleted_successfully'] = 'Album et objets supprimés avec succès';
$_lang['cliche.album_not_found'] = '[Cliche] Album non trouvé<br/>';
$_lang['cliche.album_not_specified'] = '[Cliche] Album non indiqué<br/>';
$_lang['cliche.album_udpated_succesfully'] = 'Album mis à jour avec succès';
$_lang['cliche.error_album_create_name_already_taken'] = 'Le nom de l\'album est déjà utilisé Veuillez en choisir un autre';
$_lang['cliche.error_album_delete_cancelled'] = 'Erreur - L\'album n\'a pu être supprimé. Opération interrompue, veuillez contacter le webmaster';
$_lang['cliche.error_album_delete_no_id'] = 'Erreur - L\'ID de l\'album est soit erronné soit non indiqué';
$_lang['cliche.error_delete_item_aborted'] = 'Erreur - L\'objet n\'a pu être supprimé. Opération interrompue, veuillez contacter le webmaster';
$_lang['cliche.error_delete_item_no_id'] = 'Erreur - L\'ID de l\'objet est soit erronné soit non indiqué';
$_lang['cliche.error_album_not_created'] = 'L\'album n\'a pu être créé';
$_lang['cliche.item_deleted_succesfully'] = 'Objet supprimé avec succès';
$_lang['cliche.item_not_found'] = '[Cliche] Objet non trouvé<br/>';
$_lang['cliche.item_not_specified'] = '[Cliche] Objet non indiqué<br/>';
$_lang['cliche.item_set_as_cover_succesfully'] = 'Image définie comme couverture d\'album avec succès';
$_lang['cliche.no_albums'] = '[Cliche] Il n\'y a aucun album pour le moment<br/>';