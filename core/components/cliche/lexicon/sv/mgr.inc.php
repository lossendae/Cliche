<?php
/**
 * Default Manager Swedish Lexicon Entries for Cliche
 *
 * @package cliche
 * @subpackage lexicon
 */
 
$_lang['cliche.main_title'] = 'Cliche';

$_lang['cliche.breadcrumb_album_list_desc'] = '<h3>Albumlista</h3><p>Välj ett album för att visa dess innehåll.</p>';
$_lang['cliche.breadcrumb_root'] = 'Albumlista';
$_lang['cliche.breadcrumb_upload_images'] = 'Ladda upp bilder'; 

$_lang['cliche.field_album_name_label'] = 'Albumets namn (krävs)';
$_lang['cliche.field_album_desc_label'] = 'Kort beskrivning';
$_lang['cliche.field_image_name_label'] = 'Bildens namn (krävs)';
$_lang['cliche.field_image_desc_label'] = 'Beskrivning';

$_lang['cliche.album_list_empty_msg'] = '<h4>Inga album har blivit skapade ännu</h4><p>Skapa ditt första album genom knappen ovan, klicka sedan på det nyskapade albumet för att visa dess innehåll.</p>';
$_lang['cliche.album_list_total_pics'] = '{total} bilder';

$_lang['cliche.album_desc'] = '<div class="album-infos"><tpl if="cover_id == 0">
		<span class="no-preview">Ingen förhandsgranskning</span>
	</tpl>
	<tpl if="cover_id == 0">
		<span class="no-preview"><span>Ingen förhandsgranskning</span></span>
	</tpl>
	<tpl if="cover_id">
		<tpl if="thumbnail">
			<img src="{thumbnail}" title="Omslagsbild för album {name}" alt="Omslagsbild för album {name}" />
		</tpl>
		<tpl if="!thumbnail">
			<span class="no-preview error"><span><strong>Fel</strong>Bilden hittades inte</span></span>
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
	</div><div class="album-desc"><p>{description}</p></tpl>';
$_lang['cliche.album_item_cover_alt_msg'] = 'Förhandsgranskning av {name}';
$_lang['cliche.album_item_created_by'] = 'Skapad av';
$_lang['cliche.album_item_created_on'] = 'Skapad den';
$_lang['cliche.album_item_desc_title'] = 'Beskrivning';
$_lang['cliche.album_item_empty_msg'] = '<h4>Det finns inga bilder i detta album</h4><p>Lägg till bilder genom att klicka på den gröna knappen "Lägg till bilder"</p>';
$_lang['cliche.album_item_id'] = 'ID';
$_lang['cliche.album_item_informations_title'] = 'Information';

$_lang['cliche.btn_add_album'] = 'Lägg till album';
$_lang['cliche.btn_add_photo'] = 'Lägg till bilder';
$_lang['cliche.btn_back_to_album_list'] = 'Tillbaka till albumlistan';
$_lang['cliche.btn_back_to_album'] = 'Tillbaka till albumet';
$_lang['cliche.btn_browse'] = 'Bläddra...';
$_lang['cliche.btn_delete_album'] = 'Radera album';
$_lang['cliche.btn_delete_image'] = 'Radera bild';
$_lang['cliche.btn_edit_image'] = 'Redigera bild';
$_lang['cliche.btn_options'] = 'Alternativ';
$_lang['cliche.btn_save_album'] = 'Spara album';
$_lang['cliche.btn_save_image'] = 'Spara bild';
$_lang['cliche.btn_set_as_album_cover'] = 'Sätt som bildomslag';
$_lang['cliche.btn_start_upload'] = 'Starta uppladdning';
$_lang['cliche.btn_update_album'] = 'Uppdatera album';

$_lang['cliche.window_create_a_new_album'] = 'Skapa ett nytt album';
$_lang['cliche.window_delete_album'] = 'Radera album';
$_lang['cliche.window_delete_album_msg'] = 'Alla bilder i detta album kommer också att raderas. Denna operation är bestående och går ej att ångra.';
$_lang['cliche.window_delete_image'] = 'Radera bild';
$_lang['cliche.window_delete_image_msg'] = 'Är du säker att du vill radera denna bild? Denna operation är bestående och går ej att ångra.';
$_lang['cliche.window_edit_image'] = 'Redigera din bild';
$_lang['cliche.window_edit_image_msg'] = 'Redigera informationen för din bild';
$_lang['cliche.window_set_as_album_cover'] = 'Sätt som bildomslag';
$_lang['cliche.window_set_as_album_cover_msg'] = 'Vill du sätta denna bild som albumets bildomslag?';
$_lang['cliche.window_update_album'] = 'Uppdatera album';

$_lang['cliche.upload_cancel_msg'] = 'Avbryt';
$_lang['cliche.upload_desc'] = '<h4>Välj bilder från din dator</h4><p>Klicka på knappen "Bläddra" för att välja bilder du vill ladda upp. Du kan välja flera bilder samtidigt genom att hålla in Ctrl-tangenten.</p>';
$_lang['cliche.upload_in_progress'] = 'Uppladdningen pågår...';
$_lang['cliche.upload_items_for'] = '<h3>Bilduppladdning</h3><p>Ladda upp nya bilder till albumet <strong>'; 
$_lang['cliche.upload_ready_msg'] = 'Filer klara att laddas upp :';
$_lang['cliche.upload_successful'] = 'Filerna laddades upp framgångsrikt'; 

/* Common */
$_lang['cliche.album_empty_col_msg'] = 'Välj en bild från huvudkolumnen för att visa dess beskrivning';
$_lang['cliche.loading'] = '<div class="centered empty-msg">Laddar...</div>';
$_lang['cliche.no_desc'] = '<em>Ingen beskrivning</em>';
$_lang['cliche.no_preview'] = 'Ingen förhandsgranskning';
$_lang['cliche.saving_msg'] = 'Sparar, var god vänta...';