<?php
/**
 * Default French Lexicon Entries for ClicheThumbnail
 *
 * @package cliche
 * @subpackage lexicon
 */

$_lang['clichethumbnail.main_default_text'] = '<h4>Cliquez le bouton ci-dessous pour sélectionner une image</h4>L\'aperçu de l\'image selectionnée remplacera ce texte';
$_lang['clichethumbnail.main_your_preview'] = 'Aperçu de miniature';

$_lang['clichethumbnail.window_title'] = 'Gestionnaire de miniature';

$_lang['clichethumbnail.breadcrumb_album'] = 'Choix de d\'album';
$_lang['clichethumbnail.breadcrumb_crop'] = 'Rogner l\'image';
$_lang['clichethumbnail.breadcrumb_crop_desc'] = '<h3>Outil de rognage</h3><p>Utilisez l\'image de gauche (taille originale) pour ajuster la miniature (aperçu à gauche)</p>';
$_lang['clichethumbnail.breadcrumb_root'] = 'Liste d\'album';
$_lang['clichethumbnail.breadcrumb_root_desc'] = '<h3>Aperçu de miniature</h3><p>Sélectionnez une image depuis l\'album souhaité</p>';
$_lang['clichethumbnail.breadcrumb_root_desc_with_thumb'] = '<h3>Aperçu de miniature</h3><p>Vous pouvez choisir une portion de l\'image en cliquant sur le bouton « Rogner l\'image »</p>';
$_lang['clichethumbnail.breadcrumb_upload'] = 'Ajouter des images';

$_lang['clichethumbnail.btn_add_photo'] = 'Ajouter des images';
$_lang['clichethumbnail.btn_back_to_album'] = 'Retour à l\'album';
$_lang['clichethumbnail.btn_back_to_main'] = 'Retour à l\'écran principal';
$_lang['clichethumbnail.btn_browse'] = 'Parcourir…';
$_lang['clichethumbnail.btn_browse_album'] = 'Parcourir l\'album';
$_lang['clichethumbnail.btn_crop_thumbnail'] = 'Rogner l\'image';
$_lang['clichethumbnail.btn_crop_validate'] = 'Appliquer les changements';
$_lang['clichethumbnail.btn_replace_thumbnail'] = 'Sélectionner une autre image';
$_lang['clichethumbnail.btn_remove_thumbnail'] = 'Annuler';
$_lang['clichethumbnail.btn_select_image'] = 'Sélectionner cette image';
$_lang['clichethumbnail.btn_start_upload'] = 'Démarrer l\'upload';

$_lang['clichethumbnail.main_empty_msg'] = '<h4>Il n\'y a pas d\'aperçu de défini pour ce document</p>';

$_lang['clichethumbnail.album_desc'] = '<div class="album-infos"><tpl if="cover_id == 0">
		<span class="no-preview">Aucun aperçu</span>
	</tpl>
	<tpl if="cover_id">		
		<tpl if="thumbnail">
			<img src="{thumbnail}" title="Couverture pour l\'album {name}" alt="Image de couverture pour l\'album {name}" />
		</tpl>
		<tpl if="!thumbnail">
			<span class="no-preview error"><strong>Error</strong>Image not found</span>
		</tpl>
	</tpl>
	<div class="album-name"><h3>Album : <span>{name}</span></h3>
		Crée le <strong>{createdon}</strong> par <strong>{createdby}</strong><br/>
		Album id : #<strong>{id}</strong>
	</div>
	<div class="album-meta">
		<span>{total}</span>
		Images
	</div></div>
	<tpl if="description">
	</div>
	<div class="album-desc">
		<p class="ct_info">Cet album est réservé par CLicheThumbnail. Vous ne pouvez pas l\'editer ou l\'effacer à partir de cet écran.<br/> 
		Cependant, vous avez toujours la possibilité de rajoutez des images pour utiliser avec la Variable de Modèle attachée.
		</p>
	</tpl>';
$_lang['clichethumbnail.album_empty_msg'] = '<h4>Il n\'y a pas d\'image dans cet album</h4><p>Ajoutez des images en cliquant sur « Ajouter des images »</p>';
$_lang['clichethumbnail.album_loading'] = '<div class="centered empty-msg">Chargement…</div>';

$_lang['clichethumbnail.image_cover_alt_msg'] = '{name} aperçu';
$_lang['clichethumbnail.image_created_by'] = 'Créé par';
$_lang['clichethumbnail.image_created_on'] = 'Créé le';
$_lang['clichethumbnail.image_desc_title'] = 'Description';
$_lang['clichethumbnail.image_informations_title'] = 'Informations';
$_lang['cliche.image_unselected_msg'] = '<div class="empty-msg">Sélectionnez une image depuis la colonne principale pour voir sa description</div>';
$_lang['clichethumbnail.image_no_desc'] = '<em>Aucune description</em>';
$_lang['clichethumbnail.image_no_preview'] = 'Aucun aperçu';

$_lang['clichethumbnail.upload_cancel'] = 'Annuler';
$_lang['clichethumbnail.upload_desc'] = '<h4>Sélectionnez des fichiers depuis votre ordinateur</h4><p>Vous pouvez sélectionner plusieurs fichiers en même temps en maintenant la touche shift.</p>';
$_lang['clichethumbnail.upload_in_progress'] = 'Upload en cours…';
$_lang['clichethumbnail.upload_ready_msg'] = 'Fichiers prêts à être uploadés :';

$_lang['clichethumbnail.cropper_empty_msg'] = '<h4>Chargement…</p>';