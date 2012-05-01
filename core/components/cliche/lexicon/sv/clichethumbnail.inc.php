<?php
/**
 * Default Swedish Lexicon Entries for ClicheThumbnail
 *
 * @package cliche
 * @subpackage lexicon
 */

$_lang['clichethumbnail.main_default_text'] = '<h4>Klicka på knappen ovan för att välja en bild</h4>Förhandsgranskningen för den valda bilden kommer att ersätta denna text';
$_lang['clichethumbnail.main_your_preview'] = 'Miniatyr förhandsgranskning';

$_lang['clichethumbnail.window_title'] = 'Miniatyrhanterare';

$_lang['clichethumbnail.breadcrumb_album'] = 'Albumsbläddrare';
$_lang['clichethumbnail.breadcrumb_crop'] = 'Klipp bild';
$_lang['clichethumbnail.breadcrumb_crop_desc'] = '<h3>Klippverktyg</h3><p>Använd den vänstra bilden (original storlek) för att justera markeringen för den högra miniatyren (miniatyr förhandsgranskning)</p>';
$_lang['clichethumbnail.breadcrumb_root'] = 'Albumlista';
$_lang['clichethumbnail.breadcrumb_root_desc'] = '<h3>Miniatyr förhandsgranskning</h3><p>Välj en bild från det dedikerade albumet</p>';
$_lang['clichethumbnail.breadcrumb_root_desc_with_thumb'] = '<h3>Miniatyr förhandsgranskning</h3><p>Du kan välja en specifik del av bilden genom att klicka på knappen "Klipp bild"</p>';
$_lang['clichethumbnail.breadcrumb_upload'] = 'Lägg till bilder';

$_lang['clichethumbnail.btn_add_photo'] = 'Lägg till bilder';
$_lang['clichethumbnail.btn_back_to_album'] = 'Tillbaka till albumet';
$_lang['clichethumbnail.btn_back_to_main'] = 'Tillbaka till huvudpanelen';
$_lang['clichethumbnail.btn_browse'] = 'Bläddra...';
$_lang['clichethumbnail.btn_browse_album'] = 'Bläddra i albumet';
$_lang['clichethumbnail.btn_crop_thumbnail'] = 'Klipp bild';
$_lang['clichethumbnail.btn_crop_validate'] = 'Bekräfta ändringar';
$_lang['clichethumbnail.btn_replace_thumbnail'] = 'Välj en annan bild';
$_lang['clichethumbnail.btn_remove_thumbnail'] = 'Återställ';
$_lang['clichethumbnail.btn_select_image'] = 'Välj denna bild';
$_lang['clichethumbnail.btn_start_upload'] = 'Starta uppladdning';

$_lang['clichethumbnail.main_empty_msg'] = '<h4>Ingen miniatyr har satts för detta dokument ännu</p>';

$_lang['clichethumbnail.album_desc'] = '<div class="album-infos"><tpl if="cover_id == 0">
		<span class="no-preview">Ingen förhandsgranskning</span>
	</tpl>
	<tpl if="cover_id">
		<tpl if="thumbnail">
			<img src="{thumbnail}" title="Omslagsbild för albumet {name}" alt="Omslagsbild för albumet {name}" />
		</tpl>
		<tpl if="!thumbnail">
			<span class="no-preview error"><strong>Fel</strong>Bilden kunde inte hittas</span>
		</tpl>
	</tpl>
	<div class="album-name"><h3>Album : <span>{name}</span></h3>
		Skapades den <strong>{createdon}</strong> av <strong>{createdby}</strong><br/>
		Album id : #<strong>{id}</strong>
	</div>
	<div class="album-meta">
		<span>{total}</span>
		bilder
	</div></div>
	<tpl if="description">
	</div>
	<div class="album-desc">
		<p class="ct_info">Detta album är reserverat för clichethumbnail. Du kan inte redigera eller radera det från denna panel.<br/> 
		Men du kan fortfarande ladda upp bilder för användning i den anslutna mallvariabeln.
		</p>
	</tpl>';
$_lang['clichethumbnail.album_empty_msg'] = '<h4>Det finns inga bilder i detta album</h4><p>Ladda upp bilder genom att klicka på "Lägg till bilder"</p>';
$_lang['clichethumbnail.album_loading'] = '<div class="centered empty-msg">Laddar...</div>';

$_lang['clichethumbnail.image_cover_alt_msg'] = 'Förhandsgranskning av {name}';
$_lang['clichethumbnail.image_created_by'] = 'Skapad av';
$_lang['clichethumbnail.image_created_on'] = 'Skapad den';
$_lang['clichethumbnail.image_desc_title'] = 'Beskrivning';
$_lang['clichethumbnail.image_informations_title'] = 'Information';
$_lang['cliche.image_unselected_msg'] = '<div class="empty-msg">Välj en bild från huvudkolumnen för att se dess beskrivning</div>';
$_lang['clichethumbnail.image_no_desc'] = '<em>Ingen beskrivning</em>';
$_lang['clichethumbnail.image_no_preview'] = 'Ingen förhandsgranskning';

$_lang['clichethumbnail.upload_cancel'] = 'Avbryt';
$_lang['clichethumbnail.upload_desc'] = '<h4>Välj filer från din dator</h4><p>Klicka på knappen "Bläddra" för att välja bilder du vill ladda upp. Du kan välja flera bilder samtidigt genom att hålla in Ctrl-tangenten.</p>';
$_lang['clichethumbnail.upload_in_progress'] = 'Uppladdningen pågår...'; 
$_lang['clichethumbnail.upload_ready_msg'] = 'Filer klara att laddas upp :';

$_lang['clichethumbnail.cropper_empty_msg'] = '<h4>Laddar...</p>';