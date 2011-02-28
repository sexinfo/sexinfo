<?php

/**
 * Config Editor
 *
 * Description: Allows admins to change configuration options through the admin
 * interface
 *
 * Validation is lazy:  Start with safe default and replace with good data if
 * found.
 *
 */
require('../core/sex-core.php');

# Security function - MUST PASS TRUE FOR ADMIN PAGES!
$security = new security( TRUE );

$page = new admin_page( 'template.html' );

if( $security->permission_level() > 2 )
{
	$security->redirect( '.' );
}

if( $security->session_logged_in() )
{
	//-- Data initialization
	$db = new database();
	$qaform_enabled = false;
	$qaform_limit = 10;
	$qaform_message = 'custom';
	$text = array(
		'full' => null,
		'summer' => null,
		'winter' => null,
		'custom' => null,
	);

	//-- _POST controls

	if( isset( $_POST['save_settings'] ) )
	{
		# Enable form?
		if( isset( $_POST['form_active'] ) && 'enabled' == $_POST['form_active'] )
		{
			$qaform_enabled = true;
			$db->query( "UPDATE sex_config SET config_value = 'true' WHERE config_option = 'qaform_enabled'" );
		}
		else
		{
			$db->query( "UPDATE sex_config SET config_value = 'false' WHERE config_option = 'qaform_enabled'" );
		}

		# Set limit
		if( isset( $_POST['message_limit'] ) && is_numeric( $_POST['message_limit'] ) )
		{
			$limit = intval( $_POST['message_limit'] );
			$db->query( "UPDATE sex_config SET config_value = $limit WHERE config_option = 'qaform_limit'" );
		}

		# Set current message
		if( isset( $_POST['form_message'] ) )
		{
			if( 'summer' == $_POST['form_message'] || 'winter' == $_POST['form_message'] )
			{
				$current_message = $db->escape( $_POST['form_message'] );
			}
			else
			{
				$current_message = 'custom';
			}
			$db->query( "UPDATE sex_config SET config_value = '$current_message' WHERE config_option = 'qaform_message'" );
		}

		# Set messages
		foreach( array( 'winter', 'summer', 'custom', 'full' ) as $message )
		{
			$message_text = $db->escape( $_POST['message_'.$message] );
			$db->query( "UPDATE sex_config SET config_value = '$message_text' WHERE config_option = 'qaform_$message'" );
		}
	}

	//-- Load database

	$db->query( "SELECT config_id, config_option, config_value FROM sex_config" );
	$op = array( );

	# Add
	foreach( $db->multi_result() as $row )
	{
		$op[$row['config_option']] = array(
			'id' => $row['config_id'],
			'option' => $row['config_option'],
			'value' => $row['config_value']
		);
	}

	//-- Read query results

	if( 'true' == $op['qaform_enabled']['value'] )
	{
		$qaform_enabled = true;
	}
	$qaform_message = $op['qaform_message']['value'];
	$qaform_limit = $op['qaform_limit']['value'];
	$text = array(
		'full' => $op['qaform_full']['value'],
		'summer' => $op['qaform_summer']['value'],
		'winter' => $op['qaform_winter']['value'],
		'custom' => $op['qaform_custom']['value'],
	);

	//-- HTML output
	$page->title( 'Config Editor' );
	$page->add( '
		<h1>Configuration Editor</h1>
		<p>The configuration values below are used to control the Question &amp; Answer form. All message fields should be tagged with html.</p>
		<p>If the box is full, the "Box is Full" message will be displayed.  If the form is set to <b>Disabled</b> the specified seasonal messages will be displayed instead.</p>
		
		<form action="admin-config.php" method="post" id="config_editor">
		<fieldset><legend>General Settings</legend>
		
		<h3>Question Controls</h3>
		<p>The question form is:
	' );

	$page->add( radio_line( 'form_active', 'enabled', 'Enabled', true === $qaform_enabled ) );
	$page->add( radio_line( 'form_active', 'disabled', 'Disabled', false === $qaform_enabled ) );

	$page->add( '
		</p>
		<p>Current message:
	' );

	$page->add( radio_line( 'form_message', 'summer', 'Summer', 'summer' == $qaform_message ) );
	$page->add( radio_line( 'form_message', 'winter', 'Winter', 'winter' == $qaform_message ) );
	$page->add( radio_line( 'form_message', 'custom', 'Custom', 'custom' == $qaform_message ) );

	$page->add( '
		</p>
		<p><label for="message_limit">Message limit:</label> <input type="text" id="message_limit" name="message_limit" value="'.$qaform_limit.'" /></p>

		<h3>Box is Full Message</h3>
		<p><textarea cols="80" rows="5" name="message_full">'.$text['full'].'</textarea></p>
		</fieldset>

		<fieldset><legend>Seasonal Messages</legend>
		<h3>Summer Message</h3>
		<p><textarea cols="80" rows="5" name="message_summer">'.$text['summer'].'</textarea></p>

		<h3>Winter Message</h3>
		<p><textarea cols="80" rows="5" name="message_winter">'.$text['winter'].'</textarea></p>

		<h3>Custom Message</h3>
		<p><textarea cols="80" rows="5" name="message_custom">'.$text['custom'].'</textarea></p>
		</fieldset>

		<p style="text-align: center;"><input type="submit" name="save_settings" value="Save Settings" /></p>
		</form>
	' ); # End Config Form
}

$page->output();

function radio_line( $name, $id, $text, $selected = false )
{
	if( true === $selected )
	{
		$selected = 'checked="checked"';
	}
	else
	{
		$selected = null;
	}
	return '<input name="'.$name.'" id="'.$id.'" type="radio" value="'.$id.'" '.$selected.'/> <label for="'.$id.'">'.$text.'</label>';
}

?>