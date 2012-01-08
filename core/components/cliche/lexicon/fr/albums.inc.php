<?php
/**
 * Default Manager French Lexicon Entries for Cliche
 *
 * @package cliche
 * @subpackage lexicon
 */

/* CARD - ALBUMS LIST */

//buttons
$_lang['cliche.add_album_btn'] = 'Créer un nouvel album';

//Create or update album window
$_lang['cliche.album_name_label'] = 'Nom d\'album (requis)';
$_lang['cliche.album_desc_label'] = 'Description';
$_lang['cliche.create_album_title'] = 'Votre album';
$_lang['cliche.save_album_btn'] = 'Enregistrer';

//Error messages from the backend


//view
$_lang['cliche.album-list.empty_msg'] = '<h4>Aucun album créé actuellement</h4><p>Créez votre premier album avec le bouton ci-dessus, sélectionnez ensuite l\'album créé pour voir et éditer son contenu.<br/>Utilisez le fil d\'arianne pour naviguer au sein des albums (votre position est indiquée par l\'élément vert non cliquable)</p>';
$_lang['cliche.album-empty-col-msg'] = 'Sélectionnez une entrée depuis la colonne principale pour voir sa description';
$_lang['cliche.list.empty_col_msg'] = '<div class="empty-msg">Sélectionnez un album depuis la colonne principale pour afficher sa description</div>';
$_lang['cliche.main_total_pics'] = '{total} images';

//msg
$_lang['cliche.breadcrumb_album_list_desc'] = 'Liste des albums<br/>Sélectionnez un album pour voir son contenu.';


/* CARD - ITEMS LIST */

//View
$_lang['cliche.items_empty_msg'] = '<h4>Aucune image présente dans cet album</h4><p>Ajoutez des images en cliquant sur le bouton ci-dessus</p>';
$_lang['cliche.items_empty_col_msg'] = '<div class="empty-msg">Séléectionnez une image depuis la colonne principale pour afficher sa description</div>';
$_lang['cliche.album_cover_alt_msg'] = '{name} aperçu';
$_lang['cliche.view_total_pics'] = 'Images totales';
//buttons
$_lang['cliche.add_images'] = 'Ajouter des images';
$_lang['cliche.update_album'] = 'Mettre à jour l\'album';
$_lang['cliche.delete_album'] = 'Supprimer l\'album';

//msg
$_lang['cliche.delete_album_msg'] = 'toutes les images de cet album seront également supprimées. Cette opération est irréversible.';
$_lang['cliche.breadcrumbs_album_msg'] = '<div class="album_desc">
	<tpl if="cover_id == 0">
		<span class="no-preview">Aucun aperçu</span>
	</tpl>
	<tpl if="cover_id">
		<img src="{thumbnail}" title="Couverture de l\'album {name}" alt="Image de couverture de l\'album {name}" />
	</tpl>
	<div class="album_name">Album : {name}
		<span>{total} Photos - Créé le {createdon} par <b>{createdby}</b></span>
	</div>
	<button class="inline-button green" onclick="Ext.getCmp(\'cliche-album-default\').onaddPhoto(); return false;"/><span class="icon-add-white">&nbsp;</span>Ajouter une photo</button>
</div>';

/* CARD - SINGLE ITEM */

//buttons
$_lang['cliche.edit_item'] = 'Éditer l\'image';
$_lang['cliche.delete_image'] = 'Supprimer l\'image';
$_lang['cliche.set_as_album_cover'] = 'Définir comme couverture d\'album';

//msg
$_lang['cliche.delete_image_title'] = 'Supprimer l\'image';
$_lang['cliche.delete_image_msg'] = 'Êtes-vous sûr de vouloir supprimer cette image ? Cette opération est irréversible.';
$_lang['cliche.breadcrumbs_item_msg'] = 'Affichage de l\'image : ';

/* CARD - UPLOAD ITEMS */

//view
$_lang['cliche.upload_desc'] = '<h4>Sélectionnez des fichiers depuis votre ordinateur</h4><p>Vous pouvez sélectionner plusieurs fichiers en maintenant la touche shift.<br />Les fichiers Zip sont supportés.</p>';
$_lang['cliche.upload_ready_msg'] = 'Fichier(s) prêt(s) à être uploadé(s) :';
$_lang['cliche.upload_cancel_msg'] = 'Annuler';
$_lang['cliche.upload_success_msg'] = 'Fichier(s) uploadé(s) avec succès';
$_lang['cliche.upload_fail_msg'] = 'Le fichier n\'a pu être enregistré';

//buttons
$_lang['cliche.browse'] = 'Parcourir…';
$_lang['cliche.start_upload'] = 'Commencer l\'upload';

//msg
$_lang['cliche.breadcrumbs_upload_pictures_msg'] = 'Uploader des fichiers';
$_lang['cliche.upload_in_progress'] = 'Upload en cours…';
$_lang['cliche.upload_items_for'] = 'Uploader des éléments pour l\'album ';
$_lang['cliche.upload_successful'] = 'Fichier(s) uploadé(s) avec succès';

//NOT USED YET
$_lang['cliche.global_progressbar_loading_item'] = 'Chargement de l\'élément';
$_lang['cliche.item_progressbar'] = '% effectué…';


/* MGR - COMMONS */
//tabs
$_lang['cliche.main_tab'] = 'Gérez vos albums';
//Breadcrumbs
$_lang['cliche.breadcrumb_root'] = 'Liste d\'albums';
//buttons
$_lang['cliche.close_btn'] = 'Annuler';
//misc
$_lang['cliche.actions'] = 'Actions';
$_lang['cliche.sort_by'] = 'Classer par';
$_lang['cliche.created_by'] = 'Créé par';
$_lang['cliche.created_on'] = 'Créé le';
// $_lang['cliche.sort_by_createdon'] = 'Creation date';
// $_lang['cliche.sort_by_total_pics'] = 'Number of pictures';
// $_lang['cliche.sort_by_alphabetically'] = 'Alphabetically';
$_lang['cliche.desc_title'] = 'Description';
$_lang['cliche.informations_title'] = 'Informations';
$_lang['cliche.no_desc'] = '<em>Aucune description</em>';
$_lang['cliche.no_preview'] = 'Aucun aperçu';
$_lang['cliche.loading'] = '<div class="centered empty-msg">Chargement…</div>';