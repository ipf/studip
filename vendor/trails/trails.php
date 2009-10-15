<?php
 define('TRAILS_VERSION','0.6.2');class Trails_Dispatcher{public$trails_root;public$trails_uri;public$default_controller;function __construct($trails_root,$trails_uri,$default_controller){$this->trails_root=$trails_root;$this->trails_uri=$trails_uri;$this->default_controller=$default_controller;}function dispatch($uri){$old_handler=set_error_handler(array($this,'error_handler'),E_ALL);ob_start();$level=ob_get_level();$this->map_uri_to_response($this->clean_request_uri((string)$uri))->output();while(ob_get_level()>=$level){ob_end_flush();}if(isset($old_handler)){set_error_handler($old_handler);}}function map_uri_to_response($uri){try{if(''===$uri){if(!$this->file_exists($this->default_controller.'.php')){throw new Trails_MissingFile("Default controller '{$this->default_controller}' not found'");}$controller_path=$this->default_controller;$unconsumed=$uri;}else{list($controller_path,$unconsumed)=$this->parse($uri);}$controller=$this->load_controller($controller_path);$response=$controller->perform($unconsumed);}catch(Exception$e){$response=isset($controller)?$controller->rescue($e):$this->trails_error($e);}return$response;}function trails_error($exception){ob_clean();$detailed=$_SERVER['REMOTE_ADDR']==='127.0.0.1';$body=sprintf('<html><head><title>Trails Error</title></head>'.'<body><h1>%s</h1><pre>%s</pre></body></html>',htmlentities($exception->__toString()),$detailed?htmlentities($exception->getTraceAsString()):'');if($exception instanceof Trails_Exception){$response=new Trails_Response($body,$exception->headers,$exception->getCode(),$exception->getMessage());}else{$response=new Trails_Response($body,array(),500,$exception->getMessage());}return$response;}function clean_request_uri($uri){if(FALSE!==($pos=strpos($uri,'?'))){$uri=substr($uri,0,$pos);}return ltrim($uri,'/');}function parse($unconsumed,$controller=NULL){list($head,$tail)=$this->split_on_first_slash($unconsumed);if(!preg_match('/^\w+$/',$head)){throw new Trails_RoutingError("No route matches '$head'");}$controller=(isset($controller)?$controller.'/':'').$head;if($this->file_exists($controller.'.php')){return array($controller,$tail);}else if($this->file_exists($controller)){return$this->parse($tail,$controller);}throw new Trails_RoutingError("No route matches '$head'");}function split_on_first_slash($str){$pos=strpos($str,'/');if($pos!==FALSE){return array(substr($str,0,$pos),substr($str,$pos+1));}return array($str,'');}function file_exists($path){return file_exists("{$this->trails_root}/controllers/$path");}function load_controller($controller){require_once"{$this->trails_root}/controllers/{$controller}.php";$class=Trails_Inflector::camelize($controller).'Controller';if(!class_exists($class)){throw new Trails_UnknownController("Controller missing: '$class'");}return new$class($this);}function error_handler($errno,$string,$file,$line,$context){if(!($errno&error_reporting())){return;}if($errno==E_NOTICE||$errno==E_WARNING||$errno==E_STRICT){return FALSE;}$e=new Trails_Exception(500,$string);$e->line=$line;$e->file=$file;throw$e;}}class Trails_Response{public$body='',$status,$reason,$headers=array();function __construct($body='',$headers=array(),$status=NULL,$reason=NULL){$this->set_body($body);$this->headers=$headers;if(isset($status)){$this->set_status($status,$reason);}}function set_body($body){$this->body=$body;return$this;}function set_status($status,$reason=NULL){$this->status=$status;$this->reason=isset($reason)?$reason:$this->get_reason($status);return$this;}function get_reason($status){$reason=array(100=>'Continue','Switching Protocols',200=>'OK','Created','Accepted','Non-Authoritative Information','No Content','Reset Content','Partial Content',300=>'Multiple Choices','Moved Permanently','Found','See Other','Not Modified','Use Proxy','(Unused)','Temporary Redirect',400=>'Bad Request','Unauthorized','Payment Required','Forbidden','Not Found','Method Not Allowed','Not Acceptable','Proxy Authentication Required','Request Timeout','Conflict','Gone','Length Required','Precondition Failed','Request Entity Too Large','Request-URI Too Long','Unsupported Media Type','Requested Range Not Satisfiable','Expectation Failed',500=>'Internal Server Error','Not Implemented','Bad Gateway','Service Unavailable','Gateway Timeout','HTTP Version Not Supported');return isset($reason[$status])?$reason[$status]:'';}function add_header($key,$value){$this->headers[$key]=$value;return$this;}function output(){if(isset($this->status)){$this->send_header(sprintf('HTTP/1.1 %d %s',$this->status,$this->reason),TRUE,$this->status);}foreach($this->headers as$k=>$v){$this->send_header("$k: $v");}echo$this->body;}function send_header($header,$replace=FALSE,$status=NULL){if(isset($status)){header($header,$replace,$status);}else{header($header,$replace);}}}class Trails_Controller{protected$dispatcher,$response,$performed,$layout;function __construct($dispatcher){$this->dispatcher=$dispatcher;$this->erase_response();}function erase_response(){$this->performed=FALSE;$this->response=new Trails_Response();}function get_response(){return$this->response;}function perform($unconsumed){list($action,$args)=$this->extract_action_and_args($unconsumed);$before_filter_result=$this->before_filter($action,$args);if(!(FALSE===$before_filter_result||$this->performed)){$mapped_action=$this->map_action($action);if(method_exists($this,$mapped_action)){call_user_func_array(array(&$this,$mapped_action),$args);}else{$this->does_not_understand($action,$args);}if(!$this->performed){$this->render_action($action);}$this->after_filter($action,$args);}return$this->response;}function extract_action_and_args($string){if(''===$string){return array('index',array());}$args=explode('/',$string);$action=array_shift($args);return array($action,$args);}function map_action($action){return$action.'_action';}function before_filter(&$action,&$args){}function after_filter($action,$args){}function does_not_understand($action,$args){throw new Trails_UnknownAction("No action responded to '$action'.");}function redirect($to){if($this->performed){throw new Trails_DoubleRenderError();}$this->performed=TRUE;$url=preg_match('#^(/|\w+://)#',$to)?$to:$this->url_for($to);$this->response->add_header('Location',$url)->set_status(302);}function render_text($text=' '){if($this->performed){throw new Trails_DoubleRenderError();}$this->performed=TRUE;$this->response->set_body($text);}function render_nothing(){$this->render_text('');}function render_action($action){$class=get_class($this);$controller_name=Trails_Inflector::underscore(substr($class,0,-10));$this->render_template($controller_name.'/'.$action,$this->layout);}function render_template($template_name,$layout=NULL){$factory=new Flexi_TemplateFactory($this->dispatcher->trails_root.'/views/');$template=$factory->open($template_name);switch(get_class($template)){case'Flexi_JsTemplate':$this->set_content_type('text/javascript');break;}$template->set_attributes($this->get_assigned_variables());if(isset($layout)){$template->set_layout($layout);}$this->render_text($template->render());}function get_assigned_variables(){$assigns=array();$protected=get_class_vars(get_class($this));foreach(get_object_vars($this)as$var=>$value){if(!array_key_exists($var,$protected)){$assigns[$var]=&$this->$var;}}$assigns['controller']=$this;return$assigns;}function set_layout($layout){$this->layout=$layout;}function url_for($to){$args=func_get_args();$args=array_map('urlencode',$args);$args[0]=$to;return$this->dispatcher->trails_uri.'/'.join('/',$args);}function set_status($status,$reason_phrase=NULL){$this->response->set_status($status,$reason_phrase);}function set_content_type($type){$this->response->add_header('Content-Type',$type);}function rescue($exception){return($this->response=$this->dispatcher->trails_error($exception));}}class Trails_Inflector{static function camelize($word){$parts=explode('/',$word);foreach($parts as$key=>$part){$parts[$key]=str_replace(' ','',ucwords(str_replace('_',' ',$part)));}return join('_',$parts);}static function underscore($word){$parts=explode('_',$word);foreach($parts as$key=>$part){$parts[$key]=preg_replace('/(?<=\w)([A-Z])/','_\\1',$part);}return strtolower(join('/',$parts));}}class Trails_Flash implements ArrayAccess{public$flash=array(),$used=array();static function instance(){if(!isset($_SESSION)){throw new Trails_SessionRequiredException();}if(!isset($_SESSION['trails_flash'])){$_SESSION['trails_flash']=new Trails_Flash();}return$_SESSION['trails_flash'];}function offsetExists($offset){return isset($this->flash[$offset]);}function offsetGet($offset){return$this->get($offset);}function offsetSet($offset,$value){$this->set($offset,$value);}function offsetUnset($offset){unset($this->flash[$offset],$this->used[$offset]);}function _use($k=NULL,$v=TRUE){if($k){$this->used[$k]=$v;}else{foreach($this->used as$k=>$value){$this->_use($k,$v);}}}function discard($k=NULL){$this->_use($k);}function&get($k){$return=NULL;if(isset($this->flash[$k])){$return=&$this->flash[$k];}return$return;}function keep($k=NULL){$this->_use($k,FALSE);}function set($k,$v){$this->keep($k);$this->flash[$k]=$v;}function set_ref($k,&$v){$this->keep($k);$this->flash[$k]=&$v;}function sweep(){foreach(array_keys($this->flash)as$k){if($this->used[$k]){unset($this->flash[$k],$this->used[$k]);}else{$this->_use($k);}}$fkeys=array_keys($this->flash);$ukeys=array_keys($this->used);foreach(array_diff($fkeys,$ukeys)as$k=>$v){unset($this->used[$k]);}}function __toString(){$values=array();foreach($this->flash as$k=>$v){$values[]=sprintf("'%s': [%s, '%s']",$k,var_export($v,TRUE),$this->used[$k]?"used":"unused");}return"{".join(", ",$values)."}\n";}function __sleep(){$this->sweep();return array('flash','used');}function __wakeUp(){$this->discard();}}class Trails_Exception extends Exception{public$headers;function __construct($status=500,$reason=NULL,$headers=array()){if($reason===NULL){$reason=Trails_Response::get_reason($status);}parent::__construct($reason,$status);$this->headers=$headers;}function __toString(){return"{$this->code} {$this->message}";}}class Trails_DoubleRenderError extends Trails_Exception{function __construct(){$message="Render and/or redirect were called multiple times in this action. "."Please note that you may only call render OR redirect, and at most "."once per action.";parent::__construct(500,$message);}}class Trails_MissingFile extends Trails_Exception{function __construct($message){parent::__construct(500,$message);}}class Trails_RoutingError extends Trails_Exception{function __construct($message){parent::__construct(400,$message);}}class Trails_UnknownAction extends Trails_Exception{function __construct($message){parent::__construct(404,$message);}}class Trails_UnknownController extends Trails_Exception{function __construct($message){parent::__construct(404,$message);}}class Trails_SessionRequiredException extends Trails_Exception{function __construct(){$message="Tried to access a non existing session.";parent::__construct(500,$message);}}