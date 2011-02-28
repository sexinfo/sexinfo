<?php
/**********************************************************************//**\file
	Edit Categories

	Description: Allows webmasters to add new categories, edit existing
		categories, and add content to category pages.
*******************************************************************************/

	require('../core/sex-core.php');

	# Security function - MUST PASS TRUE FOR ADMIN PAGES!
	$security = new security(TRUE);

	if($security->session_logged_in() && $security->permission_level() <= 2)
	{
		$page = new admin_page('template.html');

		$page->head('<script type="text/javascript" src="prototype.js"></script>' .
					'<script type="text/javascript" src="admin-category.js"></script>');

		function output_category_for_ul($category, $typeid) {
			$id = $category['id'];

			$output = "<li class=\"cat\" id=\"cat-$id\">";

			$output .= "<a class=\"collapse\" id=\"sublist-$id-show\" href=\"javascript:SexInfo.Category.showSubList($id)\">[+]</a>\n";
			$output .= "<a class=\"expand hidden\" id=\"sublist-$id-hide\" href=\"javascript:SexInfo.Category.hideSubList($id)\">[-]</a>\n";

			$output .= "<a class=\"move move-$typeid movecat volatile hidden\" id=\"movecat-$id\" href=\"javascript:SexInfo.Category.moveToCat($id)\">[here]</a>\n";

			$output .= "<a id=\"name-$id\" href=\"javascript:SexInfo.Category.toggleOpts($id)\" title=\"show or hide operations for this category\">{$category['name']}</a>\n";

			$output .= "<span id=\"opts-$id\" class=\"opts hidden\">\n";

			if($category['content']) {
				$output .= "<a class=\"view\" target=\"_blank\" href=\"/sexinfo/category/{$category['slug']}\">[view]</a>\n";
				$output .= "<a class=\"edit\" href=\"admin-content.php?action=edit&amp;id={$category['content']}\">[edit content]</a>\n";
			} else {
				$output .= "<a class=\"make volatile\" href=\"javascript:SexInfo.Category.addContentTo($id)\">[add content]</a>\n";
			}

			$output .= "<a class=\"new\" href=\"javascript:SexInfo.Category.newInCat($id)\">[new]</a>\n";
			$output .= "<a class=\"movethis\" id=\"movethis-$id\" href=\"javascript:SexInfo.Category.move($id, $typeid)\">[move]</a>\n";
			$output .= "<a class=\"delete volatile\" id=\"delete-$id\" href=\"javascript:SexInfo.Category.remove($id)\">[delete]</a>\n";
			$output .= "</span>\n";

			$output .= "</li>\n";

			$output .= "<ul class=\"sublist expand hidden\" id=\"sublist-$id\">\n";

			foreach($category['children'] as $child) {
				$output .= output_category_for_ul($child, $typeid);
			}

			$output .= "</ul>\n";

			return $output;
		}

		$mysqli = new mysqli($config['dbhost'], $config['dbuser'], $config['dbpass'], $config['dbname']);

		$page->add('<a href="javascript:SexInfo.Category.expandAll()">[expand all]</a> | ');
		$page->add('<a href="javascript:SexInfo.Category.collapseAll()">[collapse all]</a> | ');

		$page->add('<a href="javascript:SexInfo.Category.showAll()">[show options]</a> | ');
		$page->add('<a href="javascript:SexInfo.Category.hideAll()">[hide options]</a>');

		$page->add("<h2>Move and Delete don't work, but everything else should.</h2>");

		if(isset($_GET['message'])) {
			$message = htmlspecialchars(stripslashes($_GET['message']));
			$page->add("<div class=\"message\">$message</div>\n");
		}

		$page->add("<ol id=\"typelist\">\n");
		$result = $mysqli->query("SELECT * FROM `sex_type` ORDER BY `type_id` ASC");

		while($type = $result->fetch_assoc()) {
			$id = $type['type_id'];

			$cats = data::fetch_full_category_list($type['type_id']);

			$page->add("<li class=\"type\" id=\"type-$id\">");

			$page->add("<a class=\"collapse\" id=\"catlist-$id-show\" href=\"javascript:SexInfo.Category.showCatList($id)\">[+]</a>\n");
			$page->add("<a class=\"expand hidden\" id=\"catlist-$id-hide\" href=\"javascript:SexInfo.Category.hideCatList($id)\">[-]</a>\n");

			$page->add("<a class=\"move move-$id movetype volatile hidden\" id=\"movetype-$id\" href=\"javascript:SexInfo.Category.moveToType($id)\">[here]</a>\n");

			$page->add("{$type['type_name']}\n");

			$page->add("<a class=\"new\" href=\"javascript:SexInfo.Category.newInType($id)\">[new]</a>\n");

			$page->add("</li>\n");

			$page->add("<ul class=\"catlist expand hidden\" id=\"catlist-$id\">\n");

			foreach($cats as $cat) {
				$page->add(output_category_for_ul($cat, $id));
			}

			$page->add("</ul>\n");
		}

		$result->close();
	
		$page->add("<li id=\"newform-li\" class=\"hidden\"><form id=\"newform\">Name <input type=\"text\" name=\"name\" id=\"newform-name\" />\n");
		$page->add("<input type=\"hidden\" name=\"parent\" value=\"\" id=\"newform-parent\" />\n");
		$page->add("<input type=\"hidden\" name=\"type\" value=\"\" id=\"newform-type\" />\n");
		$page->add("<input type=\"button\" value=\"add\" id=\"newform-button\" disabled=\"disabled\" onclick=\"SexInfo.Category.createNew();\">\n");
		$page->add("<span id=\"newform-waiting\" class=\"hidden\">working...</span>\n");
		$page->add("<a id=\"newform-cancel\" href=\"javascript:SexInfo.Category.cancelNew();\">[cancel]</a></form></li>");
		$page->add("</ol>\n");

		$page->output();
	}
	else
	{
		$security->redirect();
	}
?>
