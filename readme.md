# Cliche #

A media manager for MODx Revolution

## Installation instructions: ##

- Install the component via the package manager.
- Reload the page
- Click on Cliche menu item under components
- Create an album using the dedicated button
- Browse your album by clicking the newly created elements

You can go back and forth in your albums by using the breadcrumbs.

## Front End Snippets ##

There are 3 snippets available, they all share the same properties.

### Snippets Usage ###

Simply drop the snippet call anywhere in your document.

```
[[Cliche]]
```

*The 2 other snippets requires an id to work*

Retreiving an album sets
```
[[ClicheAlbum? 
    &id=`your_album_id`
]]
```

Retreiving a single item
```
[[ClicheItem? 
    &id=`your_item_id`
]]
```

### Available Parameters ###

List of the available parameters (and their default values) :

- thumbWidth (120),
- thumbHeight (120),			
- itemTpl (item),
- albumsWrapperTpl (albumwrapper),
- albumItemTpl (albumitem),
- display (default),	
- idParam (cid),
- loadCSS (true),
- css (default),
- config (null),
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

```
[[ClicheItem? 
    &columns=`0`
]]
```

For testing purpose, Cliche comes with a Gallerific example plugin.
To use it, set you snippet call like the following :

```
[[!ClicheAlbum?
	&id=`your_album_id`
	&thumbWidth=`75` //Or any width
	&thumbHeight=`75` //Or any height
	&display=`galleriffic` //Required
	&config=`config` //Required
	&css=`style` //Required
	&columns=`0` //Required
]]
```

## Notes ##

All chunks are filebased located in the assets chunks directory

The gallerific plugin does not bundle jquery, therefore, you will need to load it in other way in your page.

This is an incomplete "getting started" base documetnation for beta testers
The complete documentation is under writing.

Thanks for using Cliche.