Cliche is an Image Gallery Component for MODx Revolution 2.2. 

Installation
---------------

- Install the component via the Package Manager.
- Reload the page
- Select Cliche under Components main menu
- Create an Album and upload some images
- Use the Cliche snippet to display your Albums


Features
-----------

- Simple Image Management : The cmp focuses on simplicity 
- Multiple File Upload : Batch upload of images and/or zip upload are available for your convenience
- Easy to template: You can use the html markup that you want (either in a tpl file or in a chunk via the manager) along with any CSS style that you need
- Javascript Effect : Use any popular image effect with the library of your choice

What's missing
------------------

- Copy or Move images between albums
- Watermark
- Sorting options
- Tag support
- Custom field
- Meta data (EXIF, IPTC or XMP meta data )

What's next
---------------

- Cliche Custom Resource types to manage album per resource which can be useful to manage portfolios
- More examples : Coming soon on a dedicated website
- Custom snippet : To allow easy sidebar integration
- More complete documentation

Front End Snippets
----------------------

Use the Cliche snippet to load your gallery as you want

Snippet Usage
-----------------

Simply drop the following line in your document : [[Cliche]]

To show albums list:
[[Cliche? 
    &view=`albums`
]]

To show a specific album:
[[Cliche? 
    &id=`your_album_id`
    &view=`album`
]]

To show a single image:
[[Cliche? 
    &id=`your_image_id`
    &view=`image`
]]

With the default front end viewer, you can show the image in a lightbox instead of a link to the image by setting "browse" to 0
Example :
[[Cliche?
	&id=`your_album_id`
    &view=`album`
	&browse=`0`
]]

Available Parameters
------------------------

List of the available parameters (and their default values) :

- thumbWidth : (120),
- thumbHeight : (120),			
- itemTpl,
- wrapperTpl,
- plugin : (default),	
- columns : (3),
- columnBreak : (<br style="clear: both;"/>),
- idParam : (cid), 
- viewParam : (view), 
- viewParamName : (album or image) 


However if you want to use a unordered list of item, you would want to not generate the linebreak.
In that case, just set the columns parameter to 0 :

[[ClicheItem? 
    &columns=`0`
]]

Notes
---------

By default, all chunks are filebased located in the assets/components/cliche/plugins/[plugin]/[chunkName].tpl 

However, you still can use any normal chunk if you want.
Cliche will search first for the chunk in the db and if it does not exist, the file in the plugin directory (as a *.tpl file).
You can bypass the search in db to use only filebased chunks by using the parameter "use_filebased_chunks"

Have fun !