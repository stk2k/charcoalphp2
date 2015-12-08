<?php
/**
* レイアウトレンダリングイベント
*
* PHP version 5
*
* @package    objects.events
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_RenderLayoutEvent extends Charcoal_UserEvent implements Charcoal_IEvent
{
    private $layout;
    private $values;
    private $render_target;

    /*
     *    コンストラクタ
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Initialize instance
     *
     * @param Charcoal_Config $config   configuration data
     */
    public function configure( $config )
    {
        parent::configure( $config );

        $config->set( 'priority', Charcoal_EnumEventPriority::VIEW_RENDERING );
    }

    /**
     * get layout name or key which is specified by the template system
     *
     * @return Charcoal_Layout        layout name or key which is specified by the template system
     */
    public function getLayout()
    {
        return $this->layout;
    }

    /**
     * set layout name or key which is specified by the template system
     *
     * @param Charcoal_Layout $layout    layout name or key which is specified by the template system
     */
    public function setLayout( $layout )
    {
        Charcoal_ParamTrait::validateIsA( 1, 'Charcoal_Layout', $layout );

        $this->layout = $layout;
    }

    /**
     * get layout values
     *
     * @return array        array value which should be assigned to the template system
     */
    public function getValues()
    {
        return $this->values;
    }

    /**
     * set layout values
     *
     * @param Charcoal_HashMap|array $values        array value which should be assigned to the template system
     */
    public function setValues( $values )
    {
        Charcoal_ParamTrait::validateHashMap( 1, $values );

        $this->values = um( $values );
    }

    /**
     * get render target
     *
     * @return Charcoal_IRenderTarget        render target object
     */
    public function getRenderTarget()
    {
        return $this->render_target;
    }

    /**
     * set render target
     *
     * @param Charcoal_IRenderTarget        array value which should be assigned to the template system
     */
    public function setRenderTarget( $render_target )
    {
        Charcoal_ParamTrait::validateIsA( 1, 'Charcoal_IRenderTarget', $render_target );

        $this->render_target = $render_target;
    }
}

