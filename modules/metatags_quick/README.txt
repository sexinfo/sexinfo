***********
* README: *
***********

DESCRIPTION:
------------
Module implements meta tags for Drupal pages in 2 different ways.
For pages that represent fieldable entities (nodes, users, taxonomy terms),
module uses Drupal 7 Fields API. To do that, module exposes new field type: meta.
For pages that do not represent fieldable entities (i.e. views, default 
front page etc.), module exposes artificial entity 'Path-based meta tags'


INSTALLATION:
-------------
1. Place the entire metatags_quick directory into your Drupal sites/all/modules/
   directory.

2. Enable Meta tags (quick) module by navigating to:
     administer > modules
     
3a. You can attach meta tags to installed entities either via module
	configuration screen (admin/config/search/metatags_quick).
     
3b. You can skip automatic fields creation and define meta tags
with Field UI module. 
     
4. (Optional) enable path-based meta tags. 

RELATED MODULES

Field UI core module is necessary to define new fields, but can be switched off on 
production servers.

You may want to use field_group module (http://drupal.org/project/field_group)
to add nice grouping of meta tag fields

token module (http://drupal.org/project/token) extends built in D7
token functionality.

---------

Notes:
-----
Meta name is field name. For example, if you add field field_description, resulting meta will be <meta name="description" content="<field value>"/>
You can add several meta fields with different names.


Financial support:
-------
I have started this project as my personal workaround of the meta tags 
problem in Drupal 7. Since then, surprisingly lot of people have downloaded
 and use it, so now it takes more of my time than I have planned initially.
If you find this module useful, please consider helping me to add new features and fix old bugs :)<br>
by donating at http://www.valthebald.net/donate_drupal
Your support is much appreciated. Thanks in advance!

Author:
-------
Valery Lourie valerylourie@gmail.com
upgrade path from nodewords written by maxiorel <maxiorel@49016.no-reply.drupal.org>
