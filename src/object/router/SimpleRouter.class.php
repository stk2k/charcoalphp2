<?php
/**
* Simple Router
*
* PHP version 5
*
* @package    objects.routers
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/
class Charcoal_SimpleRouter extends Charcoal_AbstractRouter
{
    /**
     * Lookup routing rules
     *
     * @return array returns combined array, FALSE if any pattern is matched.
     */
    public function route( Charcoal_IRequest $request, Charcoal_IRoutingRule $rule )
    {
        // Get path info
        //$request_uri = $_SERVER["REQUEST_URI"];
        //$script_name = $_SERVER["SCRIPT_NAME"];
        //$dir_name    = dirname($script_name);

        //$pos = strpos( $request_uri, $dir_name );
        //$url = substr( $request_uri, $pos + strlen($dir_name) );

        $url = rtrim($_SERVER["REQUEST_URI"],'/');

        log_info( 'debug,router', "routing started. URL=[$url]" );

        $proc_key = $this->getSandbox()->getProfile()->getString( 'PROC_KEY', 'proc' );

        $rule_keys = $rule->getKeys();

        if ( $rule_keys && is_array($rule_keys) )
        {
            log_info( 'debug,router', "rule keys=[" . implode(",",$rule_keys) . "]" );

            foreach( $rule_keys as $pattern )
            {
                $proc = $rule->getProcPath( s($pattern) );
                log_info( 'debug,router', "pattern=[$pattern] proc=[$proc]" );

                if ( $proc )
                {
                    log_info( 'debug,router', "testing pattern=[$pattern] url=[$url]" );
                    $params = self::_match( $pattern, $url );
                    log_info( 'debug,router', "params:" . print_r($params,true) );

                    // match
                    if ( $params !== NULL ){
                        $request->setArray( $params );
                        $request->set( $proc_key, $proc );
                        log_info( 'debug,router', "routing rule matched! pattern=[$pattern] proc_path=[$proc]" );

                        $result = array(
                            'proc' => $proc,
                            'params' => $params,
                            'pattern' => $pattern,
                            );

                        return $result;
                    }
                }
            }

            log_warning( 'system,debug,router', "no routing rule is matched." );
        }
        else{
            log_warning( 'system,debug,router', "routing rule are not defined." );
        }

        return FALSE;
    }

    /**
     * URLがパターンに合致するか
     */
    private static function _match( $rule, $url )
    {
        log_info( 'debug,router', "matching test: rule=[$rule] url=[$url]" );

        $parsed_url = parse_url($url);

        $url = isset($parsed_url['path']) ? $parsed_url['path'] : '';
        $query = isset($parsed_url['query']) ? $parsed_url['query'] : '';

        $params = array();
        parse_str( $query, $params );

        $url = trim($url,'/');
        $rule = trim($rule,'/');

        $url_dir_array = explode( '/', $url );
        $rule_dir_array = explode( '/', $rule );

        $cnt_url = count($url_dir_array);
        $cnt_rule = count($rule_dir_array);

        if ( $cnt_url !== $cnt_rule ){
            // マッチしなかった
            log_info( 'debug,router', "[$rule] did not matched to [$url]: cnt_url=$cnt_url cnt_rule=$cnt_rule" );
            return NULL;
        }

        foreach( $rule_dir_array as $rule_dir ){
            $url_dir = array_shift( $url_dir_array );
            if ( strpos($rule_dir,':') === 0 && strlen($url_dir) > 0 ){
                // コロンで始まる階層は変数名
                $key = substr( $rule_dir, 1 );

                $pos_at = strpos( $key, '@' );
                if ( $pos_at === FALSE ){
                    // 型指定子(@)がないので無条件でパス名を変数にセット
                    $params[ $key ] = $url_dir;
                }
                else{
                    // 型指定子(@)がある場合は型チェック
                    $type = substr( $key, $pos_at + 1 );
                    $key = substr( $key,0, $pos_at );
                    switch( $type ){
                    case 'i':
                    case 'int':
                    case 'integer':
                        if ( preg_match('/^[0-9\-]*$/',$url_dir) ){
                            // 整数なのでOK
                            $params[ $key ] = intval($url_dir);
                        }
                        else{
                            // マッチしなかった
                            log_info( 'debug,router', "[$rule] did not matched to [$url]: rule_dir=$rule_dir url_dir=$url_dir type=integer" );
                            return NULL;
                        }
                        break;
                    case 'f':
                    case 'd':
                    case 'float':
                    case 'double':
                        if ( preg_match('/^[0-9\-\.]*$/',$url_dir) ){
                            // 少数なのでOK
                            $params[ $key ] = floatval($url_dir);
                        }
                        else{
                            // マッチしなかった
                            log_info( 'debug,router', "[$rule] did not matched to [$url]: rule_dir=$rule_dir url_dir=$url_dir type=float" );
                            return NULL;
                        }
                        break;
                    case 'a':
                    case 'alphabet':
                        if ( preg_match('/^[a-zA-Z]*$/',$url_dir) ){
                            // アルファベットなのでOK
                            $params[ $key ] = $url_dir;
                        }
                        else{
                            // マッチしなかった
                            log_info( 'debug,router', "[$rule] did not matched to [$url]: rule_dir=$rule_dir url_dir=$url_dir type=alphabet" );
                            return NULL;
                        }
                        break;
                    case 'an':
                    case 'alphanum':
                    case 'alphanumeric':
                        if ( preg_match('/^[0-9a-zA-Z]*$/',$url_dir) ){
                            // アルファベット＋数字なのでOK
                            $params[ $key ] = $url_dir;
                        }
                        else{
                            // マッチしなかった
                            log_info( 'debug,router', "[$rule] did not matched to [$url]: rule_dir=$rule_dir url_dir=$url_dir type=alphanumeric" );
                            return NULL;
                        }
                        break;
                    case 's':
                    case 'string':
                        if ( preg_match('/^[0-9a-zA-Z_\-\.\:\=]*$/',$url_dir) ){
                            // 文字列なのでOK
                            $params[ $key ] = $url_dir;
                        }
                        else{
                            // マッチしなかった
                            log_info( 'debug,router', "[$rule] did not matched to [$url]: rule_dir=$rule_dir url_dir=$url_dir type=string" );
                            return NULL;
                        }
                        break;
                    default:
                        // 型指定子(@)の後が不正
                        _throw( new Charcoal_RoutingRuleSyntaxErrorException($rule,'invalid parameter type identifier:' . $type) );
                        break;
                    }
                }
            }
            elseif ( $rule_dir !== $url_dir ){
                // マッチしなかった
                log_info( 'debug,router', "[$rule] did not matched to [$url]: rule_dir=$rule_dir url_dir=$url_dir" );
                return NULL;
            }
            log_info( 'debug,router', "maches directory: [$rule_dir]" );
        }

        log_info( 'debug,router', "[$rule] matched to [$url]" );
        log_info( 'debug,router', "parameters:" . print_r($params,true) );
        log_info( 'debug,router', "URLマッピングルールにマッチしました。ルール=[$rule]" );

        return $params;
    }

}

