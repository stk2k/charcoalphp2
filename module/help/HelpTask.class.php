<?php
/**
* タスク
*
* PHP version 5
*
* @package    renderers
* @author     stk2k <stk2k@sazysoft.com>
* @copyright  2008 stk2k, sazysoft
*/

class HelpTask extends Charcoal_Task
{
    /**
     * process event
     *
     * @param Charcoal_IEventContext $context   event context
     *
     * @return boolean
     */
    public function processEvent( $context )
    {
        $request   = $context->getRequest();

        // get command line options
        $cmd_path        = us( $request->getString( 'p2' ) );

        $options = array(
            '@:help' => '[command_path]',
            '@:version' => '',
            '@:db:generate:model' => 'databse table [target directory]',
            '@:db:show:table' => 'databse table',
        );
        $examples1 = array(
            '@:help' => '@:version => show "@:version" command help',
            '@:db:generate:model' => 'charcoal blog => generate "blog" table\'s model files in "charcoal" database' .
                                '(model files are generated into current directory).',
            '@:db:show:table' => 'charcoal blog => show description about "blog" table in "charcoal" database.',
        );
        $examples2 = array(
            '@:help' => 'list => show all supported commands("list" can be omitted)',
        );
        $descriptions = array(
            '@:help' => 'show command help or list all command paths',
            '@:version' => 'show framework version.',
            '@:db:generate:model' => 'create model files into [target directory].',
            '@:db:show:table' => 'show table description',
        );

        if ( empty($cmd_path) || $cmd_path == 'list' ){
            // show all commands
            echo "Supported command list: ";
            foreach( $options as $path => $opt ){
                echo "\n  " . $path;
            }
        }
        elseif ( isset($options[$cmd_path]) ){
            echo "How to use: ";
            echo "\n  charcoal " . $cmd_path . ' ' . $options[$cmd_path];
            if ( isset($examples1[$cmd_path]) ){
                echo "\nExample:";
                echo "\n  charcoal " . $cmd_path . ' ' . $examples1[$cmd_path];
                if ( isset($examples2[$cmd_path]) ){
                    echo "\n  charcoal " . $cmd_path . ' ' . $examples2[$cmd_path];
                }
            }
            if ( isset($descriptions[$cmd_path]) ){
                echo "\n\nThis command " . $descriptions[$cmd_path];
            }
        }
        else{
            echo "Command not found: $cmd_path";
        }
        echo "\n";

        return b(true);
    }
}

return __FILE__;