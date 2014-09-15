<?php
/**
* メール送信コンポーネント
*
* PHP version 5
*
* @package    component.qdmail
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

require_once( 'QdmailException.class.php' );
require_once( 'QdmailSmtpException.class.php' );
require_once( 'QdmailAddress.class.php' );

class Charcoal_QdmailSender extends Charcoal_CharcoalComponent implements Charcoal_IComponent
{
	private $qdmail;

	/*
	 *    コンストラクタ
	 */
	public function __construct()
	{
		$switcher = new Charcoal_ErrorReportingSwitcher(0,E_DEPRECATED);

		require_once( 'qdmail.php' );
		require_once( 'qdsmtp.php' );

		parent::__construct();

		$this->qdmail = new Qdmail('utf-8' , 'base64');

		$this->qdmail->unitedCharset( 'UTF-8' );

		$this->qdmail->lineFeed( "\n" );		// 改行コードは\n（ヘッダが見えてしまうため）

		$this->qdmail->is_qmail = FALSE;		// 改行コードは\n（ヘッダが見えてしまうため）

	}

	/**
	 * Initialize instance
	 *
	 * @param Charcoal_Config $config   configuration data
	 */
	public function configure( $config )
	{
		$switcher = new Charcoal_ErrorReportingSwitcher(0,E_DEPRECATED);

		parent::configure( $config );

		if ( is_array($config) || $config === NULL ){
			$config = new Chacoal_Config( $config );
		}

		// =========================================
		// QdMail設定
		// =========================================

		// エラーを画面に出力するか
		$qdmail_error_display    = $config->getBoolean( 'qdmail.error_display', FALSE );

		$this->qdmail->errorDisplay( ub($qdmail_error_display) );

		// ログ設定
		$qdmail_log_level    = $config->getInteger( 'qdmail.log_level', 0 );
		$qdmail_log_path     = $config->getString( 'qdmail.log_path', '', TRUE );
		$qdmail_log_filename = $config->getString( 'qdmail.log_filename', '', TRUE );

		$this->qdmail->logLevel( ui($qdmail_log_level) );
		$this->qdmail->logPath( us($qdmail_log_path) );
		$this->qdmail->logFilename( "/" . $qdmail_log_filename );

		// エラーログ設定
		$qdmail_error_display      = $config->getBoolean( 'qdmail.error_display', FALSE );
		$qdmail_error_log_level    = $config->getInteger( 'qdmail.error_log_level', 0 );
		$qdmail_error_log_path     = $config->getString( 'qdmail.error_log_path', '', TRUE );
		$qdmail_error_log_filename = $config->getString( 'qdmail.error_log_filename', '', TRUE );

		$this->qdmail->errorDisplay( ub($qdmail_error_display) );
		$this->qdmail->errorlogLevel( ui($qdmail_error_log_level) );
		$this->qdmail->errorlogPath( us($qdmail_error_log_path) );
		$this->qdmail->errorlogFilename( "/" . $qdmail_error_log_filename );

		// =========================================
		// QdSMTP設定
		// =========================================

		$qdsmtp = $config->getBoolean( 'qdsmtp', FALSE );
		if ( !$qdsmtp ){
			$this->qdmail->smtp(false);
		}
		else{
			// エラーを画面に出力するか
			$qdsmtp_error_display    = $config->getBoolean( 'qdsmtp.error_display', FALSE );

			$this->qdmail->smtpObject()->error_display = ub($qdsmtp_error_display);

			// ログ設定
			$qdsmtp_log_level          = $config->getInteger( 'qdsmtp.log_level', 0 );
			$qdsmtp_log_filename       = $config->getString( 'qdsmtp.log_filename', '', TRUE );
			$qdsmtp_error_log_level    = $config->getInteger( 'qdsmtp.error_log_level', 0 );
			$qdsmtp_error_log_filename = $config->getString( 'qdsmtp.error_log_filename', '', TRUE );
			$qdsmtp_error_display      = $config->getBoolean( 'qdsmtp.error_display', FALSE );

			$this->qdmail->smtpObject()->logLevel( ui($qdsmtp_log_level) );
			$this->qdmail->smtpObject()->logFilename( us($qdsmtp_log_filename) );
			$this->qdmail->smtpObject()->errorlogLevel( ui($qdsmtp_error_log_level) );
			$this->qdmail->smtpObject()->errorlogFilename( us($qdsmtp_error_log_filename) );
			$this->qdmail->smtpObject()->error_display = $qdsmtp_error_display;

			// サーバ設定
			$qdsmtp_host     = $config->getString( 'qdsmtp.host', '' );
			$qdsmtp_port     = $config->getString( 'qdsmtp.port', '' );
			$qdsmtp_from     = $config->getString( 'qdsmtp.from', '' );
			$qdsmtp_protocol = $config->getString( 'qdsmtp.protocol', '' );
			$qdsmtp_user     = $config->getString( 'qdsmtp.user', '' );
			$qdsmtp_pass     = $config->getString( 'qdsmtp.pass', '' );

			$options = array(
					'host' => us($qdsmtp_host),
					'port' => us($qdsmtp_port),
					'from' => us($qdsmtp_from),
					'protocol' => us($qdsmtp_protocol),
					'user' => us($qdsmtp_user),
					'pass' => us($qdsmtp_pass),
				);

			$this->qdmail->smtp(true);
			$result = $this->qdmail->smtpServer( $options );
			if ( $result === FALSE ){
				_throw( new Charcoal_QdmailException($this->qdmail) );
			}
		}

		log_debug( "debug, qdmail_sender", "component configs are:" . print_r($config,true) );
	}

	/*
	 * アドレス配列を取得
	 */
	private function _getAddressList( $data )
	{
		$out = array();

		if ( is_string($data) || $data instanceof Charcoal_String ){
			// 表示名<メールアドレス>を自動変換
			mb_regex_encoding('UTF-8');
			$pattern = '/(?P<label>.*)\<(?P<address>.*)\>/';
			$ret = preg_match( $pattern, $data, $match );
			log_debug( "debug, qdmail_sender", "preg_match data:" . print_r($data,true) );
			log_debug( "debug, qdmail_sender", "preg_match($ret):" . print_r($match,true) );
			if ( $ret ){
				// ラベルあり
				$label = $match['label'];
				$address = $match['address'];
				$valid = Charcoal_MailUtil::validateAddress( s($address) );
				if ( !$valid ){
					_throw( new Charcoal_InvalidMailAddressException( $address ) );
				}
				$out[] = array( $address, $label );
			}
			else{
				// アドレスは１つのみ、表示名なし
				$valid = Charcoal_MailUtil::validateAddress( s($data) );
				if ( !$valid ){
					_throw( new Charcoal_InvalidMailAddressException( $data ) );
				}
				$out[] = us($data);
			}
		}
		elseif ( $data instanceof Charcoal_QdmailAddress ){
			// アドレスは１つのみ、QdmailAddressのインスタンス
			$address = $data;
			if ( $address->hasLabel() ){
				$out[] = array( $address->getAddress(), $address->getLabel() );
			}
			else{
				$out[] = us( $address->getAddress() );
			}
		}
		elseif ( is_array($data) ){
			// 配列の場合
			foreach( $data as $item )
			{
				if ( is_string($item) || $item instanceof Charcoal_String ){
					$item = trim($item);
					// 表示名<メールアドレス>を自動変換
					mb_regex_encoding('UTF-8');
					$pattern = '/(?P<label>.*)\<(?P<address>.*)\>/';
					$ret = preg_match( $pattern, $item, $match );
					log_debug( "debug, qdmail_sender", "preg_match item:" . print_r($item,true) );
					log_debug( "debug, qdmail_sender", "preg_match($ret):" . print_r($match,true) );
					if ( $ret ){
						// ラベルあり
						$label = $match['label'];
						$address = $match['address'];
						$valid = Charcoal_MailUtil::validateAddress( s($address) );
						if ( !$valid ){
							_throw( new Charcoal_InvalidMailAddressException( $address ) );
						}
						$out[] = array( $address, $label );
					}
					else{
						// 表示名なし
						$valid = Charcoal_MailUtil::validateAddress( s($item) );
						if ( !$valid ){
							_throw( new Charcoal_InvalidMailAddressException( $item ) );
						}
						$out[] = us($item);
					}
				}
				elseif ( $item instanceof Charcoal_QdmailAddress ){
					// QdmailAddressのインスタンス
					$address = $item;
					if ( $address->hasLabel() ){
						$out[] = array( us($address->getAddress()), us($address->getLabel()) );
					}
					else{
						$out[] = us( $address->getAddress() );
					}
				}
			}
		}

		return $out;
	}

	/*
	 * テキストメール送信
	 */
	public function sendMail( $from, $to, $subject, $body, $cc = NULL, $bcc = NULL)
	{
		$switcher = new Charcoal_ErrorReportingSwitcher(0,E_DEPRECATED);
		
		log_debug( "debug, qdmail_sender", __CLASS__ . ":" . __METHOD__ . " start." );

		// 送信先
		$from_list = $this->_getAddressList( $from );

		$to_list = $this->_getAddressList( $to );
		if ( $cc ){
			$cc_list = $this->_getAddressList( $cc );
		}
		if ( $bcc ){
			$bcc_list = $this->_getAddressList( $bcc );
		}

		$this->qdmail->to( $to_list );
		$this->qdmail->from( $from_list );
		$this->qdmail->subject( $subject );
		$this->qdmail->text( $body );

		log_debug( "debug, qdmail_sender", "to:" . print_r($to_list,true) );
		log_debug( "debug, qdmail_sender", "from_list:" . print_r($from_list,true) );
		log_debug( "debug, qdmail_sender", "subject:" . print_r($subject,true) );
		log_debug( "debug, qdmail_sender", "body:" . print_r($body,true) );

		// 送信
		$result = $this->qdmail->send();
		log_debug( "debug, qdmail_sender", "result:" . print_r($result,true) );

		if ( $result === FALSE ){
			_throw( new Charcoal_QdmailSmtpException($this->qdmail->smtpObject()) );
		}

		log_debug( "debug, qdmail_sender", __CLASS__ . ":" . __METHOD__ . " success." );
	}

}

