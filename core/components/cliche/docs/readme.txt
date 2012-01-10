Cliche
======

A media manager for MODx Revolution

Installation instructions:
--------------------------

- Install the component via the package manager.
- Reload the page
- Click on Cliche menu item under components
- Create an album using the dedicated button
- Browse your album by clicking the newly created elements

You can go back and forth in your albums by using the breadcrumbs.

Front End Snippets
------------------

Use the liche snippet to load your gallery as you want

Snippet Usage
--------------

Simply drop the snippet call anywhere in your document [[Cliche]]
The 2 other snippets requires an id to work

To retreive an album list:
[[Cliche? 
    &view=`albums`
]]

To retreive an album:
[[Cliche? 
    &id=`your_album_id`
    &view=`album`
]]

To retreive a single image:
[[Cliche? 
    &id=`your_image_id`
    &view=`image`
]]

Witht he default front end viewer, you can disable browsing through the image and use fancybox to show the image
[[Cliche?
	&id=`your_album_id`
    &view=`album`
	&browse=`0`
]]

Available Parameters
--------------------

List of the available parameters (and their default values) :

- thumbWidth (120),
- thumbHeight (120),			
- itemTpl,
- wrapperTpl,
- plugin (default),	
- css (default),
- columns (3),
- columnBreak (<\br style="clear: both;">),
- idParam (cid), //Only used by the main cliche snippet
- viewParam (view), //Only used by the main cliche snippet
- viewParamName (item or set) //Only used by the main cliche snippet

Per default, all galleries are displayed a la Wordpress.
Example for tree columns:

[container]
[item][item][item][linebreak]
[item][item][item][linebreak]
[item][item][item][linebreak]
[/container]

However if you want to use a unordered list of item, you would want to not generate the linebreak.
In that case, just set the columns parameter to 0 :
[[ClicheItem? 
    &columns=`0`
]]

For testing purpose, Cliche comes with a Gallerific example plugin.
To use it, set you snippet call like the following :

[[!ClicheAlbum?
	&id=`your_album_id`
	&thumbWidth=`75` //Or any width
	&thumbHeight=`75` //Or any height
	&display=`galleriffic` //Required
	&config=`config` //Required
	&css=`style` //Required
	&columns=`0` //Required
]]

Notes
-----

By default, all chunks are filebased located in the assets/components/cliche/plugins/[pluginName]/[chunksName].tpl 

However, you still can use any normal chunk if you want/need.
Cliche will search first for the chunk in the db and if it does not exist, in the current plugin directory (as a *.tpl file).
You can bypass the search in db to use only filebased chunks by using the parameter config['use_filebased_chunks']

This is an incomplete "getting started" base documetnation for beta testers
The complete documentation is under writing.

Thanks for using Cliche.