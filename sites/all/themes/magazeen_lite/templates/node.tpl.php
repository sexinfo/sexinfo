<div>
  <div id="node-<?php print $node->nid; ?>" class="node<?php if ($sticky) { print ' sticky'; } ?><?php if (!$status) { print ' node-unpublished'; } ?>">
  
    <?php print $user_picture; ?>
  
    <div class="node-meta clearfix">
    
      <?php print render($title_prefix); ?>
	  <!-- <h3 class="node-title left"></h3> -->
      <h3 class="node-title"><a href="<?php print $node_url ?>" title="<?php print $title; ?>"><?php print $title; ?></a></h3>
      <?php print render($title_suffix); ?>
	  <!-- <span class="submitted node-info right"></span> -->
      <span class="submitted node-info"><?php if ($display_submitted): ?><?php print $submitted; ?><?php endif; ?></span>
      
    </div><!--/node-meta-->

    <div class="node-box clearfix">
	
	  <?php 
	  /*
	  * This line was throwing up the following error:
	  * "Notice: Undefined property: stdClass::$comment_count
	  * I'm not sure why, but we don't use comments anyways so I'm disabling it.
	  * -Andrew
	  
      <h2 class="comments-header"><?php print $node->comment_count ?> <?php print t('Comments'); ?></h2>
	  */
	  ?>
	  
      <div class="node-content clearfix">
        
        <?php hide($content['links']); ?>
        <?php hide($content['comments']); ?>
        
        <?php print render($content); ?>
      
      </div><!--/node-content-->
    
      <div class="node-footer clearfix">
        <div class="meta">
          <?php
            // Query database table taxonomy_term_data and taxonomy_index
            // So I can get all terms from my node.
            $term = db_query('SELECT t.name, t.tid FROM {taxonomy_index} n LEFT JOIN  {taxonomy_term_data} t ON (n.tid = t.tid) WHERE n.nid = :nid', array(':nid' => $node->nid));

            // db_query in Drupal 7 returns a stdClass object. 
            // Value names are corresponding to the fields in your SQL query 
            //(in our case "t.name") AND t.tid for build path.
            $tags = '';
            foreach ($term as $record) {
              // I put l() function for make my links.
              $tags .= l($record->name, 'taxonomy/term/' . $record->tid);
            }
          ?>
          <div class="terms">
			<!--Tags + Category -->
			<span class="node-category">Category: </span>
			<?php print $tags; ?>			
		  </div>
          <?php if ($content['links']): ?>
			<div class="nodelinks">
				<!--Read More -->
				<?php print render($content['links']); ?>
			</div>
          <?php endif; ?>            
        </div><!--/meta-->
      </div><!--/node-footer-->
    </div><!--/node-box-->
  </div>
  <?php if ($content['comments']): ?>	
    <?php print render($content['comments']); ?>
  <?php endif; ?>
</div>

<!------------------------------------------------------------------------------------------------------------------>
<!------- EXAMPLE NODE STRUCTURE ------->
<!--
BASIC CLASS STRUCT
.node
.node-meta
h3.node-title
.contextual-links-wrapper
.node-box
.node-content
.node-footer
-->
<?php 
/*


<div id="node-13" class="node">

    <div class="node-meta clearfix">
	
		<h3 class="node-title left"><a href="/sexinfo/article/semen-testing-only" title="Semen (Testing only)">Semen (Testing only)</a></h3>

		<div class="contextual-links-wrapper">
			<ul class="contextual-links">
				<li class="node-edit first">
					<a href="/sexinfo/node/13/edit?destination=node">Edit</a>
				</li>
				<li class="node-delete last">
					<a href="/sexinfo/node/13/delete?destination=node">Delete</a>
				</li>
			</ul>
		</div>      
		
		<span class="submitted node-info right"></span>

    </div><!--/node-meta-->
	
    <div class="node-box clearfix">
	
      <div class="node-content clearfix">

        <div class="field field-name-body field-type-text-with-summary field-label-hidden">
			<div class="field-items">
				<div class="field-item even" property="content:encoded">
					<p>The ejaculate (also called semen, cum or the seminal fluids) is a combination of fluids from multiple sex glands inside the male. Semen contains sperm which are produced in the testes and expelled via the vas deferens.</p>
				</div>
			</div>
		</div>      

      </div><!--/node-content-->
	  
      <div class="node-footer clearfix">

        <div class="meta">
			<div class="terms"><a href="/sexinfo/category/body">The Body</a></div>
				<div class="nodelinks">
					<ul class="links inline">
						<li class="node-readmore first last">
							<a href="/sexinfo/article/semen-testing-only" rel="tag" title="Semen (Testing only)">Read more<span class="element-invisible"> about Semen (Testing only)</span></a>
						</li>
					</ul>
				</div>                   
        </div><!--/meta-->

      </div><!--/node-footer-->

    </div><!--/node-box-->

  </div><!--/#node-13.node-->
 */
 ?>