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
    private $blog_name_expected;
    private $category_name_expected;

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
		case "find_by_id":
		case "save":
		case "fluent_api":
		case "recordset_query":
		case "recordset_find":
		case "nested_recordset_query":
		case "nested_recordset_find":
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
		$this->gw = $context->getComponent( 'smart_gateway@:charcoal:db' );

		$this->gw->autoCommit( b(TRUE) );

		switch( $action ){

		case "commit":
		case "query":
		case "select":
		case "select_alias":
		case "select_alias_forupdate":
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
		case "find_by_id":
		case "save":
		case "fluent_api":
		case "recordset_query":
		case "recordset_find":
		case "nested_recordset_query":
		case "nested_recordset_find":

			// truncate all tables
			$this->gw->execute( "TRUNCATE blogs" );
			$this->gw->execute( "TRUNCATE blog_category" );
			$this->gw->execute( "TRUNCATE posts" );
			$this->gw->execute( "TRUNCATE comments" );

			// blogs entries
			$sql = <<< SQL
INSERT INTO `blogs` (`blog_id`, `blog_category_id`, `blog_name`, `post_total`) VALUES
(1, 1, 'my blog', 2),
(2, 2, 'another blog', 1);
SQL;
			$this->gw->execute( $sql );

			$this->blog_name_expected = array(
					1 => 'my blog',
					2 => 'another blog',
					);

			// blog_category entries
			$sql = <<< SQL
INSERT INTO `blog_category` (`blog_category_id`, `blog_category_name`) VALUES
(1, 'Books'),
(2, 'Hobby'),
(3, 'Job'),
(4, 'Diary');
SQL;
			$this->gw->execute( $sql );

			$this->category_name_expected = array(
				1 => 'Books',
				2 => 'Hobby',
				3 => 'Job',
				4 => 'Diary',
				);

			// posts entries
			$sql = <<< SQL
INSERT INTO `posts` (`post_id`, `blog_id`, `post_title`, `post_body`, `post_user`, `favorite`) VALUES
(1, 1, 'Heloo world', 'My first blog post!', 'stk2k', 8),
(2, 1, 'Hiyas', 'My second blog post!', 'stk2k', 11),
(3, 2, 'How does it work?', 'My third blog post!', 'stk2k', 5);
SQL;
			$this->gw->execute( $sql );

			// comments entries
			$sql = <<< SQL
INSERT INTO `comments` (`comment_id`, `post_id`, `comment_title`, `comment_body`, `comment_user`) VALUES
(1, 1, 'wolf''s comment', 'my name id wolf.', 'wolf'),
(2, 1, 'bear''s comment', 'Bear comes here', 'bear'),
(3, 2, 'fox''s comment', 'Fox will be back', 'fox');
SQL;
			$this->gw->execute( $sql );

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

		$this->gw = $context->getComponent( 'smart_gateway@:charcoal:db' );
		switch( $action ){

		case "commit":

			$default_ds = $this->gw->getDataSource();

			$another_ds = $context->createObject( 'PDO', 'data_source' );

			$data = array(
					array( 1, "This is test.", "My first blog post!", "stk2k" ),
				);

			$this->gw->autoCommit( b(TRUE) );
			$this->gw->execute( "TRUNCATE posts" );
			foreach( $data as $row ){
				$this->gw->execute( "INSERT INTO posts(blog_id,post_title,post_body,post_user) values(?,?,?,?)", $row );
			}

			$this->gw->autoCommit( b(FALSE) );
			$this->gw->beginTrans();

			$result = $this->gw->query( "SELECT * FROM posts WHERE post_id=1");
			$post_user = $result[0]['post_user'];

			// デフォルトコネクションで更新
			$this->gw->execute( "UPDATE posts set post_user = 'hoge' where post_id = 1" );

			$result2 = $this->gw->query( "SELECT * FROM posts WHERE post_id=1");
			$post_user2 = $result2[0]['post_user'];

			// commit前に違うコネクションで確認
			$this->gw->setDataSource( $another_ds );
			$result3 = $this->gw->query( "SELECT * FROM posts WHERE post_id=1");
			$post_user3 = $result3[0]['post_user'];

			$this->gw->setDataSource( $default_ds );
			$this->gw->commitTrans();

			// commit後に違うコネクションで確認
			$this->gw->setDataSource( $another_ds );
			$result4 = $this->gw->query( "SELECT * FROM posts WHERE post_id=1");
			$post_user4 = $result4[0]['post_user'];

			$this->assertEquals( $post_user, $post_user3 );

			return TRUE;

		case "query":
			$sql = "SELECT * FROM blogs WHERE blog_id = 1";
			$result = $this->gw->query( $sql );

			foreach( $result as $row ){
				$blog_name = $row['blog_name'];
				echo "blog_name:$blog_name" . eol();
			}

			$this->assertEquals( 1, count($result) );
			$this->assertEquals( "my blog", $blog_name );

			return TRUE;

		case "select":
			$where = "blog_id = ?";
			$criteria = new Charcoal_SQLCriteria( $where, array(1) );
			$result = $this->gw->findAll( 'blogs', $criteria );

			foreach( $result as $row ){
				$blog_name = $row->blog_name;
				echo "blog_name:$blog_name" . eol();
			}

			$this->assertEquals( 1, count($result) );
			$this->assertEquals( "my blog", $blog_name );

			return TRUE;

		case "select_alias":
			$where = "b.blog_id = ?";
			$criteria = new Charcoal_SQLCriteria( $where, array(1) );
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
			$criteria = new Charcoal_SQLCriteria( $where, array("My First Blog") );
			$result = $this->gw->findAllForUpdate( 'blogs as b', $criteria );

			foreach( $result as $row ){
				print print_r($row,true) . PHP_EOL;
			}

			return TRUE;

		case "inner_join":
			$where = "blogs.blog_id = ?";
			$criteria = new Charcoal_SQLCriteria( $where, array(1) );
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
			$criteria = new Charcoal_SQLCriteria( $where, array(1) );
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
			$criteria = new Charcoal_SQLCriteria( $where, array(1) );
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
			$criteria = new Charcoal_SQLCriteria( $where, array(1) );
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
			$criteria = new Charcoal_SQLCriteria( $where, array(1) );
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
			$criteria = new Charcoal_SQLCriteria( $where, array(1) );
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
			$result = $this->gw->count( 'posts', $criteria, '*' );

			echo "result:" . $result . eol();

			// 評価
			$this->assertEquals( 3, $result );

			return TRUE;

		case "max":

			$criteria = new Charcoal_SQLCriteria();
			$result = $this->gw->max( 'posts', $criteria, 'favorite' );

			echo "result:" . $result . eol();

			$this->assertEquals( 11, $result );

			return TRUE;

		case "min":

			$criteria = new Charcoal_SQLCriteria();
			$result = $this->gw->min( 'posts', $criteria, 'favorite' );

			echo "result:" . $result . eol();

			// 評価
			$this->assertEquals( 5, $result );

			return TRUE;

		case "avg":

			$criteria = new Charcoal_SQLCriteria();
			$result = $this->gw->avg( 'posts', $criteria, 'favorite' );

			echo "result:" . $result . eol();

			$this->assertEquals( 8, $result );

			return TRUE;

		case "count_alias":

			$criteria = new Charcoal_SQLCriteria();
			$result = $this->gw->count( 'posts as p', $criteria, '*' );

			echo "result:" . $result . eol();

			$this->assertEquals( 3, $result );

			return TRUE;

		case "max_alias":

			$criteria = new Charcoal_SQLCriteria();
			$result = $this->gw->max( 'posts as p + comments as c on "p.post_id = c.post_id"', $criteria, 'favorite' );

			echo "result:" . $result . eol();

			$this->assertEquals( 11, $result );

			return TRUE;

		case "find_first":

			$criteria = new Charcoal_SQLCriteria();
			$criteria->setOrderBy( 'favorite' );

			$result = $this->gw->findFirst( 'posts', $criteria );

			echo "result:" . $result->post_title . eol();

			$this->assertEquals( 'How does it work?', $result->post_title );

			return TRUE;

		case "find_by_id":

			$result = $this->gw->findAll( 'posts', new Charcoal_SQLCriteria() );

			foreach ($result as $row) {
				$blog_id = $row['blog_id'];
				$blog = $this->gw->findById( 'blogs', $blog_id  );
				$this->assertEquals( $this->blog_name_expected[$blog_id], $blog['blog_name'] );

				$blog_category_id = $blog['blog_category_id'];
				$category = $this->gw->findById( 'blog_category', $blog_category_id  );
				$this->assertEquals( $this->category_name_expected[$blog_category_id], $category['blog_category_name'] );
			}

			return TRUE;

		case "save":

			$dto = new PostTableDTO();
			$dto->post_title = 'New Post';
			$dto->post_body = 'New Post Body';
			$dto->user = 'Ichiro';

			$count = $this->gw->count( 'posts', new Charcoal_SQLCriteria() );
			echo "count(before save):" . $count . eol();
			$this->assertEquals( 3, $count );

			$new_id = $this->gw->save( "posts", $dto );

			$criteria = new Charcoal_SQLCriteria();
			$criteria->setWhere( "post_id = ?" );
			$criteria->setParams( array($new_id) );

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
						->select( "b.blog_name, b.post_total, p.post_user, p.post_title" )
						->from(s("blogs"),s("b"))
						->leftJoin(s("posts"),s("p"))->on( "b.blog_id = p.blog_id" )
						->where()
						->gt(s("b.post_total"), i(1))
						->orderBy(s("b.post_total DESC"))
						->limit(i(5))
						->offset(i(0))
						->prepareExecute()
						->findFirst()->result();

//			echo print_r($rs,true) . eol();

//			echo "last SQL:" . $this->gw->getLastSQL() . eol();
				
//			echo "last params:" . $this->gw->getLastParams() . eol();
				
			return TRUE;

		case "recordset_query":

			$rsfactory1 = $this->gw->createRecordsetFactory();		// fetch mode: FETCHMODE_BOTH
			
			$sql = "SELECT * FROM blogs WHERE blog_id = 1";
			$result = $this->gw->query( $sql, NULL, $rsfactory1 );

			foreach( $result as $row ){
				$this->assertEquals( $this->blog_name_expected[$row['blog_id']], $row['blog_name'] );
			}

			$rsfactory2 = $this->gw->createRecordsetFactory( Charcoal_IRecordset::FETCHMODE_ASSOC );
			
			$sql = "SELECT * FROM blogs WHERE blog_id = 1";
			$result = $this->gw->query( $sql, NULL, $rsfactory2 );

			foreach( $result as $row ){
				$this->assertEquals( $this->blog_name_expected[$row['blog_id']], $row['blog_name'] );
			}

			$rsfactory3 = $this->gw->createRecordsetFactory( Charcoal_IRecordset::FETCHMODE_NUM );
			
			$sql = "SELECT blog_id, blog_name, post_total FROM blogs WHERE blog_id = 1";
			$result = $this->gw->query( $sql, NULL, $rsfactory3 );

			foreach( $result as $row ){
				$this->assertEquals( $this->blog_name_expected[$row[0]], $row[1] );
			}

			return TRUE;


		case "recordset_find":

			$rsfactory1 = $this->gw->createRecordsetFactory();		// fetch mode: FETCHMODE_ASSOC
			
			$sql = "SELECT * FROM blogs WHERE blog_id = 1";
			$result = $this->gw->query( $sql, NULL, $rsfactory1 );

			foreach( $result as $row ){
				$this->assertEquals( $this->blog_name_expected[$row['blog_id']], $row['blog_name'] );
			}

			$rsfactory2 = $this->gw->createRecordsetFactory( Charcoal_IRecordset::FETCHMODE_ASSOC );
			
			$sql = "SELECT * FROM blogs WHERE blog_id = 1";
			$result = $this->gw->query( $sql, NULL, $rsfactory2 );

			foreach( $result as $row ){
				$this->assertEquals( $this->blog_name_expected[$row['blog_id']], $row['blog_name'] );
			}

			$rsfactory3 = $this->gw->createRecordsetFactory( Charcoal_IRecordset::FETCHMODE_NUM );
			
			$sql = "SELECT blog_id, blog_name, post_total FROM blogs WHERE blog_id = 1";
			$result = $this->gw->query( $sql, NULL, $rsfactory3 );

			foreach( $result as $row ){
				$this->assertEquals( $this->blog_name_expected[$row[0]], $row[1] );
			}

			return TRUE;

		case "nested_recordset_query":
/*
			$rsfactory1 = $this->gw->createRecordsetFactory();		// fetch mode: FETCHMODE_BOTH
			
			$sql = "SELECT * FROM blogs WHERE blog_id = 1";
			$result = $this->gw->query( $sql, NULL, $rsfactory1 );

			foreach( $result as $row ){
				$this->assertEquals( 1, $row['blog_id'] );
				$this->assertEquals( $this->blog_name_expected[1], $row['blog_name'] );

				$blog_category_id = $row['blog_category_id'];

				$sql = "SELECT * FROM blog_category WHERE blog_category_id = ?";
				$result2 = $this->gw->query( $sql, array($blog_category_id), $rsfactory1 );

				foreach( $result2 as $row ){
					$this->assertEquals( $blog_category_id, $row['blog_category_id'] );
					$this->assertEquals( $this->category_name_expected[$blog_category_id], $row['blog_category_name'] );
				}
			}

			$rsfactory2 = $this->gw->createRecordsetFactory( Charcoal_IRecordset::FETCHMODE_ASSOC );
			
			$sql = "SELECT * FROM blogs WHERE blog_id = 1";
			$result = $this->gw->query( $sql, NULL, $rsfactory2 );

			foreach( $result as $row ){
				$this->assertEquals( 1, $row['blog_id'] );
				$this->assertEquals( $this->blog_name_expected[1], $row['blog_name'] );

				$blog_category_id = $row['blog_category_id'];

				$sql = "SELECT * FROM blog_category WHERE blog_category_id = ?";
				$result2 = $this->gw->query( $sql, array($blog_category_id), $rsfactory2 );

				foreach( $result2 as $row ){
					$this->assertEquals( $blog_category_id, $row['blog_category_id'] );
					$this->assertEquals( $this->category_name_expected[$blog_category_id], $row['blog_category_name'] );
				}
			}

			$rsfactory3 = $this->gw->createRecordsetFactory( Charcoal_IRecordset::FETCHMODE_NUM );
			
			$sql = "SELECT blog_id, blog_name, post_total, blog_category_id FROM blogs WHERE blog_id = 1";
			$result = $this->gw->query( $sql, NULL, $rsfactory3 );

			foreach( $result as $row ){
				$this->assertEquals( 1, $row[0] );
				$this->assertEquals( $this->blog_name_expected[1], $row[1] );

				$blog_category_id = $row[3];

				$sql = "SELECT blog_category_id, blog_category_name FROM blog_category WHERE blog_category_id = ?";
				$result2 = $this->gw->query( $sql, array($blog_category_id), $rsfactory3 );

				foreach( $result2 as $row ){
					$this->assertEquals( $blog_category_id, $row[0] );
					$this->assertEquals( $this->category_name_expected[$blog_category_id], $row[1] );
				}
			}
*/
			$another_ds = $context->createObject( 'PDO', 'data_source' );

			$default_ds_config = $this->gw->getDataSource()->getConfig();

			//ad($default_ds_config);

			$config = $context->createConfig( $default_ds_config );

			$GLOBALS['hoge'] = true;

			ad($config);

			$config->set( 'token_key', 'foo' );

			$another_ds->configure( $config );



			return TRUE;


		case "nested_recordset_find":
			return TRUE;


		}

		return FALSE;
	}

}

return __FILE__;