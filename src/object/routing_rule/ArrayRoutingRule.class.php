<?php
/**
* Array Routing Rule
*
* PHP version 5
*
* @package    objects.routing_rules
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/
class Charcoal_ArrayRoutingRule extends Charcoal_AbstractRoutingRule
{
    const TAG = 'array_routing_rule';

    private $proc_paths;

    /*
     * Construct object
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Initialize instance
     *
     * @param array $config   configuration data
     */
    public function configure( $config )
    {
        parent::configure( $config );
        
        $config = new Charcoal_ConfigPropertySet( $this->getSandbox()->getEnvironment(), $config );

        $rules_section = $config->getSection( 'routing rules' );

        $patterns = $rules_section->getKeys();
    
        $this->proc_paths = array();

        foreach( $patterns as $pattern ){
            $proc_path = $rules_section->getString( $pattern );
            if ( $proc_path === NULL ){
                _throw( new Charcoal_RoutingRuleConfigException( $pattern, 'can not be NULL' ) );
            }
            $this->proc_paths[$pattern] = us($proc_path);
        }

        log_info( 'system,debug,router', "proc_paths:" . print_r($this->proc_paths,true), self::TAG );
    }

    /*
     *  Get all keys
     *
     * @return array
     */
    public function getKeys()
    {
        return array_keys( $this->proc_paths );
    }

    /*
     *  Get procedure path associated with a pattern
     *
     * @return string
     */
    public function getProcPath( Charcoal_String $pattern )
    {
        $pattern = us($pattern);

        return isset($this->proc_paths[$pattern]) ? $this->proc_paths[$pattern] : NULL;
    }

}

