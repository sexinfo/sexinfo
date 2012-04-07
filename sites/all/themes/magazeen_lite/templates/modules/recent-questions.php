<h2>Recent Questions</h2>           

<div class="column-container dark">

  <div class="question">
    <h4>What Happened to Me?</h4>             
    <p class="date">May 23, 2011</p>
    <p>Question: Dear Sexperts, I stayed with a friend and family member in a hotel room. We were only making out and touching. I then passed out completely for at least 2-3 hours. I had been drinking, but I was not drunk. When we got up from the bed, there was blood on the mattress. Since the sheets were off, I did not know if it was possible for me to &hellip;</p>
    <a href="/sexinfo/node/$nid" class="readmore">Read More &raquo;</a>
  </div><!-- .question (STATIC) -->
  
  <div class="question">
    <h4>Should I Let My Partner Cheat?</h4>             
    <p class="date">May 23, 2011</p>
    <p>Dear Sexperts, My girlfriend wants to have sex with another guy. Just sex. Just once. She wants to know what it's like to cheat. What do I do? -Male, 22, California Answer: It is not uncommon for partners to experience curiosity and to have desires outside of their relationship. However, every relationship is unique. Some prefer an open relatio &hellip;</p>
    <a href="/sexinfo/node/$nid" class="readmore">Read More &raquo;</a>
  </div><!-- .question (STATIC) -->
  
  <div class="question">
    <h4>First Time Sex Worries?</h4>              
    <p class="date">May 23, 2011</p>
    <p>Question Dear Sexperts, I am 19 and had sex for the first time late last night. It was harder than expected to get it in. Can stress or other thoughts influence the effect? Also, I realized he took off the condom before we finally got it in but he did pull out...I've been on birth control for a few years, should I be worried about getting pregnant and &hellip;</p>
    <a href="/sexinfo/node/$nid" class="readmore">Read More &raquo;</a>
  </div><!-- .question (STATIC) -->
      
  <?php       
    // Recent Questions module
    // Displays the three most recently authored Q/A's and a link to the full node                            
                    
    function format_time($time) {
      // Utility function to render a Unix timestamp in a readable format
      // Format: October 17, 2012
      // Note that %e (day as 1-31) does not work on Windows, so %#d is apparently a workaround.
      return strftime("%B %#d, %Y", $time);
    }
    function slice_teaser($body) {
      // Utility function that takes the full body of a question (string),
      // strips all HTML tags (since it's injected into a paragraph)
      // and cuts it off into a 350-char preview chunk
      return substr(strip_tags($body), 0, 350) . "&hellip;";
    }
                    
    // This is an absolutely horrible way to go about things.
    // Drupal provides DB wrapper functions that I can't figure out for the life of me, and I'm
    // pretty sure this sort of DB code should never be in a template file anyways. Bummer.               
    //mysql_connect("localhost", "sexweb00m", "249APWan") or die("Could not connect: " . mysql_error());
    //mysql_select_db("sexweb00");
    /*
     * Question title+timestamp is stored in table:node
     * but the question body is stored in table:field_data_field_question,
     * so we have to SQL join the two by id so that we have access to fields
     * from both tables.
     * See http://www.codinghorror.com/blog/2007/10/a-visual-explanation-of-sql-joins.html
    */
    /*
    $query = "
      SELECT * FROM node                    
      INNER JOIN field_data_field_question
      ON node.nid = field_data_field_question.entity_id
      ORDER BY created DESC
      LIMIT 3
    ";
    $result = mysql_query($query) or die(mysql_error());
    */
    
    // Note that = is used in the loop here instead of == which is very intentional
    // See http://www.tizag.com/mysqlTutorial/mysqlfetcharray.php
    /*while($row = mysql_fetch_array($result) ) {
      // Loop through returned question rows
      // Initialize content variables to be passed into content_tag()
      $nid = $row['nid'];
      $time = format_time($row['created']);
      $title = $row['title'];
      $teaser = slice_teaser($row['field_question_value']);
      $node_path = "/sexinfo/node/"; // Read More link: ex href="/sexinfo/node/28"                
      */

      /*
       - OUTPUTTED CODE STRUCTURE -
      <div class="question">
        <h4>Question Title</h4>             
        <p class="date">October 30, 2011</p>
        <p>Body of the question (Not the answer)</p>
        <a href="/sexinfo/node/$nid" class="readmore">Read More &raquo;</a>

      </div>                  
      */  
      /*                 
      print open_tag("div", array("class" => "question"));

      */                  
      /*print open_tag("div", array("class" => "question"));
        content_tag("h4", $title);
        content_tag("p", $time, array("class" => "date"));                                      
        content_tag("p", $teaser);
        content_tag("a", "Read More &raquo;", array("href" => $node_path . $nid, "class" => "readmore"));     
      print close_tag("div");                 
    }
  
      print close_tag("div");*/             
    //}                             
  ?>
  
</div><!-- .column-container.dark -->