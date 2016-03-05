<?php
class CommonClassLoader extends Charcoal_UserClassLoader implements Charcoal_IClassLoader
{
    /*
     * クラスとパスの対応を表す連想配列を取得
     */
    public function getClassPathAssoc()
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

                "BlogTableDTO"                => "class/dto",
                "BlogCategoryTableDTO"        => "class/dto",
                "PostTableDTO"                => "class/dto",
                "CommentTableDTO"            => "class/dto",

                // table model classes

                "BlogTableModel"            => "class/table_model",
                "BlogCategoryTableModel"    => "class/table_model",
                "PostTableModel"            => "class/table_model",
                "CommentTableModel"            => "class/table_model",

                // exception classes

                // exception handler classes

                // another classes
                "SimplePdo"                => "class/pdo",

            );
    }
}
