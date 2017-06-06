<div id="articlespread">
	<div id="leftcol">
		<h5>Recently Published</h5>

		<?php
		$jsonData = file_get_contents("modules/carousel-links.json", true);
		$phpArray = json_decode($jsonData, true);

		print "<ul>";
		foreach($phpArray as $val) {
			print "<li class='recentlyPublished'>
						<a href='". $val['url'] ."'>
						<img src='sites/all/themes/magazeen_lite/images/modules/". $val['image'] ."''></img>

						<p> ". $val['title']." </p>
					
						<div style='clear: both'></div>
				</a>
			</li>";
		}
		print "</ul>";

		?>

	</div>

	<div id="middlecol">
		<h5>Pinned Articles</h5>
		<?php
		foreach($phpArray as $val) {
			print "<div class='panel'>
						<a href='". $val['url'] ."'>
						<img src='sites/all/themes/magazeen_lite/images/modules/". $val['image'] ."''></img>
					
						<div>
							<h4> ". $val['title']." </h4>
						</div>
					

				</a>
			</div>";
		}

		?>
	</div>

	<div id="rightcol">
		<h5>Helpful Information</h5>
		<ul>
			<li class="largepanel">
				<a href="/sexinfo/ppq">
					<img src="sites/all/themes/magazeen_lite/images/modules/ppq-front-page.jpg" width="650px"></img>
					<div>
						<div><h3>Could you be pregnant?</h3></div>
						
						<p>We've created a brand new pregnancy questionnaire that will help estimate your likelihood of pregnancy.</p>
					</div>
				</a>
			</li>

			<li class="largepanel">
				<a href="/sexinfo/quizzes">
					<img src="sites/all/themes/magazeen_lite/images/modules/love-relationships.jpg" width="650px"></img>
					<div>
						<div><h3>Test Your Knowledge</h3></div>
						<p>Do you think you know it all? Are you an expert on masturbation, LGBTQ facts, paraphilias, or pregnancy and abortion? Quiz yourself here to see if you really are.</p>
					</div>
				</a>
			</li>
		</ul>
	</div>

	<div style="clear: both;"></div>
</div>





<!-- "/sites/all/themes/magazeen_lite/images/modules/" -->