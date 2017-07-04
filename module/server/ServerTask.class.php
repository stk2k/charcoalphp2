<?php
/**
* Web Server Task
*
* PHP version 5
*
* @package    renderers
* @author     stk2k <stk2k@sazysoft.com>
* @copyright  2008 stk2k, sazysoft
*/

class ServerTask extends Charcoal_Task
{
    /**
     * process event
     *
     * @param Charcoal_IEventContext $context   event context
     *
     * @return boolean|Charcoal_Boolean
     */
    public function processEvent( $context )
    {
        $request   = $context->getRequest();
    
        // get parameter from CLI
        $host_port   = us($request->getString( 'p2' ));
        $webroot     = us($request->getString( 'p3' ));
        
        // if webroot is specified, apply default port
        if ( empty($webroot) ){
            $webroot = CHARCOAL_HOME . '/sample/public_html';
        }
    
        // create process
        $descriptorspec  = array(
            0 => array('pipe', 'r'),
            1 => array('pipe', 'w'),
            2 => array('pipe', 'w')
        );
        if (defined('PHP_WINDOWS_VERSION_MAJOR')){
            $proc = proc_open("cmd /c php -S $host_port -t $webroot", $descriptorspec, $pipes);
        }
        else{
            $proc = proc_open("php -S $host_port -t $webroot &", $descriptorspec, $pipes);
        }
    
        stream_set_blocking($pipes[1], 0);
        stream_set_blocking($pipes[2], 0);
    
        fwrite($pipes[0], "test");
        fclose($pipes[0]);
    
        $stdout = $stderr = '';
        while (feof($pipes[1]) === false || feof($pipes[2]) === false) {
            $read = array($pipes[1], $pipes[2]);
            $write = array();
            $except = array();
            $ret = stream_select( $read, $write, $except, $timeout = 1 );
            if ($ret === false) {
                // error
                break;
            } else if ($ret === 0) {
                // timeout
                continue;
            } else {
                foreach ($read as $sock) {
                    if ($sock === $pipes[1]) {
                        $stdout .= fread($sock, 4096);
                    } else if ($sock === $pipes[2]) {
                        $stderr .= fread($sock, 4096);
                    }
                }
            }
        }
        fclose($pipes[1]);
        fclose($pipes[2]);
        proc_close($proc);

        return b(true);
    }
}

return __FILE__;