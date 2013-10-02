<?php
/**
* タスク
*
* PHP version 5
*
* @package    renderers
* @author     stk2k <stk2k@sazysoft.com>
* @copyright  2008 stk2k, sazysoft
*
*
*/

/**
 * @property Charcoal_SmartGateway $gw
 *
 */
class SmartGatewayTestTask extends Charcoal_TestTask
{
    private $gw;

	/**
	 * check if action will be processed
	 */
	public function isValidAction( $action )
	{
		switch( $action ){
		case "commit":
		case "query":
		case "select":
		case "select_alias":
		case "select_alias_forupdate":
		case "select_cascade":
		case "select_as":
		case "select_as_forupdate":
		case "inner_join":
		case "left_join":
		case "right_join":
		case "inner_join_alias":
		case "inner_join_multi":
		case "inner_join_multi_alias":
		case "count":
		case "max":
		case "min":
		case "avg":
		case "count_alias":
		case "max_alias":
		case "find_first":
		case "save_insert":
		case "fluent_api":
			return TRUE;
		}
		return FALSE;
	}

	/**
	 * セットアップ
	 */
	public function setUp( $action, $context )
	{
		$action = us($action);

		// SmartGateway
		$this->gw = $context->getComponent( s('smart_gateway@:charcoal:db') );

		$this->gw->autoCommit( b(TRUE) );
		$this->gw->execute( s("TRUNCATE blogs") );
		$this->gw->execute( s("TRUNCATE posts") );
		$this->gw->execute( s("TRUNCATE comments") );

		switch( $action ){

		case "commit":
		case "query":
		case "select":
		case "select_alias":
		case "select_alias_forupdate":
		case "select_cascade":
		case "select_as":
		case "select_as_forupdate":
		case "inner_join":
		case "left_join":
		case "right_join":
		case "inner_join_alias":
		case "inner_join_multi":
		case "inner_join_multi_alias":
		case "count":
		case "max":
		case "min":
		case "avg":
		case "count_alias":
		case "max_alias":
		case "find_first":
		case "save_insert":
		case "fluent_api":

			// blogs entries
			$data = array(
					array( "my blog", 1, 2 ),
					array( "another blog", 2, 1 ),
				);

			foreach( $data as $row ){
				$this->gw->execute( s("INSERT INTO blogs(blog_name, blog_category_id, post_total) values(?,?,?)"), v($row) );
			}

			// posts entries
			$data = array(
					array( 1, "Heloo world", "My first blog post!", "stk2k", 8 ),
					array( 1, "Hiyas", "My second blog post!", "stk2k", 11 ),
					array( 2, "How does it work?", "My third blog post!", "stk2k", 5 ),
				);

			foreach( $data as $row ){
				$this->gw->execute( s("INSERT INTO posts(blog_id,post_title,post_body,post_user,favorite) values(?,?,?,?,?)"), v($row) );
			}

			// comments entries
			$data = array(
					array( 1, "wolf's comment", "my name id wolf.", "wolf" ),
					array( 1, "bear's comment", "Bear comes here", "bear" ),
					array( 2, "fox's comment", "Fox will be back", "fox" ),
				);

			foreach( $data as $row ){
				$this->gw->execute( s("INSERT INTO comments(post_id, comment_title, comment_body, comment_user) values(?,?,?,?)"), v($row) );
			}

		}

	}

	/**
	 * クリーンアップ
	 */
	public function cleanUp( $action, $context )
	{
	}

	/**
	 * テスト
		 * @var Charcoal_SmartGateway $this->gw
	 */
	public function test( $action, $context )
	{
		$action = us($action);

		$this->gw = $context->getComponent( s('smart_gateway@:charcoal:db') );
		switch( $action ){

		case "commit":

			$default_ds = $this->gw->getDataSource();

			$another_ds = $context->getObject( 'PDO', 'data_source' );

			$data = array(
					array( 1, "This is test.", "My first blog post!", "stk2k" ),
				);

			$this->gw->autoCommit( b(TRUE) );
			$this->gw->execute( s("TRUNCATE posts") );
			foreach( $data as $row ){
				$this->gw->execute( s("INSERT INTO posts(blog_id,post_title,post_body,post_user) values(?,?,?,?)"), v($row) );
			}

			$this->gw->autoCommit( b(FALSE) );
			$this->gw->beginTrans();

			$result = $this->gw->query( s("SELECT * FROM posts WHERE post_id=1") );
			$post_user = $result[0]['post_user'];

			// デフォルトコネクションで更新
			$this->gw->execute( s("UPDATE posts set post_user = 'hoge' where post_id = 1") );

			$result2 = $this->gw->query( s("SELECT * FROM posts WHERE post_id=1") );
			$post_user2 = $result2[0]['post_user'];

			// commit前に違うコネクションで確認
			$this->gw->setDataSource( $another_ds );
			$result3 = $this->gw->query( s("SELECT * FROM posts WHERE post_id=1") );
			$post_user3 = $result3[0]['post_user'];

			$this->gw->setDataSource( $default_ds );
			$this->gw->commitTrans();

			// commit後に違うコネクションで確認
			$this->gw->setDataSource( $another_ds );
			$result4 = $this->gw->query( s("SELECT * FROM posts WHERE post_id=1") );
			$post_user4 = $result4[0]['post_user'];

			$this->assertEquals( $post_user, $post_user3 );

			return TRUE;

		case "query":
			$sql = "SELECT * FROM blogs WHERE blog_id = 1";
			$result = $this->gw->query( s($sql) );

			foreach( $result as $row ){
				$blog_name = $row['blog_name'];
				echo "blog_name:$blog_name" . eol();
			}

			$this->assertEquals( 1, count($result) );
			$this->assertEquals( "my blog", $blog_name );

			return TRUE;

		case "select":
			$where = "blog_id = ?";
			$criteria = new Charcoal_SQLCriteria( s($where), v(array(1)) );
			$result = $this->gw->findAll( 'blogs', $criteria );

			foreach( $result as $row ){
				$blog_name = $row->blog_name;
				echo "blog_name:$blog_name" . eol();
			}

			$this->assertEquals( 1, count($result) );
			$this->assertEquals( "my blog", $blog_name );

			return TRUE;

		case "select_cascade":
			{
				$where = "blog_id = ?";
				$criteria = new Charcoal_SQLCriteria( $where, array(1) );
				$target = 'blogs as b + blog_category as c on "b.blog_category_id = c.blog_category_id"';
				$result = $this->gw->findAll( $target, $criteria );

//			echo "result:" . print_r($result,true) . eol();

				foreach( $result as $row ){
					$blog_name     = $row->blog_name;
					$category_name = $row->blog_category_name;
//				echo "blog_name:$blog_name" . eol();
//				echo "category_name:$category_name" . eol();
				}

				$this->assertEquals( 1, count($result) );
				$this->assertEquals( "my blog", $blog_name );
			}
			return TRUE;

		case "select_alias":
			$where = "b.blog_id = ?";
			$criteria = new Charcoal_SQLCriteria( s($where), v(array(1)) );
			$result = $this->gw->findAll( 'blogs as b', $criteria );

			foreach( $result as $row ){
				$blog_name = $row->blog_name;
				echo "blog_name:$blog_name" . eol();
			}

			$this->assertEquals( 1, count($result) );
			$this->assertEquals( "my blog", $blog_name );

			return TRUE;

		case "select_alias_forupdate":
			$where = "blog_name like ?";
			$criteria = new Charcoal_SQLCriteria( s($where), v(array("My First Blog")) );
			$result = $this->gw->findAllForUpdate( 'blogs as b', $criteria );

			foreach( $result as $row ){
				print print_r($row,true) . PHP_EOL;
			}

			return TRUE;

		case "inner_join":
			$where = "blogs.blog_id = ?";
			$criteria = new Charcoal_SQLCriteria( s($where), v(array(1)) );
			$result = $this->gw->findAll( 'blogs + posts on "blogs.blog_id = posts.blog_id" + comments on "posts.post_id = comments.post_id"', $criteria );

			foreach( $result as $row ){
				$blog_name = $row->blog_name;
				echo "blog_name:$blog_name" . eol();
			}

			$this->assertEquals( 3, count($result) );
			$this->assertEquals( "my blog", $blog_name );

			return TRUE;


		case "left_join":
			$where = "blogs.blog_id = ?";
			$criteria = new Charcoal_SQLCriteria( s($where), v(array(1)) );
			$result = $this->gw->findAll( 'blogs (+ posts on "blogs.blog_id = posts.blog_id"', $criteria );

			foreach( $result as $row ){
				$blog_name = $row->blog_name;
				echo "blog_name:$blog_name" . eol();
			}

			$this->assertEquals( 2, count($result) );
			$this->assertEquals( "my blog", $blog_name );

			return TRUE;


		case "right_join":
			$where = "blogs.blog_id = ?";
			$criteria = new Charcoal_SQLCriteria( s($where), v(array(1)) );
			$result = $this->gw->findAll( 'blogs +) posts on "blogs.blog_id = posts.blog_id"', $criteria );

			foreach( $result as $row ){
				$blog_name = $row->blog_name;
				echo "blog_name:$blog_name" . eol();
			}

			// 評価
			$this->assertEquals( 2, count($result) );
			$this->assertEquals( "my blog", $blog_name );

			return TRUE;


		case "inner_join_alias":
			$where = "b.blog_id = ?";
			$criteria = new Charcoal_SQLCriteria( s($where), v(array(1)) );
			$result = $this->gw->findAll( 'blogs as b + posts as p on "b.blog_id = p.blog_id"', $criteria );

			foreach( $result as $row ){
				$blog_name = $row->blog_name;
				echo "blog_name:$blog_name" . eol();
			}

			$this->assertEquals( 2, count($result) );
			$this->assertEquals( "my blog", $blog_name );

			return TRUE;


		case "inner_join_multi":
			$where = "blogs.blog_id = ?";
			$criteria = new Charcoal_SQLCriteria( s($where), v(array(1)) );
			$result = $this->gw->findAll( 'blogs  + posts on "blogs.blog_id = posts.blog_id" + comments on "posts.post_id = comments.post_id"', $criteria );

			foreach( $result as $row ){
				$blog_name = $row->blog_name;
				echo "blog_name:$blog_name" . eol();
			}

			$this->assertEquals( 3, count($result) );
			$this->assertEquals( "my blog", $blog_name );

			return TRUE;


		case "inner_join_multi_alias":
			$where = "b.blog_id = ?";
			$criteria = new Charcoal_SQLCriteria( s($where), v(array(1)) );
			$result = $this->gw->findAll( 'blogs as b + posts as p on "b.blog_id = p.blog_id" + comments as c on "p.post_id = c.post_id"', $criteria );

			foreach( $result as $row ){
				$blog_name = $row->blog_name;
				echo "blog_name:$blog_name" . eol();
			}

			$this->assertEquals( 3, count($result) );
			$this->assertEquals( "my blog", $blog_name );

			return TRUE;


		case "count":

			$criteria = new Charcoal_SQLCriteria();
			$result = $this->gw->count( s('posts'), $criteria, s('*') );

			echo "result:" . $result . eol();

			// 評価
			$this->assertEquals( 3, $result );

			return TRUE;

		case "max":

			$criteria = new Charcoal_SQLCriteria();
			$result = $this->gw->max( s('posts'), $criteria, s('favorite') );

			echo "result:" . $result . eol();

			$this->assertEquals( 11, $result );

			return TRUE;

		case "min":

			$criteria = new Charcoal_SQLCriteria();
			$result = $this->gw->min( s('posts'), $criteria, s('favorite') );

			echo "result:" . $result . eol();

			// 評価
			$this->assertEquals( 5, $result );

			return TRUE;

		case "avg":

			$criteria = new Charcoal_SQLCriteria();
			$result = $this->gw->avg( s('posts'), $criteria, s('favorite') );

			echo "result:" . $result . eol();

			$this->assertEquals( 8, $result );

			return TRUE;

		case "count_alias":

			$criteria = new Charcoal_SQLCriteria();
			$result = $this->gw->count( s('posts as p'), $criteria, s('*') );

			echo "result:" . $result . eol();

			$this->assertEquals( 3, $result );

			return TRUE;

		case "max_alias":

			$criteria = new Charcoal_SQLCriteria();
			$result = $this->gw->max( s('posts as p + comments as c on "p.post_id = c.post_id"'), $criteria, s('favorite') );

			echo "result:" . $result . eol();

			$this->assertEquals( 11, $result );

			return TRUE;

		case "find_first":

			$criteria = new Charcoal_SQLCriteria();
			$criteria->setOrderBy( s('favorite') );

			$result = $this->gw->findFirst( 'posts', $criteria );

			echo "result:" . $result->post_title . eol();

			$this->assertEquals( 'How does it work?', $result->post_title );

			return TRUE;

		case "save_insert":

			$dto = new PostTableDTO();
			$dto->post_title = 'New Post';
			$dto->post_body = 'New Post Body';
			$dto->user = 'Ichiro';

			$count = $this->gw->count( 'posts', new Charcoal_SQLCriteria() );
			echo "count(before save):" . $count . eol();
			$this->assertEquals( 3, $count );

			$new_id = $this->gw->save( s('posts'), $dto );

			$criteria = new Charcoal_SQLCriteria();
			$criteria->setWhere( s('post_id = ?') );
			$criteria->setParams( v(array($new_id)) );

			$new_record = $this->gw->findFirst( 'posts', $criteria );

			echo "new_record:" . print_r($new_record,true) . eol();

			$count = $this->gw->count( 'posts', new Charcoal_SQLCriteria() );
			echo "count(after save):" . $count . eol();
			$this->assertEquals( 4, $count );
			$this->assertEquals( 'New Post', $new_record->post_title );
			$this->assertEquals( 'New Post Body', $new_record->post_body );

			return TRUE;
		case "fluent_api":
			
			$rs = $this->gw
						->select( s("b.blog_name, b.post_total, p.post_user, p.post_title") )
						->from(s("blogs"),s("b"))
						->leftJoin(s("posts"),s("p"))->on( s('b.blog_id = p.blog_id') )
						->where()
						->gt(s("b.post_total"), i(1))
						->orderBy(s("b.post_total DESC"))
						->limit(i(5))
						->offset(i(0))
						->prepare()
						->findFirst()->result();

//			echo print_r($rs,true) . eol();

//			echo "last SQL:" . $this->gw->getLastSQL() . eol();
				
//			echo "last params:" . $this->gw->getLastParams() . eol();
				
			return TRUE;
		}

		return FALSE;
	}

}

return __FILE__;