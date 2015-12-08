<?php
class MyClassLoader extends Charcoal_UserClassLoader implements Charcoal_IClassLoader
{
    /*
     * クラスとパスの対応を表す連想配列を取得
     */
    public function getClassPathAssoc()
    {
        return array(
                // constant classes
                // core classes
                // base classes
                // layout manager classes
                // service classes
                // events classes
                // component classes
                // domain object classes
                // domain model classes
                // DTO classes
                // table model classes
                // exception classes
                // exception handler classes
                'ShellCommandExceptionHandler' => 'class/exception_handler',
            );
    }
}
