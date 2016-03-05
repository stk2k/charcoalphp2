<?php
/**
* base class for core hook
*
* PHP version 5
*
* @package    objects.core_hooks
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

abstract class Charcoal_AbstractCoreHook extends Charcoal_CharcoalComponent implements Charcoal_ICoreHook
{
    /**
     * Convert corehook stage constant value into display string
     *
     * @param int $stage       integer constant value defined in Charcoal_EnumCoreHookStage
     *
     * @return string          display name for corehook stage
     */
    public function getCoreHookStageName( $stage )
    {
        $klass = new ReflectionClass( 'Charcoal_EnumCoreHookStage' );
        $consts = $klass->getConstants();
        $consts = array_flip( $consts );
        return isset($consts[$stage]) ? $consts[$stage] : '';
/*
        $defs = array(
            Charcoal_EnumCoreHookStage::START_OF_BOOTSTRAP => 'START_OF_BOOTSTRAP',
            Charcoal_EnumCoreHookStage::BEFORE_INIT_FRAMEWORK => 'BEFORE_INIT_FRAMEWORK',
            Charcoal_EnumCoreHookStage::AFTER_INIT_FRAMEWORK => 'AFTER_INIT_FRAMEWORK',
            Charcoal_EnumCoreHookStage::BEFORE_REG_CLASS_LOADERS => 'BEFORE_REG_CLASS_LOADERS',
            Charcoal_EnumCoreHookStage::CREATE_FRAMEWORK_CLASS_LOADER => 'CREATE_FRAMEWORK_CLASS_LOADER',
            Charcoal_EnumCoreHookStage::REG_FRAMEWORK_CLASS_LOADER => 'REG_FRAMEWORK_CLASS_LOADER',
            Charcoal_EnumCoreHookStage::CREATE_CLASS_LOADER => 'CREATE_CLASS_LOADER',
            Charcoal_EnumCoreHookStage::REG_CLASS_LOADER => 'REG_CLASS_LOADER',
            Charcoal_EnumCoreHookStage::AFTER_REG_CLASS_LOADERS => 'AFTER_REG_CLASS_LOADERS',
            Charcoal_EnumCoreHookStage::BEFORE_REG_EXTLIB_DIR => 'BEFORE_REG_EXTLIB_DIR',
            Charcoal_EnumCoreHookStage::ADD_EXTLIB_DIR => 'ADD_EXTLIB_DIR',
            Charcoal_EnumCoreHookStage::AFTER_REG_EXTLIB_DIR => 'AFTER_REG_EXTLIB_DIR',
            Charcoal_EnumCoreHookStage::BEFORE_SET_SESSION_HANDLER => 'BEFORE_SET_SESSION_HANDLER',
            Charcoal_EnumCoreHookStage::AFTER_SET_SESSION_HANDLER => 'AFTER_SET_SESSION_HANDLER',
            Charcoal_EnumCoreHookStage::BEFORE_START_SESSION => 'BEFORE_START_SESSION',
            Charcoal_EnumCoreHookStage::AFTER_START_SESSION => 'AFTER_START_SESSION',
            Charcoal_EnumCoreHookStage::BEFORE_ROUTING_RULE => 'BEFORE_ROUTING_RULE',
            Charcoal_EnumCoreHookStage::AFTER_ROUTING_RULE => 'AFTER_ROUTING_RULE',
            Charcoal_EnumCoreHookStage::BEFORE_ROUTER => 'BEFORE_ROUTER',
            Charcoal_EnumCoreHookStage::AFTER_ROUTER => 'AFTER_ROUTER',
            Charcoal_EnumCoreHookStage::BEFORE_CREATE_PROCEDURE => 'BEFORE_CREATE_PROCEDURE',
            Charcoal_EnumCoreHookStage::AFTER_CREATE_PROCEDURE => 'AFTER_CREATE_PROCEDURE',
            Charcoal_EnumCoreHookStage::BEFORE_PROCEDURE_FORWARD => 'BEFORE_PROCEDURE_FORWARD',
            Charcoal_EnumCoreHookStage::PRE_PROCEDURE_FORWARD => 'PRE_PROCEDURE_FORWARD',
            Charcoal_EnumCoreHookStage::POST_PROCEDURE_FORWARD => 'POST_PROCEDURE_FORWARD',
            Charcoal_EnumCoreHookStage::AFTER_PROCEDURE_FORWARD => 'AFTER_PROCEDURE_FORWARD',
            Charcoal_EnumCoreHookStage::END_OF_BOOTSTRAP => 'END_OF_BOOTSTRAP',
            Charcoal_EnumCoreHookStage::PRE_EXECUTE_PROCEDURE => 'PRE_EXECUTE_PROCEDURE',
            Charcoal_EnumCoreHookStage::POST_EXECUTE_PROCEDURE => 'POST_EXECUTE_PROCEDURE',
            Charcoal_EnumCoreHookStage::AFTER_EXECUTE_PROCEDURES => 'AFTER_EXECUTE_PROCEDURES',
            Charcoal_EnumCoreHookStage::START_OF_SHUTDOWN => 'START_OF_SHUTDOWN',
            Charcoal_EnumCoreHookStage::BEFORE_SAVE_SESSION => '',
            Charcoal_EnumCoreHookStage::AFTER_SAVE_SESSION => '',
            Charcoal_EnumCoreHookStage::BEFORE_DESTROY_CONTAINER => '',
            Charcoal_EnumCoreHookStage::AFTER_DESTROY_CONTAINER => '',
            Charcoal_EnumCoreHookStage::END_OF_SHUTDOWN => '',
        }
*/
    }
}

