<?php
/**
* Simple Token Generator
*
* PHP version 5
*
* @package    objects.token_generators
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/
class Charcoal_SimpleTokenGenerator extends Charcoal_AbstractTokenGenerator
{
    private $algorithm;

    /*
     * コンストラクタ
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
        
        $config = new Charcoal_HashMap($config);

        $this->algorithm  = $config->getString( 'algorithm', 'sha1' );

        log_debug( "debug", "token algorithm: {$this->algorithm}" );
    }

    /**
     * generate a token
     */
    public function generateToken()
    {
        $algorithm = us($this->algorithm);

        $token = '';
        switch( $algorithm )
        {
        case 'sha1':
            $token = Charcoal_System::hash( 'sha1' );
            break;
        case 'md5':
            $token = Charcoal_System::hash( 'md5' );
            break;
        }

        return $token;
    }


}

