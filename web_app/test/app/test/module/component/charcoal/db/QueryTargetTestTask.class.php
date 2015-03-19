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

class QueryTargetTestTask extends Charcoal_TestTask
{
	private $default_ds;
	private $another_ds;

	/**
	 * check if action will be processed
	 */
	public function isValidAction( $action )
	{
		switch( $action ){
		case "qt_table_alias":
		case "qt_inner_join":
		case "qt_inner_join_alias":
		case "qt_left_join":
		case "qt_left_join_alias":
		case "qt_right_join":
		case "qt_right_join_alias":
		case "qt_complex_join":
		case "qt_complex_join_alias":
			return TRUE;
		}
		return FALSE;
	}

	/**
	 * セットアップ
	 */
	public function setUp( $action, $context )
	{

	}

	/**
	 * クリーンアップ
	 */
	public function cleanUp( $action, $context )
	{
	}

	/**
	 * テスト
	 */
	public function test( $action, $context )
	{
		$action = us($action);

		switch( $action ){
		case "qt_table_alias":
			$target = new Charcoal_QueryTarget( s("model_a as a") );

//echo "qtlist:" . print_r($qtlist,true) . eol();

			$this->assertEquals( 'model_a', $target->getModelName() );
			$this->assertEquals( 'a', $target->getAlias() );

			return TRUE;

		case "qt_inner_join":
			$target = new Charcoal_QueryTarget( s("model_a + model_b") );

//echo "qtlist:" . print_r($qtlist,true) . eol();

			$this->assertEquals( 'model_a', $target->getModelName() );
			$this->assertEquals( '', $target->getAlias() );

			$joins = $target->getJoins();
			$join_head = array_shift($joins);

			$this->assertEquals( Charcoal_EnumSQLJoinType::INNER_JOIN, $join_head->getJoinType() );
			$this->assertEquals( 'model_b', $join_head->getModelName() );
			$this->assertEquals( '', $join_head->getAlias() );

			return TRUE;

		case "qt_inner_join_alias":
			$target = new Charcoal_QueryTarget( s("model_a as a + model_b as b") );

//echo "qtlist:" . print_r($qtlist,true) . eol();

			$this->assertEquals( 'model_a', $target->getModelName() );
			$this->assertEquals( 'a', $target->getAlias() );

			$joins = $target->getJoins();
			$join_head = array_shift($joins);

			$this->assertEquals( Charcoal_EnumSQLJoinType::INNER_JOIN, $join_head->getJoinType() );
			$this->assertEquals( 'model_b', $join_head->getModelName() );
			$this->assertEquals( 'b', $join_head->getAlias() );

			return TRUE;

		case "qt_left_join":
			$target = new Charcoal_QueryTarget( s("model_a (+ model_b") );

//echo "qtlist:" . print_r($qtlist,true) . eol();

			$this->assertEquals( 'model_a', $target->getModelName() );
			$this->assertEquals( '', $target->getAlias() );

			$joins = $target->getJoins();
			$join_head = array_shift($joins);

			$this->assertEquals( Charcoal_EnumSQLJoinType::LEFT_JOIN, $join_head->getJoinType() );
			$this->assertEquals( 'model_b', $join_head->getModelName() );
			$this->assertEquals( '', $join_head->getAlias() );

			return TRUE;

		case "qt_left_join_alias":
			$target = new Charcoal_QueryTarget( s("model_a as a (+ model_b as b") );

//echo "qtlist:" . print_r($qtlist,true) . eol();

			$this->assertEquals( 'model_a', $target->getModelName() );
			$this->assertEquals( 'a', $target->getAlias() );

			$joins = $target->getJoins();
			$join_head = array_shift($joins);

			$this->assertEquals( Charcoal_EnumSQLJoinType::LEFT_JOIN, $join_head->getJoinType() );
			$this->assertEquals( 'model_b', $join_head->getModelName() );
			$this->assertEquals( 'b', $join_head->getAlias() );

			return TRUE;

		case "qt_right_join":
			$target = new Charcoal_QueryTarget( s("model_a +) model_b") );

//echo "qtlist:" . print_r($qtlist,true) . eol();

			$this->assertEquals( 'model_a', $target->getModelName() );
			$this->assertEquals( '', $target->getAlias() );

			$joins = $target->getJoins();
			$join_head = array_shift($joins);

			$this->assertEquals( Charcoal_EnumSQLJoinType::RIGHT_JOIN, $join_head->getJoinType() );
			$this->assertEquals( 'model_b', $join_head->getModelName() );
			$this->assertEquals( '', $join_head->getAlias() );

			return TRUE;

		case "qt_right_join_alias":
			$target = new Charcoal_QueryTarget( s("model_a as a +) model_b as b") );

//echo "qtlist:" . print_r($qtlist,true) . eol();

			$this->assertEquals( 'model_a', $target->getModelName() );
			$this->assertEquals( 'a', $target->getAlias() );

			$joins = $target->getJoins();
			$join_head = array_shift($joins);

			$this->assertEquals( Charcoal_EnumSQLJoinType::RIGHT_JOIN, $join_head->getJoinType() );
			$this->assertEquals( 'model_b', $join_head->getModelName() );
			$this->assertEquals( 'b', $join_head->getAlias() );

			return TRUE;

		case "qt_complex_join":
			$target = new Charcoal_QueryTarget( s("model_a + model_b + model_c") );

//echo "qtlist:" . print_r($qtlist,true) . eol();

			$this->assertEquals( 'model_a', $target->getModelName() );
			$this->assertEquals( '', $target->getAlias() );

			$joins = $target->getJoins();

			$this->assertEquals( Charcoal_EnumSQLJoinType::INNER_JOIN, $joins[0]->getJoinType() );
			$this->assertEquals( 'model_b', $joins[0]->getModelName() );
			$this->assertEquals( '', $joins[0]->getAlias() );

			$this->assertEquals( Charcoal_EnumSQLJoinType::INNER_JOIN, $joins[1]->getJoinType() );
			$this->assertEquals( 'model_c', $joins[1]->getModelName() );
			$this->assertEquals( '', $joins[1]->getAlias() );

			return TRUE;

		case "qt_complex_join_alias":
			$target = new Charcoal_QueryTarget( s("model_a as a (+ model_b as b +) model_c as c") );

//echo "qtlist:" . print_r($qtlist,true) . eol();

			$this->assertEquals( 'model_a', $target->getModelName() );
			$this->assertEquals( 'a', $target->getAlias() );

			$joins = $target->getJoins();

			$this->assertEquals( Charcoal_EnumSQLJoinType::LEFT_JOIN, $joins[0]->getJoinType() );
			$this->assertEquals( 'model_b', $joins[0]->getModelName() );
			$this->assertEquals( 'b', $joins[0]->getAlias() );

			$this->assertEquals( Charcoal_EnumSQLJoinType::RIGHT_JOIN, $joins[1]->getJoinType() );
			$this->assertEquals( 'model_c', $joins[1]->getModelName() );
			$this->assertEquals( 'c', $joins[1]->getAlias() );

			return TRUE;
		}

		return FALSE;
	}

}

return __FILE__;