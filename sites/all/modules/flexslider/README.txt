About
=====
Integrates the FlexSlider library into Drupal.

Current Options
---------------
Allows you to use FlexSlider in a few different ways


- As a library to be used with any other theme or module by calling drupal_add_library('flexslider', 'flexslider')
- Integrates with Views Slideshow with FlexSlider Views submodule (80% feature complete) (flexslider_views_slideshow)
- Integrates with Fields (flexslider_fields)
- Adds a Views display mode (flexslider_views)

Future Plans
------------

- Add support for Views jQFX

About FlexSlider
----------------

Library created and maintained by <a href="http://drupal.org/user/1491960">mbmuffin</a> at http://http://flex.madebymufffin.com/

- Simple, semantic markup
- Supported in all major browsers
- Horizontal/vertical slide and fade animations
- Multiple slider support, Callback API, and more
- Hardware accelerated touch swipe support
- Custom navigation options
- Use any html elements in the slides
- Built for beginners and pros, alike
- Free to use under the MIT license


Installation
============

1. Download the FlexSlider library from http://flex.madebymufffin.com/
2. Unzip the file and rename the folder to "flexslider" (pay attention to the case of the letters)
3. Put the folder in a libraries directory
    - Ex: sites/all/libraries
4. Ensure you have a valid path similar to this one
    - Ex: sites/all/libraries/flexslider/jquery.flexslider-min.js

That's it!

Debugging
---------

You can toggle the development version of the library in the administrative settings page. This will load the unminified version of the library.

Usage
=====

You can load the library using drupal_add_library('flexslider', 'flexslider') in your theme or module. For any integrated functionality, the library will be loaded automatically.