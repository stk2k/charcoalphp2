<?php
class CommonClassLoader extends Charcoal_UserClassLoader implements Charcoal_IClassLoader
{
	/*
	 * クラスとパスの対応を表す連想配列を取得
	 */
	public function getClassPathAssoc( Charcoal_String $class_name )
	{
		return array(
				// constant classes
				// base classes

				'CommandTaskBase'			=> 'classes/base',

				// layout manager classes
				// service classes
				// events classes

				'ShellEvent'				=> 'classes/events',

				// component classes
				// domain object classes
				// domain model classes
				// DTO classes
				// table model classes
				// table model classes
				// exception classes

				// exception handler classes

			);
	}
}
