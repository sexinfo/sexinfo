<?php
/**********************************************************************//**\file
	Navigation Class

	Description: Navigation list processing and output; builds category lists
		for the current article and outputs them to the main page
*******************************************************************************/

	class navigation_tab
	{
		private $tabs = array(
			'Home' => '/sexinfo/',
			'SexInfo Topics' => '/sexinfo/category/',
			'Q&amp;A\'s' => '/sexinfo/question/',
			'Ask the Sexperts' => '/sexinfo/submit.php',
			'Frequently Asked Questions' => '/sexinfo/article/frequently-asked-questions',
		);
		private $output = '';
		
		public function __construct()
		{
			$this->build_tabs();
		}
		
		private function build_tabs()
		{
			foreach($this->tabs as $key => $var)
			{
				$request = explode('/', $_SERVER['REQUEST_URI']);

				# set default case, change if there is a match
				$current = FALSE;
				
				if($var == $_SERVER['REQUEST_URI'])
				{
					$current = TRUE;
					$this->unique = FALSE;
				}
				else
				{
					if($request[2] == 'article' && $key == 'Articles' && $request[3] != 'etc' && $request[3] != 'emergency-numbers' && $request[3] != 'frequently-asked-questions')
						$current = TRUE;
					if($request[2] == 'question' && $key == 'Questions and Answers')
						$current = TRUE;
					if($request[2] == 'category' && $key == 'Categories')
						$current = TRUE;
				}
				
				if($current)
					$this->output.='<li class="current_page_item"><a href="'.$var.'">'.$key.'</a></li>';
				else
					$this->output.='<li><a href="'.$var.'">'.$key.'</a></li>';
			}
		}
		
		public function output()
		{
			return $this->output;
		}
	}
	

?>