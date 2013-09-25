
# ====================================================
# 	file
# ====================================================

PROC_PATH="@:objects:cache_drivers:file"

ACTIONS="get_empty_data,get_integer_data,get_string_data,get_array_data,get_boolean_data,get_float_data,get_object_data"
php shell.php -proc $PROC_PATH -actions $ACTIONS

# ====================================================
# 	memcached
# ====================================================

PROC_PATH="@:objects:cache_drivers:memcached"

ACTIONS="get_empty_data,get_integer_data,get_string_data,get_array_data,get_boolean_data,get_float_data,get_object_data"
php shell.php -proc $PROC_PATH -actions $ACTIONS

# ====================================================
# 	memcache
# ====================================================

PROC_PATH="@:objects:cache_drivers:memcache"

ACTIONS="get_empty_data,get_integer_data,get_string_data,get_array_data,get_boolean_data,get_float_data,get_object_data"
php shell.php -proc $PROC_PATH -actions $ACTIONS

