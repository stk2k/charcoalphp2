<?php
/**
* Hello Task
*
* PHP version 5
*
* @package    renderers
* @author     stk2k <stk2k@sazysoft.com>
* @copyright  2008 stk2k, sazysoft
*/

class IndexTask extends Charcoal_Task
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
        // show HTML
        $html = array(
            '<!DOCTYPE html>',
            '<html lang="ja">',
            '<head>',
            '<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />',
            '<meta http-equiv="content-type" content="text/html; charset=utf-8" />',
            '<meta http-equiv="pragma" content="no-cache" />',
            '<title>CharcoalPHP Framework</title>',
            '<!-- <meta name="viewport" content="width=device-width, initial-scale=1.0"> -->',
            '<!-- <meta name="viewport" content="width=1024" /> -->',
            '<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">',
            '<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>',
            '<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>',
            '</head>',
            '<body>',
            '<style>',
            '#header {',
            '  padding: 20px;',
            '  background-color: teal;',
            '  margin-bottom: 20px;',
            '}',
            '.logo {',
            '  color: white;',
            '  font-weight: bold;',
            '  font-size: 24px;',
            '}',
            '#footer {',
            '  padding: 10px;',
            '  background-color: honeydew;',
            '  margin-top: 20px;',
            '}',
            '.copyright {',
            '  color: darkolivegreen;',
            '  font-size: 11px;',
            '}',
            '</style>',
            '<div id="header">',
            '  <div class="logo">CharcoalPHP Framework Samples</div>',
            '</div>',
            '<div class="container">',
            '    <div class="panel panel-warning">',
            '      <div class="panel-heading">Hello world</div>',
            '      <div class="panel-body">',
            '        <ul class="list-group">',
            '          <li class="list-group-item"><a href="/hello">/hello</a></li>',
            '        </ul>',
            '      </div>',
            '    </div>',
            '    <div class="panel panel-warning">',
            '      <div class="panel-heading">PHP Info</div>',
            '      <div class="panel-body">',
            '        <ul class="list-group">',
            '          <li class="list-group-item"><a href="/phpinfo">/phpinfo</a></li>',
            '        </ul>',
            '      </div>',
            '    </div>',
            '    <div class="panel panel-warning">',
            '      <div class="panel-heading">Calc</div>',
            '      <div class="panel-body">',
            '        <ul class="list-group">',
            '          <li class="list-group-item"><a href="/calc">/calc</a></li>',
            '        </ul>',
            '      </div>',
            '    </div>',
            '</div>',
            '<div id="footer">',
            '  <div class="copyright">CharcoalPHP Framework ver.' . Charcoal_Framework::getLongVersion() . '</div>',
            '  <div class="copyright">Copyright (c)2008-2018 CharcoalPHP team.</div>',
            '</div>',
            '</body>',
            '</html>',
        );
        
        foreach($html as $line){
            echo $line . PHP_EOL;
        }
        
        // return TRUE if processing the procedure success.
        return TRUE;
    }
}
