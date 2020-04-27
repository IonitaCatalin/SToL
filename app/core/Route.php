<?php
class Route
{
	
	private $method;				
	private $pattern;				
	private $optional_functions; 	
	private $function; 			
	private $param; 				

	function __construct($args)
	{
		$this->method = array_shift($args);
		$this->pattern = array_shift($args);
		$this->function = array_pop($args);
		$this->optional_functions = $args;
		$this->param = array();
	}

	public function methodMatches($method) {
		if ( $this->method == $method )
			return true;
		else
			return false;
	}

	public function patternMatches($URI) {
        //Parametrii din URI ,de tipul /api/:id
	    preg_match_all('@:([\w]+)@', $this->pattern, $param_names, PREG_PATTERN_ORDER);
	    $param_names = $param_names[0];

	
        $pattern_as_regex = preg_replace_callback('@:[\w]+@', array($this, 'convertPatternToRegex'), $this->pattern);
	    if ( substr($this->pattern, -1) === '/' ) {
	        $pattern_as_regex = $pattern_as_regex . '?';
	    }
	    $pattern_as_regex = '@^' . $pattern_as_regex . '$@';
	    if ( preg_match($pattern_as_regex, $URI, $param_values) ) {
	        array_shift($param_values);
	        foreach ( $param_names as $index => $value ) {
	            $val = substr($value, 1);
	            if ( isset($param_values[$val]) ) {
	                $this->param[$val] = urldecode($param_values[$val]);
	            }
	        }
	        return true;
	    }
	    return false;
	}

	private function convertPatternToRegex( $matches ) {
	    $key = str_replace(':', '', $matches[0]);
		return '(?P<' . $key . '>[a-zA-Z0-9_\-\.\!\~\*\\\'\(\)\:\@\&\=\$\+,%]+)';
	}

	public function run() {
		foreach ($this->optional_functions as $function) {
			if (is_callable($function))
				call_user_func($function);
		}

		if (is_callable($this->function)) {
		    call_user_func_array($this->function, array_values($this->param));
		    return true;
		}
		return false;
	}

}