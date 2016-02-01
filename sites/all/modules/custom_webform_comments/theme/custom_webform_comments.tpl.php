<?php

$content = $variables['data'];
$row = array();
$output = '';
foreach($content as $row) {
   $output .= '<div class="admin-comment">';
			if($row['subject']) {
			   $output .= '<div class="admin-comment-subject">Subject: '.$row['subject'].'</div>';
				}
    $output .= '<div class="admin-comment-body">'.$row['comment'].'</div>';
				$commenter = user_load($row['commenter_user_id']);
				$output .= '<div class="admin-comment-byline"> by '.l($commenter->name, 'user/'.$commenter->uid).' on '.date("F j, Y, g:i a", strtotime($row['ts'])).'</div>';
				$output .= '</div>';
}
print $output;