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
     *
     * @param string $action
     *
     * @return boolean
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
        case "delete_by_id":
        case "delete_by_ids":
            return TRUE;

        /* ------------------------------
            increment/decrement test
        */
        case "increment_field":
        case "increment_field_by":
        case "decrement_field":
        case "decrement_field_by":
            return TRUE;

        /* ------------------------------
            update test
        */
        case "update_field":
        case "update_fields":
        case "update_field_by":
        case "update_field_now":
        case "update_field_null":

        /* ------------------------------
            select db test
        */
        case "select_db":
            return TRUE;
        }
        return FALSE;
    }

    /**
     * セットアップ
     *
     * @param string $action
     * @param Charcoal_IEventContext $context
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
        case "delete_by_id":
        case "delete_by_ids":
        case "increment_field":
        case "increment_field_by":
        case "decrement_field":
        case "decrement_field_by":
        case "update_field":
        case "update_fields":
        case "update_field_by":
        case "update_field_now":
        case "update_field_null":
        case "select_db":

            // truncate all tables
            $this->gw->execute( "", "TRUNCATE blogs" );
            $this->gw->execute( "", "TRUNCATE blog_category" );
            $this->gw->execute( "", "TRUNCATE posts" );
            $this->gw->execute( "", "TRUNCATE comments" );

            // blogs entries
            $sql = <<< SQL
INSERT INTO `blogs` (`blog_id`, `blog_category_id`, `blog_name`, `post_total`, `created_date`, `modified_date`) VALUES
(1, 1, 'my blog', 2, '2010-01-02 12:56:12', '2010-01-02 12:56:12'),
(2, 2, 'another blog', 1, '2010-01-12 02:12:32', '2010-01-15 11:42:02');
SQL;
            $this->gw->execute( "", $sql );

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
            $this->gw->execute( "", $sql );

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
            $this->gw->execute( "", $sql );

            // comments entries
            $sql = <<< SQL
INSERT INTO `comments` (`comment_id`, `post_id`, `comment_title`, `comment_body`, `comment_user`) VALUES
(1, 1, 'wolf''s comment', 'my name id wolf.', 'wolf'),
(2, 1, 'bear''s comment', 'Bear comes here', 'bear'),
(3, 2, 'fox''s comment', 'Fox will be back', 'fox');
SQL;
            $this->gw->execute( "", $sql );

        }

        if ( $action == "select_db" ){
            $config = $this->gw->getDataSource()->getConfig()->getAll();
            $pdo = new SimplePdo($config['test2']);

            $pdo->query( "TRUNCATE `item`" );

            $sql = <<< SQL
INSERT INTO `item` (`item_name`, `price`, `stock`) VALUES
('apple', 100, 10),
('banana', 200, 5),
('melon', 90, 0);
SQL;
            $pdo->query( $sql );
        }
    }

    /**
     * クリーンアップ
     *
     * @param string $action
     * @param Charcoal_IEventContext $context
     */
    public function cleanUp( $action, $context )
    {
    }

    /**
     * テスト
     *
     * @param string $action
     * @param Charcoal_IEventContext $context
     *
     * @return boolean
     */
    public function test( $action, $context )
    {
        $action = us($action);

        $this->gw = $context->getComponent( 'smart_gateway@:charcoal:db' );
        switch( $action ){

        case "commit":

            $default_ds = $this->gw->getDataSource();

            /** @var Charcoal_IDataSource $another_ds */
            $another_ds = $context->createObject( 'PDO', 'data_source' );

            $data = array(
                    array( 1, "This is test.", "My first blog post!", "stk2k" ),
                );

            $this->gw->autoCommit( b(TRUE) );
            $this->gw->execute( "commit #1", "TRUNCATE posts" );
            foreach( $data as $row ){
                $this->gw->execute( "commit #2", "INSERT INTO posts(blog_id,post_title,post_body,post_user) values(?,?,?,?)", $row );
            }

            $this->gw->autoCommit( b(FALSE) );
            $this->gw->beginTrans();

            $result = $this->gw->query( "commit #3", "SELECT * FROM posts WHERE post_id=1");
            $post_user = $result[0]['post_user'];

            // デフォルトコネクションで更新
            $this->gw->execute( "commit #4", "UPDATE posts set post_user = 'hoge' where post_id = 1" );

            $this->gw->query( "commit #5", "SELECT * FROM posts WHERE post_id=1");
            //$post_user2 = $result2[0]['post_user'];

            // commit前に違うコネクションで確認
            $this->gw->setDataSource( $another_ds );
            $result3 = $this->gw->query( "commit #6", "SELECT * FROM posts WHERE post_id=1");
            $post_user3 = $result3[0]['post_user'];

            $this->gw->setDataSource( $default_ds );
            $this->gw->commitTrans();

            // commit後に違うコネクションで確認
            $this->gw->setDataSource( $another_ds );
            $this->gw->query( "commit #7", "SELECT * FROM posts WHERE post_id=1");
            //$post_user4 = $result4[0]['post_user'];

            $this->assertEquals( $post_user, $post_user3 );

            return TRUE;

        case "query":
            $sql = "SELECT * FROM blogs WHERE blog_id = 1";
            $result = $this->gw->query( "query #1", $sql );

            $blog_name = '';
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
            $result = $this->gw->findAll( "select #1", 'blogs', $criteria );

            $blog_name = '';
            foreach( $result as $row ){
                $blog_name = $row['blog_name'];
                echo "blog_name:$blog_name" . eol();
            }

            $this->assertEquals( 1, count($result) );
            $this->assertEquals( "my blog", $blog_name );

            return TRUE;

        case "select_alias":
            $where = "b.blog_id = ?";
            $criteria = new Charcoal_SQLCriteria( $where, array(1) );
            $result = $this->gw->findAll( "select_alias #1", 'blogs as b', $criteria );

            $blog_name = '';
            foreach( $result as $row ){
                $blog_name = $row['blog_name'];
                echo "blog_name:$blog_name" . eol();
            }

            $this->assertEquals( 1, count($result) );
            $this->assertEquals( "my blog", $blog_name );

            return TRUE;

        case "select_alias_forupdate":
            $where = "blog_name like ?";
            $criteria = new Charcoal_SQLCriteria( $where, array("My First Blog") );
            $result = $this->gw->findAllForUpdate( "select_alias_forupdate #1", 'blogs as b', $criteria );

            foreach( $result as $row ){
                print print_r($row,true) . PHP_EOL;
            }

            return TRUE;

        case "inner_join":
            $where = "blogs.blog_id = ?";
            $criteria = new Charcoal_SQLCriteria( $where, array(1) );
            $result = $this->gw->findAll( "inner_join #1", 'blogs + posts on "blogs.blog_id = posts.blog_id" + comments on "posts.post_id = comments.post_id"', $criteria );

            $blog_name = '';
            foreach( $result as $row ){
                $blog_name = $row['blog_name'];
                echo "blog_name:$blog_name" . eol();
            }

            $this->assertEquals( 3, count($result) );
            $this->assertEquals( "my blog", $blog_name );

            return TRUE;


        case "left_join":
            $where = "blogs.blog_id = ?";
            $criteria = new Charcoal_SQLCriteria( $where, array(1) );
            $result = $this->gw->findAll( "left_join #1", 'blogs (+ posts on "blogs.blog_id = posts.blog_id"', $criteria );

            $blog_name = '';
            foreach( $result as $row ){
                $blog_name = $row['blog_name'];
                echo "blog_name:$blog_name" . eol();
            }

            $this->assertEquals( 2, count($result) );
            $this->assertEquals( "my blog", $blog_name );

            return TRUE;


        case "right_join":
            $where = "blogs.blog_id = ?";
            $criteria = new Charcoal_SQLCriteria( $where, array(1) );
            $result = $this->gw->findAll( "right_join #1", 'blogs +) posts on "blogs.blog_id = posts.blog_id"', $criteria );

            $blog_name = '';
            foreach( $result as $row ){
                $blog_name = $row['blog_name'];
                echo "blog_name:$blog_name" . eol();
            }

            // 評価
            $this->assertEquals( 2, count($result) );
            $this->assertEquals( "my blog", $blog_name );

            return TRUE;


        case "inner_join_alias":
            $where = "b.blog_id = ?";
            $criteria = new Charcoal_SQLCriteria( $where, array(1) );
            $result = $this->gw->findAll( "inner_join_alias #1", 'blogs as b + posts as p on "b.blog_id = p.blog_id"', $criteria );

            $blog_name = '';
            foreach( $result as $row ){
                $blog_name = $row['blog_name'];
                echo "blog_name:$blog_name" . eol();
            }

            $this->assertEquals( 2, count($result) );
            $this->assertEquals( "my blog", $blog_name );

            return TRUE;


        case "inner_join_multi":
            $where = "blogs.blog_id = ?";
            $criteria = new Charcoal_SQLCriteria( $where, array(1) );
            $result = $this->gw->findAll( "inner_join_multi #1", 'blogs  + posts on "blogs.blog_id = posts.blog_id" + comments on "posts.post_id = comments.post_id"', $criteria );

            $blog_name = '';
            foreach( $result as $row ){
                $blog_name = $row['blog_name'];
                echo "blog_name:$blog_name" . eol();
            }

            $this->assertEquals( 3, count($result) );
            $this->assertEquals( "my blog", $blog_name );

            return TRUE;


        case "inner_join_multi_alias":
            $where = "b.blog_id = ?";
            $criteria = new Charcoal_SQLCriteria( $where, array(1) );
            $result = $this->gw->findAll( "inner_join_multi_alias #1", 'blogs as b + posts as p on "b.blog_id = p.blog_id" + comments as c on "p.post_id = c.post_id"', $criteria );

            $blog_name = '';
            foreach( $result as $row ){
                $blog_name = $row['blog_name'];
                echo "blog_name:$blog_name" . eol();
            }

            $this->assertEquals( 3, count($result) );
            $this->assertEquals( "my blog", $blog_name );

            return TRUE;


        case "count":

            $criteria = new Charcoal_SQLCriteria();
            $result = $this->gw->count( "count #1", 'posts', $criteria, '*' );

            echo "result:" . $result . eol();

            // 評価
            $this->assertEquals( 3, $result );

            return TRUE;

        case "max":

            $criteria = new Charcoal_SQLCriteria();
            $result = $this->gw->max( "max #1", 'posts', $criteria, 'favorite' );

            echo "result:" . $result . eol();

            $this->assertEquals( 11, $result );

            return TRUE;

        case "min":

            $criteria = new Charcoal_SQLCriteria();
            $result = $this->gw->min( "min #1", 'posts', $criteria, 'favorite' );

            echo "result:" . $result . eol();

            // 評価
            $this->assertEquals( 5, $result );

            return TRUE;

        case "avg":

            $criteria = new Charcoal_SQLCriteria();
            $result = $this->gw->avg( "avg #1", 'posts', $criteria, 'favorite' );

            echo "result:" . $result . eol();

            $this->assertEquals( 8, $result );

            return TRUE;

        case "count_alias":

            $criteria = new Charcoal_SQLCriteria();
            $result = $this->gw->count( "count_alias #1",'posts as p', $criteria, '*' );

            echo "result:" . $result . eol();

            $this->assertEquals( 3, $result );

            return TRUE;

        case "max_alias":

            $criteria = new Charcoal_SQLCriteria();
            $result = $this->gw->max( "max_alias #1",'posts as p + comments as c on "p.post_id = c.post_id"', $criteria, 'favorite' );

            echo "result:" . $result . eol();

            $this->assertEquals( 11, $result );

            return TRUE;

        case "find_first":

            $criteria = new Charcoal_SQLCriteria();
            $criteria->setOrderBy( 'favorite' );

            $result = $this->gw->findFirst( "find_first #1",'posts', $criteria );

            echo "result:" . $result['post_title'] . eol();

            $this->assertEquals( 'How does it work?', $result['post_title'] );

            return TRUE;

        case "find_by_id":

            $result = $this->gw->findAll( "find_by_id #1",'posts', new Charcoal_SQLCriteria() );

            foreach ($result as $row) {
                $blog_id = $row['blog_id'];
                $blog = $this->gw->findById( "find_by_id #2",'blogs', $blog_id  );
                $this->assertEquals( $this->blog_name_expected[$blog_id], $blog['blog_name'] );

                $blog_category_id = $blog['blog_category_id'];
                $category = $this->gw->findById( "find_by_id #3",'blog_category', $blog_category_id  );
                $this->assertEquals( $this->category_name_expected[$blog_category_id], $category['blog_category_name'] );
            }

            return TRUE;

        case "save":

            $dto = new PostTableDTO();
            $dto->post_title = 'New Post';
            $dto->post_body = 'New Post Body';
            $dto->user = 'Ichiro';

            $count = $this->gw->count( "save #1",'posts', new Charcoal_SQLCriteria(), NULL );

            echo $this->gw->popSQLHistory() . PHP_EOL;

            echo "count(before save):" . $count . eol();
            $this->assertEquals( 3, $count );

            $new_id = $this->gw->save( "save #2","posts", $dto );

            echo $this->gw->popSQLHistory() . PHP_EOL;

            $criteria = new Charcoal_SQLCriteria();
            $criteria->setWhere( "post_id = ?" );
            $criteria->setParams( array($new_id) );

            $new_record = $this->gw->findFirst( "save #3",'posts', $criteria );

            echo $this->gw->popSQLHistory() . PHP_EOL;

            $count = $this->gw->count( "save #4",'posts', new Charcoal_SQLCriteria() );

            echo $this->gw->popSQLHistory() . PHP_EOL;

            echo "count(after save):" . $count . eol();
            $this->assertEquals( 4, $count );
            $this->assertEquals( 'New Post', $new_record['post_title'] );
            $this->assertEquals( 'New Post Body', $new_record['post_body'] );

            return TRUE;

        case "fluent_api":

            $this->gw
                        ->select( "b.blog_name, b.post_total, p.post_user, p.post_title" )
                        ->from(s("blogs"),s("b"))
                        ->leftJoin(s("posts"),s("p"))->on( "b.blog_id = p.blog_id" )
                        ->where()
                        ->gt(s("b.post_total"), i(1))
                        ->orderBy(s("b.post_total DESC"))
                        ->limit(i(5))
                        ->offset(i(0))
                        ->prepareExecute()
                        ->findFirst( "fluent_api #1" )->result();

            echo $this->gw->popSQLHistory() . PHP_EOL;

//            echo print_r($rs,true) . eol();

//            echo "last SQL:" . $this->gw->getLastSQL() . eol();

//            echo "last params:" . $this->gw->getLastParams() . eol();

            return TRUE;

        case "recordset_query":

            $rsfactory1 = $this->gw->createRecordsetFactory();        // fetch mode: FETCHMODE_BOTH

            $sql = "SELECT * FROM blogs WHERE blog_id = 1";
            $result = $this->gw->query( "recordset_query #1", $sql, NULL, $rsfactory1, NULL );

            echo $this->gw->popSQLHistory() . PHP_EOL;

            foreach( $result as $row ){
                $this->assertEquals( $this->blog_name_expected[$row['blog_id']], $row['blog_name'] );
            }

            $rsfactory2 = $this->gw->createRecordsetFactory( Charcoal_IRecordset::FETCHMODE_ASSOC );

            $sql = "SELECT * FROM blogs WHERE blog_id = 1";
            $result = $this->gw->query( "recordset_query #2", $sql, NULL, $rsfactory2, NULL );

            echo $this->gw->popSQLHistory() . PHP_EOL;

            foreach( $result as $row ){
                $this->assertEquals( $this->blog_name_expected[$row['blog_id']], $row['blog_name'] );
            }

            $rsfactory3 = $this->gw->createRecordsetFactory( Charcoal_IRecordset::FETCHMODE_NUM );

            $sql = "SELECT blog_id, blog_name, post_total FROM blogs WHERE blog_id = 1";
            $result = $this->gw->query( "recordset_query #3", $sql, NULL, $rsfactory3, NULL );

            echo $this->gw->popSQLHistory() . PHP_EOL;

            foreach( $result as $row ){
                $this->assertEquals( $this->blog_name_expected[$row[0]], $row[1] );
            }

            return TRUE;


        case "recordset_find":

            $rsfactory1 = $this->gw->createRecordsetFactory();        // fetch mode: FETCHMODE_ASSOC

            $sql = "SELECT * FROM blogs WHERE blog_id = 1";
            $result = $this->gw->query( "recordset_find #1", $sql, NULL, $rsfactory1, NULL );

            echo $this->gw->popSQLHistory() . PHP_EOL;

            foreach( $result as $row ){
                $this->assertEquals( $this->blog_name_expected[$row['blog_id']], $row['blog_name'] );
            }

            $rsfactory2 = $this->gw->createRecordsetFactory( Charcoal_IRecordset::FETCHMODE_ASSOC );

            $sql = "SELECT * FROM blogs WHERE blog_id = 1";
            $result = $this->gw->query( "recordset_find #2", $sql, NULL, $rsfactory2, NULL );

            echo $this->gw->popSQLHistory() . PHP_EOL;

            foreach( $result as $row ){
                $this->assertEquals( $this->blog_name_expected[$row['blog_id']], $row['blog_name'] );
            }

            $rsfactory3 = $this->gw->createRecordsetFactory( Charcoal_IRecordset::FETCHMODE_NUM );

            $sql = "SELECT blog_id, blog_name, post_total FROM blogs WHERE blog_id = 1";
            $result = $this->gw->query( "recordset_find #3", $sql, NULL, $rsfactory3, NULL );

            echo $this->gw->popSQLHistory() . PHP_EOL;

            foreach( $result as $row ){
                $this->assertEquals( $this->blog_name_expected[$row[0]], $row[1] );
            }

            return TRUE;

        case "nested_recordset_query":

            $rsfactory1 = $this->gw->createRecordsetFactory();        // fetch mode: FETCHMODE_BOTH

            $sql = "SELECT * FROM blogs WHERE blog_id = 1";
            $result = $this->gw->query( NULL, $sql, NULL, $rsfactory1 );

            foreach( $result as $row ){
                $this->assertEquals( 1, $row['blog_id'] );
                $this->assertEquals( $this->blog_name_expected[1], $row['blog_name'] );

                $blog_category_id = $row['blog_category_id'];

                $sql = "SELECT * FROM blog_category WHERE blog_category_id = ?";
                $result2 = $this->gw->query( NULL, $sql, array($blog_category_id), $rsfactory1 );

                foreach( $result2 as $row2 ){
                    $this->assertEquals( $blog_category_id, $row2['blog_category_id'] );
                    $this->assertEquals( $this->category_name_expected[$blog_category_id], $row2['blog_category_name'] );
                }
            }
            /*
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

            $config = $context->createConfig( $default_ds_config->getAll() );

            $GLOBALS['hoge'] = true;

            ad($config);

            $config->set( 'token_key', 'foo' );

            $another_ds->configure( $config );



            return TRUE;


        case "nested_recordset_find":
            return TRUE;

        case "delete_by_id":

            $this->gw->deleteById( "delete_by_id #1", 'blog_category', 1 );

            echo $this->gw->popSQLHistory() . PHP_EOL;

            $criteria = new Charcoal_SQLCriteria( '' );

            $cnt = $this->gw->count( "delete_by_id #2", 'blog_category', $criteria );
            echo 'cnt:' . $cnt . PHP_EOL;

            echo $this->gw->popSQLHistory() . PHP_EOL;

            return TRUE;

        case "delete_by_ids":

            $this->gw->deleteByIds( "delete_by_ids #1", 'blog_category', array(1,3) );

            echo $this->gw->popSQLHistory() . PHP_EOL;

            $criteria = new Charcoal_SQLCriteria( '' );

            $cnt = $this->gw->count( "delete_by_ids #2", 'blog_category', $criteria );
            echo 'cnt:' . $cnt . PHP_EOL;

            echo $this->gw->popSQLHistory() . PHP_EOL;

            return TRUE;

        /* ------------------------------
            increment/decrement test
        */
        case "increment_field":

            // increment post_toal by 1
            $this->gw->incrementField( "increment_field #1", 'blogs', 1, 'post_total' );
            echo $this->gw->popSQLHistory() . PHP_EOL;

            // post_total must be 2 => 3
            $pdo = new DuplicatedPdo($this->gw);
            $post_total = $pdo->queryValue("SELECT post_total FROM blogs WHERE blog_id = ?", [1]);
            var_dump($post_total);
            $this->assertEquals( 3, $post_total );

            // increment post_toal by 2
            $this->gw->incrementField( "increment_field #1", 'blogs', 1, 'post_total', 2 );
            echo $this->gw->popSQLHistory() . PHP_EOL;

            // post_total must be 3 => 5
            $pdo = new DuplicatedPdo($this->gw);
            $post_total = $pdo->queryValue("SELECT post_total FROM blogs WHERE blog_id = ?", [1]);
            var_dump($post_total);
            $this->assertEquals( 5, $post_total );

            // increment post_toal by -5
            $this->gw->incrementField( "increment_field #1", 'blogs', 1, 'post_total', -5 );
            echo $this->gw->popSQLHistory() . PHP_EOL;

            // post_total must be 5 => 0
            $pdo = new DuplicatedPdo($this->gw);
            $post_total = $pdo->queryValue("SELECT post_total FROM blogs WHERE blog_id = ?", [1]);
            var_dump($post_total);
            $this->assertEquals( 0, $post_total );

            return TRUE;

        case "increment_field_by":

            // increment post_toal by 1
            $this->gw->incrementFieldBy( "increment_field_by #1", 'blogs', 'post_total', 'blog_name', 'my blog' );
            echo $this->gw->popSQLHistory() . PHP_EOL;

            // post_total must be 2 => 3
            $pdo = new DuplicatedPdo($this->gw);
            $post_total = $pdo->queryValue("SELECT post_total FROM blogs WHERE blog_id = ?", [1]);
            var_dump($post_total);
            $this->assertEquals( 3, $post_total );

            // increment post_toal by 2
            $this->gw->incrementFieldBy( "increment_field_by #2", 'blogs', 'post_total', 'blog_name', 'my blog', 2 );
            echo $this->gw->popSQLHistory() . PHP_EOL;

            // post_total must be 3 => 5
            $pdo = new DuplicatedPdo($this->gw);
            $post_total = $pdo->queryValue("SELECT post_total FROM blogs WHERE blog_id = ?", [1]);
            var_dump($post_total);
            $this->assertEquals( 5, $post_total );

            // increment post_toal by -5
            $this->gw->incrementFieldBy( "increment_field_by #3", 'blogs', 'post_total', 'blog_name', 'my blog', -5 );
            echo $this->gw->popSQLHistory() . PHP_EOL;

            // post_total must be 5 => 0
            $pdo = new DuplicatedPdo($this->gw);
            $post_total = $pdo->queryValue("SELECT post_total FROM blogs WHERE blog_id = ?", [1]);
            var_dump($post_total);
            $this->assertEquals( 0, $post_total );

            return TRUE;

        case "decrement_field":

            // decrement post_toal by 1
            $this->gw->decrementField( "decrement_field #1", 'blogs', 1, 'post_total' );
            echo $this->gw->popSQLHistory() . PHP_EOL;

            // post_total must be 2 => 1
            $pdo = new DuplicatedPdo($this->gw);
            $post_total = $pdo->queryValue("SELECT post_total FROM blogs WHERE blog_id = ?", [1]);
            var_dump($post_total);
            $this->assertEquals( 1, $post_total );

            // decrement post_toal by 2
            $this->gw->decrementField( "decrement_field #2", 'blogs', 1, 'post_total', 2 );
            echo $this->gw->popSQLHistory() . PHP_EOL;

            // post_total must be 1 => -1
            $pdo = new DuplicatedPdo($this->gw);
            $post_total = $pdo->queryValue("SELECT post_total FROM blogs WHERE blog_id = ?", [1]);
            var_dump($post_total);
            $this->assertEquals( -1, $post_total );

            // decrement post_toal by -5
            $this->gw->decrementField( "decrement_field #3", 'blogs', 1, 'post_total', -5 );
            echo $this->gw->popSQLHistory() . PHP_EOL;

            // post_total must be -1 => 4
            $pdo = new DuplicatedPdo($this->gw);
            $post_total = $pdo->queryValue("SELECT post_total FROM blogs WHERE blog_id = ?", [1]);
            var_dump($post_total);
            $this->assertEquals( 4, $post_total );

            return TRUE;

        case "decrement_field_by":

            // decrement post_toal by 1
            $this->gw->decrementFieldBy( "decrement_field_by #1", 'blogs', 'post_total', 'blog_name', 'my blog' );
            echo $this->gw->popSQLHistory() . PHP_EOL;

            // post_total must be 2 => 1
            $pdo = new DuplicatedPdo($this->gw);
            $post_total = $pdo->queryValue("SELECT post_total FROM blogs WHERE blog_id = ?", [1]);
            var_dump($post_total);
            $this->assertEquals( 1, $post_total );

            // decrement post_toal by 2
            $this->gw->decrementFieldBy( "decrement_field_by #2", 'blogs', 'post_total', 'blog_name', 'my blog', 2 );
            echo $this->gw->popSQLHistory() . PHP_EOL;

            // post_total must be 1 => -1
            $pdo = new DuplicatedPdo($this->gw);
            $post_total = $pdo->queryValue("SELECT post_total FROM blogs WHERE blog_id = ?", [1]);
            var_dump($post_total);
            $this->assertEquals( -1, $post_total );

            // decrement post_toal by -5
            $this->gw->decrementFieldBy( "decrement_field_by #3", 'blogs', 'post_total', 'blog_name', 'my blog', -5 );
            echo $this->gw->popSQLHistory() . PHP_EOL;

            // post_total must be -1 => 4
            $pdo = new DuplicatedPdo($this->gw);
            $post_total = $pdo->queryValue("SELECT post_total FROM blogs WHERE blog_id = ?", [1]);
            var_dump($post_total);
            $this->assertEquals( 4, $post_total );

            return TRUE;

        /* ------------------------------
            update test
        */
        case "update_field":

            // update a field
            $this->gw->updateField( 'update_field #1', 'blogs', 1, 'post_total', 5 );
            echo $this->gw->popSQLHistory() . PHP_EOL;

            // confirm result
            $pdo = new DuplicatedPdo($this->gw);
            $rows = $pdo->queryRows("SELECT * FROM blogs");
            //var_dump($rows);
            foreach( $rows as $row ){
                switch($row['blog_id']){
                    case 1:
                        $this->assertEquals( 1, $row['blog_category_id'] );
                        $this->assertEquals( 'my blog', $row['blog_name'] );
                        $this->assertEquals( 5, $row['post_total'] );
                        break;
                    case 2:
                        $this->assertEquals( 2, $row['blog_category_id'] );
                        $this->assertEquals( 'another blog', $row['blog_name'] );
                        $this->assertEquals( 1, $row['post_total'] );
                        break;
                }
            }

            return TRUE;

        case "update_fields":

            // update fields
            $fields = array(
                'post_total' => 999,
                'blog_name' => 'super popular blog',
            );
            $this->gw->updateFields( 'update_fields #1', 'blogs', 1, $fields );
            echo $this->gw->popSQLHistory() . PHP_EOL;

            // confirm result
            $pdo = new DuplicatedPdo($this->gw);
            $rows = $pdo->queryRows("SELECT * FROM blogs");
            //var_dump($rows);
            foreach( $rows as $row ){
                switch($row['blog_id']){
                    case 1:
                        $this->assertEquals( 1, $row['blog_category_id'] );
                        $this->assertEquals( 'super popular blog', $row['blog_name'] );
                        $this->assertEquals( 999, $row['post_total'] );
                        break;
                    case 2:
                        $this->assertEquals( 2, $row['blog_category_id'] );
                        $this->assertEquals( 'another blog', $row['blog_name'] );
                        $this->assertEquals( 1, $row['post_total'] );
                        break;
                }
            }
            return TRUE;

        case "update_field_by":
            // update fields in a row
            $rows = $this->gw->updateFieldBy( 'update_field_by #1', 'blogs', 'blog_name', 'another blog', 'blog_id', 1 );
            echo $this->gw->popSQLHistory() . PHP_EOL;
            $this->assertEquals( 1, $rows );

            // confirm result
            $pdo = new DuplicatedPdo($this->gw);
            $rows = $pdo->queryRows("SELECT * FROM blogs");
            //var_dump($rows);
            foreach( $rows as $row ){
                switch($row['blog_id']){
                    case 1:
                        $this->assertEquals( 1, $row['blog_category_id'] );
                        $this->assertEquals( 'another blog', $row['blog_name'] );
                        $this->assertEquals( 2, $row['post_total'] );
                        break;
                    case 2:
                        $this->assertEquals( 2, $row['blog_category_id'] );
                        $this->assertEquals( 'another blog', $row['blog_name'] );
                        $this->assertEquals( 1, $row['post_total'] );
                        break;
                }
            }

            // update fields in multiple rows
            $rows = $this->gw->updateFieldBy( 'update_field_by #1', 'blogs', 'post_total', 3, 'blog_name', 'another blog' );
            echo $this->gw->popSQLHistory() . PHP_EOL;
            $this->assertEquals( 2, $rows );

            // confirm result
            $pdo = new DuplicatedPdo($this->gw);
            $rows = $pdo->queryRows("SELECT * FROM blogs");
            //var_dump($rows);
            foreach( $rows as $row ){
                switch($row['blog_id']){
                    case 1:
                        $this->assertEquals( 1, $row['blog_category_id'] );
                        $this->assertEquals( 'another blog', $row['blog_name'] );
                        $this->assertEquals( 3, $row['post_total'] );
                        break;
                    case 2:
                        $this->assertEquals( 2, $row['blog_category_id'] );
                        $this->assertEquals( 'another blog', $row['blog_name'] );
                        $this->assertEquals( 3, $row['post_total'] );
                        break;
                }
            }

            return TRUE;

        case "update_field_now":

            $start_time = time();

            // update field to current time
            $this->gw->updateFieldNow( 'update_field_now #1', 'blogs', 1, 'modified_date' );
            echo $this->gw->popSQLHistory() . PHP_EOL;

            // confirm result
            $pdo = new DuplicatedPdo($this->gw);
            $rows = $pdo->queryRows("SELECT * FROM blogs");
            //var_dump($rows);
            foreach( $rows as $row ){
                switch($row['blog_id']){
                    case 1:
                        $this->assertEquals( 1, $row['blog_category_id'] );
                        $this->assertEquals( 'my blog', $row['blog_name'] );
                        $this->assertEquals( 2, $row['post_total'] );
                        $this->assertEquals( '2010-01-02 12:56:12', $row['created_date'] );
                        $this->assertLessThanOrEqual( $start_time, strtotime($row['modified_date']) );
                        $this->assertGreaterThanOrEqual( time(), strtotime($row['modified_date']) );
                        break;
                    case 2:
                        $this->assertEquals( 2, $row['blog_category_id'] );
                        $this->assertEquals( 'another blog', $row['blog_name'] );
                        $this->assertEquals( 1, $row['post_total'] );
                        $this->assertEquals( '2010-01-12 02:12:32', $row['created_date'] );
                        $this->assertEquals( '2010-01-15 11:42:02', $row['modified_date'] );
                        break;
                }
            }
            return TRUE;

        case "update_field_null":

            // update fields in a row
            $this->gw->updateFieldNull( 'update_field_null #1', 'blogs', 1, 'blog_name' );
            echo $this->gw->popSQLHistory() . PHP_EOL;

            // confirm result
            $pdo = new DuplicatedPdo($this->gw);
            $rows = $pdo->queryRows("SELECT * FROM blogs");
            //var_dump($rows);
            foreach( $rows as $row ){
                switch($row['blog_id']){
                    case 1:
                        $this->assertEquals( 1, $row['blog_category_id'] );
                        $this->assertEquals( null, $row['blog_name'] );
                        $this->assertEquals( 2, $row['post_total'] );
                        break;
                    case 2:
                        $this->assertEquals( 2, $row['blog_category_id'] );
                        $this->assertEquals( 'another blog', $row['blog_name'] );
                        $this->assertEquals( 1, $row['post_total'] );
                        break;
                }
            }
            return TRUE;

        case "select_db":

            $exists = $this->gw->existsTable('item');

            $this->assertEquals( false, $exists );

            $this->gw->selectDatabase('test2');

            $exists = $this->gw->existsTable('item');

            $this->assertEquals( true, $exists );



            return TRUE;

        default:
            break;
        }

        return FALSE;
    }

}

return __FILE__;