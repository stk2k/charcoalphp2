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
				// layout manager classes
				// service classes
				// events classes
				// component classes
				// domain object classes
				// domain model classes
				// DTO classes

				'BlogCategoryTableDTO'					=> 'class/dto',
				'BlogTableDTO'							=> 'class/dto',
				'CommentTableDTO'						=> 'class/dto',
				'PostTableDTO'							=> 'class/dto',
				'TestTableDTO'							=> 'class/dto',

				// table model classes

				'BlogCategoryTableModel'				=> 'class/table_model',
				'BlogTableModel'						=> 'class/table_model',
				'CommentTableModel'						=> 'class/table_model',
				'PostTableModel'						=> 'class/table_model',
				'TestTableModel'						=> 'class/table_model',

				// exception classes
				// exception handler classes
			);
	}
}
