<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Controller that implements the AMFPHP gateway
 * and configures it using our custom Config Object.
 *
 * @package AMF
 * @category Controller
 * @author Lowgain
 */
class Controller_Site_Amf extends Controller {
	
	public function action_gateway()
	{
		require_once Kohana::find_file('vendor/amfphp/Amfphp', 'ClassLoader');
		
		// AMFPHP Config File reading from Kohana Config File.
		// Configured to use Kohana's filesystem to look for services.
		$config = new Amf_Config; 
		
		// Standard AMFPHP 2.0 Initialization
		$gateway = Amfphp_Core_HttpRequestGatewayFactory::createGateway($config);
		
		$gateway->service();
		$gateway->output();
	}
	
}