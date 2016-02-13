<?php
  /*
   * I was going crazy writing code like <?php print "<div class=\"sidebar\">". $content ."</div>"; ?>
   * so I wrote a utility function for it called content_tag(). Just going to leave it here for now. -Andrew
  */

  function generate_attr_string($attr) {
    $attr_string = null;
    if (!empty($attr)) {
      foreach ($attr as $key => $value) {
        // If we have attributes, loop through the key/value pairs passed in and append result HTML
        // to a string that gets added into the opening tag
        $attr_string .= $key . "=" . '"' . $value . '" ';
      }
    }
    return $attr_string;
  }

  function open_tag($tag, $attr) {
    // Utility function for content_tag()
    // returns an opening HTML tag with attributes from parameter
    $attr_string = generate_attr_string($attr);
    return "<" . $tag . " " . $attr_string . ">";
  }

  function close_tag($tag) {
    // Utility function for content_tag(). Returns an closing HTML tag
    return "</" . $tag . ">";
  }

  function single_tag($tag, $attr=array()) {
    // Utility function for content_tag(). Returns a HTML tag (like <hr />) with attributes applied
    $attr_string = generate_attr_string($attr);
    return "<" . $tag . " " . $attr_string . " />";
  }

  function content_tag($tagName, $content, $attr=array()) {
    /*
     * Description: Facilitates creating HTML tags with dynamic content.
     * Parameters: $tagName, $content, $attr=array()
     *  - $tagName: string; the HTML tag, ex: "div"
     *  - $content: string; the content to wrap in tags
     *  - $attr: array; a list of attributes to add to the tag, ex: array( "id" => "sidebar", "class" => "nav" )
     *     - default value: null
     * Example call:
     *    $content = mysql_query($query); // Anything really
     *    content_tag("div", $content, array("class" => "user-info"));
    */
    return open_tag($tagName, $attr) . $content . close_tag($tagName);
  }

?>
