
---------------------------------------------------------------------------
seo_checker - Version 7.x-1.x
---------------------------------------------------------------------------
Author: Michael Ruoss (mruoss at ufirstgroup dot co m)

Overview:
=========
The SEO Compliance Checker hooks into the node creation and modification 
procedure and checks the submitted content on the compliance of specific 
search engine optimization rules.

seo_checker is the core module that triggers the checks and displays 
their results. The checks for the several SEO rules have to be provided by
separate submodules.

The core module comes along with such a submodule providing checks for some 
basic SEO rules. There is however a simple way to extend the functionality
of the checker by adding more such rules in further submodules.


Defining further SEO rules:
===========================
A submodule providing rules for the seo_checker should implement the hook
hook_register_seo_rules() which should return an array providing the most
important informations about the rules which are the following:

  name:                 The name of the rule
  description:          Describes what the rule checks upon
  threshold type:       There are currently two possible types:
                          at_least: The result of the check must reach a 
                                    certain threashold in order to succeed
                          range:    The result must be inside a range 
                                    (e.g. between 20% and 40%)
  default threshold:    Thresholds can be changed on the settings page. 
                        Here you can set a default. use array(20,40) for
                        ranges. 
  callback:             This is the function that implements the check. 
                        It will be called by seo_checker.
  callback arguments    Array of arguments that will be passed to the
                        callback function AFTER the form elements
  passed feedback:      The feedback the user gets if he passes the check
  failed feedback:      The feedback the user gets if he failed the check


An example for an implementation of this hook:

basic_seo_rules_register_seo_rules() {
  $rules['alt_attributes'] = array(
    'name' => t('Alt attributes in &lt;img&gt; - tags'),
    'description' => t('Checks if all the <img> tags have an alt attribute.'),
    'threshold type' => 'at_least',
    'default threshold' => 100,
    'callback' => 'basic_seo_rules_alt_attribute',
    'passed feedback' => t('Test passed.'),
    'failed feedback' => t('Test failed, please make sure your images contain an alternative text.'), 
  );
return $rules;
}


Implementing the checks:
========================
For the example displayed above we would now implement the
function basic_seo_rules_alt_attribute($form_values) which will be called by
the SEO Checker, passing it the values from the node_form.

As the checker displays percents, the function should return a value between
0 and 100.


Adding a settings tab for your rules:
=====================================
Using hook_menu you can add your own tabs in the default setting page of the
SEO Checker. Just use the same path and the type MENU_LOCAL_TASK:

$items['admin/settings/seo_checker/YOUR_MODULE'] = array( 
 'title' => ...
 ... => ...
 'type' => MENU_LOCAL_TASK,
)
