<?php
class Configs
{
	var $_props = array();

    /**
     * Constructor.
     *
     * @param string $file
     * @param values If $values == null, then the object will be initialized empty.
     * If it contains a valid PHP array, all the properties will be initialized at once.
     */
	function Configs($file = null,  $values = null ) {
        $this->_load(SI_CFG . 'config.php');
        $this->_load(SI_CFG . 'database.php');
        if($file){
            $this->_load($file);
            if($values && is_array($values)){
                array_merge($this->_props, $values);
            }
        }
	}
	
	function _load($file){
        if(!file_exists($file))
            return false;
		require($file);
		if(isset($configs) && is_array($configs)){
			$this->_props = array_merge($this->_props, $configs);
		}
	}
	
	static function & getInstance(){
		static $instance = array();
		if(!isset($instance[0])){
			$instance[0] = new Configs();
		}
		return $instance[0];
	}

    /**
     * Sets a value in our hash table.
     *
     * @param key Name of the value in the hash table
     * @param value Value that we want to assign to the key '$key'
     */
	static function set( $key, $value = null ){
		$_this = & Configs::getInstance();
		if(!is_array($key)) {
			$name = $_this->_configVarNames($key);
			if(count($name) > 1){
				$_this->_props[$name[0]][$name[1]] = $value;
			} else {
				$_this->_props[$name[0]] = $value;
			}
		} else {
			foreach($key as $names => $value){
				$name = $_this->_configVarNames($names);
				if(count($name) > 1){
					$_this->_props[$name[0]][$name[1]] = $value;
				} else {
					$_this->_props[$name[0]] = $value;
				}
			}
		}
	}

    /**
     * Returns the value associated to a key
     *
     * @param key Key whose value we want to fetch
	 * @param defaultValue value that we should return in case the one we're looking for
	 * is empty or does not exist
     * @return Value associated to that key
     */
	static function get( $key, $defaultValue = null ){
		$instance = & Configs::getInstance();
  		$name = $instance->_configVarNames($key);
		if(count($name) > 1){
			if(isset($instance->_props[$name[0]][$name[1]]) ) {
	            return $instance->_props[$name[0]][$name[1]];
	        } 
	    } else {
	    	if(isset($instance->_props[$name[0]])) {
	            return $instance->_props[$name[0]];
	    	}
	    }
	    return $defaultValue;
	}
	
	
	/**
	 * Checks $name for dot notation to create dynamic Configure::$var as an array when needed.
	 *
	 * @param mixed $name
	 * @return array
	 * @access private
	 */
	function _configVarNames($name) {
		if (is_string($name)) {
			if (strpos($name, ".")) {
				$name = explode(".", $name);
			} else {
				$name = array($name);
			}
		}
		return $name;
	}

	/**
	 * Method overwritten from the Object class
     * @return Returns a nicer representation of our contents
	 */
	function toString(){
		$instance = & Configs::getInstance();
		print_r( $instance->_props );
	}
}

?>
