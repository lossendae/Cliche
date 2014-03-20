<?php
/**
 * Default English Lexicon Entries for ClicheThumbnail
 *
 * @package cliche
 * @subpackage lexicon
 */

$_lang['clichethumbnail.main_default_text'] = '<h4>Kliknij przycisk by wybrać zdjęcie. </h4>Wczytany zostanie podgląd zdjęcia';
$_lang['clichethumbnail.main_your_preview'] = 'Podgląd miniatury';

$_lang['clichethumbnail.window_title'] = 'Menadżer miniatur';

$_lang['clichethumbnail.breadcrumb_album'] = 'Przeglądaj albumy';
$_lang['clichethumbnail.breadcrumb_crop'] = 'Kadruj zdjęcie';
$_lang['clichethumbnail.breadcrumb_crop_desc'] = '<h3>Narzędzie kadrowania</h3><p>Użyj lewego zdjęcia (rozmiar oryginalny) by dostosować miniaturę z prawej (podgląd miniatury)</p>';
$_lang['clichethumbnail.breadcrumb_root'] = 'Lista albumów';
$_lang['clichethumbnail.breadcrumb_root_desc'] = '<h3>Podgląd miniatury</h3><p>Wybierz zdjęcie z albumu</p>';
$_lang['clichethumbnail.breadcrumb_root_desc_with_thumb'] = '<h3>Podgląd miniatury</h3><p>Możesz dostosować zdjęcie klikając przycisk "Kadruj zdjęcie"</p>';
$_lang['clichethumbnail.breadcrumb_upload'] = 'Dodaj zdjęcia';

$_lang['clichethumbnail.btn_add_photo'] = 'Dodaj zdjęcia';
$_lang['clichethumbnail.btn_back_to_album'] = 'Wróc do albumu';
$_lang['clichethumbnail.btn_back_to_main'] = 'Wróć do głównego panelu';
$_lang['clichethumbnail.btn_browse'] = 'Przeglądaj...';
$_lang['clichethumbnail.btn_browse_album'] = 'Przeglądaj album';
$_lang['clichethumbnail.btn_crop_thumbnail'] = 'Kadruj zdjęcie';
$_lang['clichethumbnail.btn_crop_validate'] = 'Zatwierdź zmiany';
$_lang['clichethumbnail.btn_replace_thumbnail'] = 'Wyierz inne zdjęcie';
$_lang['clichethumbnail.btn_remove_thumbnail'] = 'Resetuj';
$_lang['clichethumbnail.btn_select_image'] = 'Wybierz zdjęcie';
$_lang['clichethumbnail.btn_start_upload'] = 'Rozpocznij wgrywanie zdjęć';

$_lang['clichethumbnail.main_empty_msg'] = '<h4>Nie ma żadnych zdjęć dla tego dokumentu</p>';

$_lang['clichethumbnail.album_desc'] = '<div class="album-infos"><tpl if="cover_id == 0">
		<span class="no-preview">Brak podglądu</span>
	</tpl>
	<tpl if="cover_id">
		<tpl if="thumbnail">
			<img src="{thumbnail}" title="Image Cover for the album {name}" alt="Image Cover for the album {name}" />
		</tpl>
		<tpl if="!thumbnail">
			<span class="no-preview error"><strong>Błąd</strong>Zdjęcia nie znaleziono</span>
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
	</div>
	<div class="album-desc">
		<p class="ct_info">Ten album zarezerwowany jest dla zmiennych dokumentów. Nie możesz go usunąć z poziomu tego panelu.<br/> 
		Możesz jednak usunąć i edytować znajdujące się w nim zdjęcia.
		</p>
	</tpl>';
$_lang['clichethumbnail.album_empty_msg'] = '<h4>Nie ma żadnych zdjęć w tym albumie</h4><p>Wgraj zdjęcia klikając przycisk "Wgraj zdjęcia"</p>';
$_lang['clichethumbnail.album_loading'] = '<div class="centered empty-msg">Ładuję...</div>';

$_lang['clichethumbnail.image_cover_alt_msg'] = '{name} preview';
$_lang['clichethumbnail.image_created_by'] = 'Utworzony przez';
$_lang['clichethumbnail.image_created_on'] = 'Utworzono';
$_lang['clichethumbnail.image_desc_title'] = 'Opis';
$_lang['clichethumbnail.image_informations_title'] = 'Informacje';
$_lang['cliche.image_unselected_msg'] = '<div class="empty-msg">Wybierz zdjęcie by zobaczyć opis</div>';
$_lang['clichethumbnail.image_no_desc'] = '<em>Brak opisu</em>';
$_lang['clichethumbnail.image_no_preview'] = 'Brak podglądu';

$_lang['clichethumbnail.upload_cancel'] = 'Anuluj';
$_lang['clichethumbnail.upload_desc'] = '<h4>Wybierz pliki z komputera</h4><p>Możesz wgrać kilka plików na raz przytrzymując SHIFT</p>';
$_lang['clichethumbnail.upload_in_progress'] = 'Wgrywanie w trakcie...'; 
$_lang['clichethumbnail.upload_ready_msg'] = 'Pliki gotowe do wgrania :';

$_lang['clichethumbnail.cropper_empty_msg'] = '<h4>Ładuję...</p>';
