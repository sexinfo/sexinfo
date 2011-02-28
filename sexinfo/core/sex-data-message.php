<?php
/**********************************************************************//**\file
    <title>

    Description:  <description>
*******************************************************************************/

    class sex_message
    {
        private $id = null;
        private $type = null;
        private $parent_id = null;
        private $author_id = null;
        private $added = null;
        private $edited = 0;
        private $body = null;

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
                        message_type,
                        message_parent_id,
                        message_author_id,
                        message_added,
                        message_edited,
                        message_body
                    FROM sex_message
                    WHERE message_id = $item
                ");

				if(!$query_succeeded)
				{
					$this->error = "Object load failed: {$this->db->error}";
					return false;
				}

                $result = $this->db->result();

                $this->id = intval($result['message_id']);
                $this->type = $result['message_type'];
                $this->parent_id = intval($result['message_parent_id']);
                $this->author_id = intval($result['message_author_id']);
                $this->added = intval($result['message_added']);
                $this->edited = intval($result['message_edited']);
                $this->body = $result['message_body'];
                $this->author_id = intval($result['message_author_id']);

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
                !is_null($this->type) &&
                !is_null($this->parent_id) &&
                !is_null($this->author_id) &&
                !is_null($this->body))
            {
                /*
                message_id = {$this->id},
                message_type = '{$this->db->escape($this->type)}',
                message_parent_id = {$this->parent_id},
                message_author_id = {$this->author_id},
                message_added = {$this->added},
                message_edited = {$this->edited},
                message_body = '{$this->db->escape($this->body)}'
                */

				$time = time();

                if(is_null($this->id))
                {
                    # insert
                    if(!$this->db->query("
						INSERT INTO sex_message
						SET
							message_parent_id = {$this->parent_id},
							message_type = '{$this->db->escape($this->type)}',
							message_added = $time,
							message_body = '{$this->db->escape($this->body)}',
							message_author_id = {$this->author_id}
					"))
					{
						$this->error = "insert failed: {$this->db->error}";
						return false;
					}

					$this->id = $this->db->lastid();
					$this->added = $time;
                }
                else
                {
                    # update
                    if(!$this->db->query("
						UPDATE sex_message
						SET
							message_edited = $time,
							message_body = '{$this->db->escape($this->body)}',
						WHERE message_id = $this->id
						LIMIT 1"))
					{
						$this->error = "insert failed: {$this->db->error}";
						return false;
					}
                return true;
                }
            }
            else
            {
                $this->error = 'Insufficient data to save the object: ';

				foreach(array('type', 'parent_id', 'author_id', 'body') as $var)
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

        public function get_type()
        {
            return $this->type;
        }

        public function get_parent_id()
        {
            return $this->parent_id;
        }

        public function get_author_id()
        {
            return $this->author_id;
        }

        public function get_added()
        {
            return $this->added;
        }

        public function get_edited()
        {
            return $this->edited;
        }

        public function get_body()
        {
            return $this->body;
        }

        /*-- Setters ---------------------------------------------------------*/

        public function set_type($input) # VARCHAR(12)
        {
            if(!is_null($input) && strlen($input) <= 12)
            {
                $this->type = $input;
                return true;
        }
            else
            {
                $this->error = 'type must be <= 12 characters and !null';
                return false;
            }
        }

        public function set_parent_id($input) # INT
        {
            if(is_int($input))
            {
                $this->parent_id = $input;
                return true;
            }
            else
            {
                $this->error = 'parent_id must be type INT';
                return false;
            }
        }

        public function set_author_id($input) # INT
        {
            if(is_int($input))
            {
                $this->author_id = $input;
                return true;
            }
            else
            {
                $this->error = 'author_id must be type INT';
                return false;
            }
        }

        public function set_body($input) # TEXT
        {
            if(!is_null($input))
            {
                $this->body = $input;
                return true;
            }
            else
            {
                $this->error = 'body must be !null';
                return false;
            }
        }

        /*-- Extra Functions -------------------------------------------------*/


    }
?> 