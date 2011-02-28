<?php
/**********************************************************************//**\file
    Bug Data Model

    Description:  Provides an interface to bug report data
*******************************************************************************/

    class sex_bug
    {
        private $id = null;
        private $added = null;
        private $updated = null;
        private $reporter_id = null;
        private $fixer_id = null;
        private $assigned_to_id = null;
        private $status = 1;
        private $type = 0;
        private $priority = 0;
        private $title = null;
        private $useragent = null;
        private $url = null;
        private $description = null;

		private $reporter_name = null;
		private $assigned_to_name = null;
		private $fixer_name = null;

        protected $db = null;
        public $error = null;

        /*-- Global Functions ------------------------------------------------*/

        public function __construct($item = null)
        {
            $this->db = new database();

            if(!is_null($item))
            {
                $this->load($item);
            }
        }

        public function __destruct()
        {

        }

        private function load($item)
        {
            if(is_int($item))
            {
				$this->id = $item;

                $query_succeeded = $this->db->query("
                    SELECT
                        bug_added,
                        bug_updated,
                        bug_reporter_id,
						(SELECT Concat(user_first_name, ' ', user_last_name) FROM sex_user WHERE user_id = bug_reporter_id) as reporter_name,
                        bug_fixer_id,
						(SELECT Concat(user_first_name, ' ', user_last_name) FROM sex_user WHERE user_id = bug_fixer_id) as fixer_name,
                        bug_assigned_to_id,
						(SELECT Concat(user_first_name, ' ', user_last_name) FROM sex_user WHERE user_id = bug_assigned_to_id) as assigned_to_name,
                        bug_status,
                        bug_type,
                        bug_priority,
                        bug_title,
                        bug_useragent,
                        bug_url,
                        bug_description
                    FROM sex_bug
                    WHERE bug_id = $item
                ");

				if(!$query_succeeded)
				{
					$this->error = "Object load failed: {$this->db->error}";
					return false;
				}

                $result = $this->db->result();

                $this->added = intval($result['bug_added']);
				if(!is_null($result['bug_updated']))
					$this->updated = intval($result['bug_updated']);
                $this->reporter_id = intval($result['bug_reporter_id']);
				$this->reporter_name = $result['reporter_name'];
				if(!is_null($result['bug_fixer_id']))
				{
					$this->fixer_id = intval($result['bug_fixer_id']);
					$this->fixer_name = $result['fixer_name'];
				}
				if(!is_null($result['bug_assigned_to_id']))
				{
					$this->assigned_to_id = intval($result['bug_assigned_to_id']);
					$this->assigned_to_name = $result['assigned_to_name'];
				}
                $this->status = intval($result['bug_status']);
                $this->type = intval($result['bug_type']);
                $this->priority = intval($result['bug_priority']);
                $this->title = $result['bug_title'];
                $this->useragent = $result['bug_useragent'];
                $this->url = $result['bug_url'];
                $this->description = $result['bug_description'];

                return true;
            }
            else
            {
                $this->error = 'Object load failed: parameter must be type INT, or null to create a new one';
                return false;
            }
        }

        public function save()
        {
            if(
                !is_null($this->reporter_id) &&
                !is_null($this->status) &&
                !is_null($this->type) &&
                !is_null($this->priority) &&
                !is_null($this->title) &&
                !is_null($this->description))
            {
                /*
                bug_id = {$this->id},
                bug_added = {$this->added},
                bug_updated = {$this->updated},
                bug_reporter_id = {$this->reporter_id},
                bug_fixer_id = {$this->fixer_id},
                bug_assigned_to_id = {$this->assigned_to_id},
                bug_status = {$this->status},
                bug_type = {$this->type},
                bug_priority = {$this->priority},
                bug_title = {$this->title},
                bug_useragent = {$this->useragent},
                bug_url = {$this->url},
                bug_description = {$this->description}
                */

				$time = time();

                if(is_null($this->id))
                {
                    # insert
					$query_succeeded = $this->db->query("
						INSERT INTO sex_bug
						SET
							bug_added = $time,
							bug_reporter_id = {$this->reporter_id},
							bug_status = {$this->status},
							bug_type = {$this->type},
							bug_priority = {$this->priority},
							bug_title = '{$this->db->escape($this->title)}',
							bug_useragent = '{$this->db->escape($this->useragent)}',
							bug_url = '{$this->db->escape($this->url)}',
							bug_description = '{$this->db->escape($this->description)}'
					");

					if(!$query_succeeded)
					{
						$this->error = 'Insert failed: '.$this->db->error;
						return false;
					}

					$this->id = $this->db->lastid();
					$this->added = $time;
                }
                else
                {
					$set = '';
					$first = true;
					foreach(array('fixer_id', 'assigned_to_id', 'status', 'type', 'priority', 'title', 'useragent', 'url', 'description') as $var)
					{
						if(!$first)
							$set .= ',';
						if(is_null($this->{$var}))
							$set .= "bug_$var = NULL";
						elseif(is_numeric($this->{$var}))
							$set .= "bug_$var = {$this->{$var}}";
						else
							$set .= "bug_$var = '{$this->db->escape($this->{$var})}'";
						$first = false;
					}

                    # update
                    $query_succeeded = $this->db->query("
						UPDATE sex_bug
						SET
							bug_updated = $time,
							$set
						WHERE bug_id = $this->id
						LIMIT 1");

					if(!$query_succeeded)
					{
						$this->error = 'Update failed: '.$this->db->error;
						return false;
					}
					$this->updated = $time;
                }
                return true;
            }
            else
            {
				$this->error = 'insufficient data to save this object: ';
				
				foreach(array('reporter_id','status','type','priority','title','description') as $var)
				{
					if(is_null($this->{$var}))
						$this->error .= "$var = null,";
				}

                return false;
            }
        }

        /*-- Getters ---------------------------------------------------------*/

        public function get_id()
        {
            return $this->id;
        }

        public function get_added()
        {
            return $this->added;
        }

        public function get_updated()
        {
            return $this->updated;
        }

        public function get_reporter_id()
        {
            return $this->reporter_id;
        }

        public function get_fixer_id()
        {
            return $this->fixer_id;
        }

        public function get_assigned_to_id()
        {
            return $this->assigned_to_id;
        }

        public function get_status()
        {
            return $this->status;
        }

        public function get_type()
        {
            return $this->type;
        }

        public function get_priority()
        {
            return $this->priority;
        }

        public function get_title()
        {
            return $this->title;
        }

        public function get_useragent()
        {
            return $this->useragent;
        }

        public function get_url()
        {
            return $this->url;
        }

        public function get_description()
        {
            return $this->description;
        }

		public function get_reporter_name()
        {
            return $this->reporter_name;
        }

		public function get_assigned_to_name()
        {
            return $this->assigned_to_name;
        }

		public function get_fixer_name()
        {
            return $this->fixer_name;
        }

        /*-- Setters ---------------------------------------------------------*/

        public function set_reporter_id($input) # INT
        {
            # reporter user_id
            if(is_int($input))
            {
                $this->reporter_id = $input;
                return true;
            }
            else
            {
                $this->error = 'reporter_id must be type INT';
                return false;
            }
        }

        public function set_fixer_id($input) # INT
        {
            # fixer\'s user_id
            if(is_int($input))
            {
                $this->fixer_id = $input;
                return true;
            }
            else
            {
                $this->error = 'fixer_id must be type INT';
                return false;
            }
        }

        public function set_assigned_to_id($input) # INT
        {
            # assigned to user_id for fixing
            if(is_int($input))
            {
                $this->assigned_to_id = $input;
                return true;
            }
            else
            {
                $this->error = 'assigned_to_id must be type INT';
                return false;
            }
        }

        public function set_status($input) # TINYINT
        {
            # 0 = unspecified | 1 = open | 2 = assigned | 3 = fixed | 4 = closed
			switch($input)
			{
				case 0:
				case 1:
				case 2:
				case 3:
				case 4:
					$this->status = $input;
					return true;
				default:
					$this->error = 'status must be one of 0,1,2,3,4';
					return false;
			}
        }

        public function set_type($input) # TINYINT
        {
            # 0 = Unspecified | 1 = Problem | 2 = Enhancement | 3 = Todo
			switch($input)
			{
				case 0:
				case 1:
				case 2:
				case 3:
					$this->type = $input;
					return true;
				default:
					$this->error = 'type must be one of 0,1,2,3';
					return false;
			}
        }

        public function set_priority($input) # TINYINT
        {
            # 0 = Unspecified | 1 = Low | 2 = Medium | 3 = High | 4 = Critical
			switch($input)
			{
				case 0:
				case 1:
				case 2:
				case 3:
				case 4:
					$this->priority = $input;
					return true;
				default:
					$this->error = 'priority must be one of 0,1,2,3,4';
					return false;
			}
        }

        public function set_title($input) # VARCHAR(60)
        {
            # Brief title / description of the bug
            if(!is_null($input) && strlen($input) >= 5 && strlen($input) <= 60)
            {
                $this->title = $input;
                return true;
            }
            else
            {
                $this->error = 'title must be between 5 and 60 characters';
                return false;
            }
        }

        public function set_useragent($input) # VARCHAR(120)
        {
            # Useragent of reporter
            if(strlen($input) <= 120)
            {
                $this->useragent = $input;
                return true;
            }
            else
            {
                $this->error = 'user agent must be <= 120 characters';
                return false;
            }
        }

        public function set_url($input) # VARCHAR(120)
        {
            # URL to problem page, if specified (usually referred url)
            if(strlen($input) <= 120)
            {
                $this->url = $input;
                return true;
            }
            else
            {
                $this->error = 'url must be <= 120 characters';
                return false;
            }
        }

        public function set_description($input) # TEXT
        {
            # Detailed description of the problem / feature request
            if(!is_null($input) && strlen($input) >= 10)
            {
                $this->description = $input;
                return true;
            }
            else
            {
                $this->error = 'description must be >= 10 characters and !null';
                return false;
            }
        }

        /*-- Extra Functions -------------------------------------------------*/


    }
?>