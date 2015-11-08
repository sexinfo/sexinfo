MODULE
 Autocomplete Searchbox
-------------------------------------------------------------------------------


DESCRIPTION
 This module allows you to turn the default drupal searchbox into an
 autocomplete search box. However, limiting details from autocomplete
 dropdown does not guarantee that search results will not show up. It
 is just a way to ease searching on a given website.

 You can provide a colorful layout of autocomplete results by enabling
 and entering color codes for it from the settings page.

 Please pour in your suggestions as to how this module can be used in a better
 way, or whether there is a security flaw, or a bug. That would be appreciable.
-------------------------------------------------------------------------------


USAGE
 1. You can either use this module with the drupal search box, or
 2. You can even make any custom textfield in drupal, a searchable
    textfield.
    That is, just put autocomplete path created by this module, and turn
    your custom created textfield into a textfield that searches anything
    on the website and fills it.
 3. Optional but good to have - set a broader width of the textfield to adjust
    searchbox results.

 Autocomplete path can be used as :

  $form['autocomplete_searchbox_search_sample'] = array(
    '#title' => t('Searchbox Demo'),
    '#type' => 'textfield',
    '#autocomplete_path' => 'admin/search-portal',
  );

 Needless to say, it will work according to how you configure it on the
 settings page.

 Either or both of the above situations can work together. You can disable
 the default autocomplete functionality on searchbox by unchecking the
 "Autocomplete with searchbox" field in the advance settings.

 By using this module, you have the power to whether to show or hide results
 in the autocomplete for a specific drupal entity.

 As yet, only content types, taxonomies and users are
 searchable, as they are the primary building blocks of a drupal site.
-------------------------------------------------------------------------------


CONFIGURATION
 Before using this module : immediately after installation, configure the
 autocomplete settings. On the settings page, you will see a demo searchbox.
 This is provided for previewing or to get an idea of what exactly the
 searchbox in the website will do.

 Visit "admin/config/search/autocomplete-search-config" to set who all can use,
 what all things you want to and how you want to show in the autocomplete
 dropdown.
-------------------------------------------------------------------------------


CREDITS
 Gauravjeet Singh < gauravjeet007@gmail.com >
 D.O. username < gauravjeet_singh >
-------------------------------------------------------------------------------
