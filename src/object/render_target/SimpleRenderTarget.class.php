<?php
/**
* Simple rendering target
*
* PHP version 5
*
* @package    objects.events
* @author     CharcoalPHP Development Team
* @copyright  2014 stk2k, sazysoft
*/

class Charcoal_SimpleRenderTarget extends Charcoal_AbstractRenderTarget implements Charcoal_IRenderTarget
{
    /**
     *    render buffer
     *
     * @param Charcoal_String|string $buffer    rendering data
     */
    public function render( $buffer )
    {
        Charcoal_ParamTrait::validateString( 1, $buffer );

        $buffer = us( $buffer );

        if ( is_string($buffer) ){
            echo $buffer;
        }
    }
}

