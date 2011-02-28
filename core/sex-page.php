<?php
/**********************************************************************//**\file
	Page Output Class

	Description: Parses /theme/template.html for basic html layout, buffers
		content, and injects content into the template when the page is output
		to the user.
*******************************************************************************/

	class page
	{
		protected $pagedata = '';
		protected $content ='';
		protected $head = '';
		protected $warning = '';
		protected $navlist = '';

		/** Constructor grabs default template
		 *
		 * @param string $template (optional) specify filename+path to override default template file
		 */
		public function __construct($template = 'theme/template.html')
		{
			if (file_exists($template))
				$this->pagedata = file_get_contents($template);
			else
				die('The specified template, '.$template.', could not be found.');

			$this->hook_constructor();
		}

		public function __destruct()
		{

		}

/*******************************************************************************
	Hooks
*******************************************************************************/

		protected function hook_constructor()
		{

		}

		protected function hook_output()
		{

		}

/*******************************************************************************
	Public functions
*******************************************************************************/

		/** Replaces a given case-sensitive token in the template with user-specified text
		 *
		 * @param string $token String delineated with %'s indicating onmatch
		 * @param string $replacement String indicating replacement
		 */
		public function insert($token, $replacement)
		{
			$this->pagedata = str_replace($token, $replacement, $this->pagedata);
		}

		/** Injects custom html headers (extra stylesheets, metadata, etc.)
		 *
		 * @param <type> $header
		 */
		public function head($head)
		{
			$this->head = $head;
		}

		/** Injects page title
		 *
		 * @param <type> $title
		 */
		public function title($title = 'Insert Title')
		{
			$this->insert('%title%', $title);
		}

		/** Appends output to the body of the page (content area)
		 *
		 * @param <type> $input
		 */
		public function add($input)
		{
			$this->content .= $input;
		}

		/** Injects navigation menus
		 *
		 * @param string $list HTML-formatted list of navigation links
		 */
		public function navigation($list)
		{
			$this->navlist = $list;
		}

		/** Injects a site warning below the header
		 *
		 * @param string $warning Site warning shown to users
		 */
		public function warning($warning)
		{
			$this->warning = $warning;
		}

		/** Outputs the page (SENDS HTML HEADERS)
		 *
		 */
		public function output($return = FALSE)
		{
			
			$this->insert('%content%', $this->content);
			$this->insert('%head%', $this->head);
			$this->insert('%warning%', $this->warning);
			
			$tabs = new navigation_tab();
			$this->insert('%tabs%', $tabs->output());

			# Image hacks
			$this->insert('src="./images/', 'src="/sexinfo/images/');
			$this->insert('src="images/', 'src="/sexinfo/images/');
			$this->insert('SRC="./images/', 'src="/sexinfo/images/');
			$this->insert('SRC="images/', 'src="/sexinfo/images/');

			if($this->navlist == '')
			{
				$nav = new nav();
				$this->navigation($nav->output(1));
			}
			$this->output_navigation();

			# Execute hooks (if extended)
			$this->hook_output();

			# Testing Hack for LSIT
			#$this->insert('/sexinfo/', '/sexinfo/test/');
		
			
			
			if($return)
				return $this->pagedata;
			else
				echo $this->pagedata;
		}

		protected function output_navigation()
		{
			$this->insert('%navigation%', $this->navlist);
		}
		
		# thanks to: http://shiflett.org/blog/2005/oct/convert-smart-quotes-with-php

	}
?>