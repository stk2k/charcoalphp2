<?php
class CommonClassLoader extends Charcoal_UserClassLoader implements Charcoal_IClassLoader
{
	/*
	 * クラスとパスの対応を表す連想配列を取得
	 */
	public function getClassPathAssoc( $class_name )
	{
		return array(
				// constant classes
				// base classes

				// layout manager classes
				// service classes
				// events classes

				// component classes
				// domain object classes
				// domain model classes
				// DTO classes

				"BlogTableDTO"				=> "classes/DTOs",
				"BlogCategoryTableDTO"		=> "classes/DTOs",
				"PostTableDTO"				=> "classes/DTOs",
				"CommentTableDTO"			=> "classes/DTOs",

				// table model classes

				"BlogTableModel"			=> "classes/table_models",
				"BlogCategoryTableModel"	=> "classes/table_models",
				"PostTableModel"			=> "classes/table_models",
				"CommentTableModel"			=> "classes/table_models",

				// exception classes

				// exception handler classes

			);
	}
}
