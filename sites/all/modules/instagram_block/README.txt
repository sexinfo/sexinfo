
CONTENTS OF THIS FILE
---------------------

 * Introduction
 * Requirements
 * Installation
 * Innitial Configuration
 * Demonstration
 * Acknowledgments


INTRODUCTION
------------

This is a very simple module that integrates with Instagram and creates a block
containing your most recent instagram posts.

The block's configuration page lets you choose how many posts and what size
they should appear in the block. The images are individually exposed to the
drupal theme layer, so developers have access to an all of the variables
provided by the instagram api should they choose to extent the block. For more
informations see the instagram developer pages: 

http://instagram.com/developer/endpoints/users/#get_users_media_recent


REQUIREMENTS
------------

This module depends on php curl commands to parse the information from instagram
and thus has a dependency on php5-curl.

It also has a dependency on the drupal core block module.


INSTALLATION
------------

This module is installed like any drupal module hand has no specific
installation instructions.


INNITIAL CONFIGURATION
----------------------

You can configure the settings for your instagram block by going to the
configuration page (admin/config/content/instagram_block). You will need to
authorise the application with your instagram account.

I have provided an authorisation callback to get the users instagram details
through a site that I host (http://instagram.yanniboi.com).


DEMONSTRATION
-------------

For a demonstration of instagram_block module in use, please have a look at
instagram.yanniboi.com.


ACKNOWLEDGMENTS
---------------

The heavy lifting for this module was done by Nick from Blueprint Interactive,
so kudos to him.

See his article here:
http://www.blueprintinteractive.com/blog/how-instagram-api-fancybox-simp...
