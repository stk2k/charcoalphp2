<?php
/**
* メール送信コンポーネント
*
* PHP version 5
*
* @package    components.mail
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

require_once( 'qdmail.php' );
require_once( 'qdsmtp.php' );

require_once( 'QdmailException' . CHARCOAL_CLASS_FILE_SUFFIX );
require_once( 'QdmailSmtpException' . CHARCOAL_CLASS_FILE_SUFFIX );
require_once( 'QdmailAddress' . CHARCOAL_CLASS_FILE_SUFFIX );

class Charcoal_QdmailSender extends Charcoal_CharcoalComponent implements Charcoal_IComponent
{
	private $qdmail;

	/*
	 *    コンストラクタ
	 */
	public function __construct()
	{
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
		parent::configure( $config );

		// =========================================
		// QdMail設定
		// =========================================

		// ログ設定
		$qdmail_log_level    = $config->getInteger( s('qdmail.log_level'), i(0) )->getValue();
		$qdmail_log_path     = $config->getString( s('qdmail.log_path'), s('') )->getValue();
		$qdmail_log_filename = $config->getString( s('qdmail.log_filename'), s('') )->getValue();

		$this->qdmail->logLevel( $qdmail_log_level );
		$this->qdmail->logPath( $qdmail_log_path );
		$this->qdmail->logFilename( "/" . $qdmail_log_filename );

		// エラーログ設定
		$qdmail_error_display      = $config->getBoolean( s('qdmail.error_display'), b(FALSE) )->getValue();
		$qdmail_error_log_level    = $config->getInteger( s('qdmail.error_log_level'), i(0) )->getValue();
		$qdmail_error_log_path     = $config->getString( s('qdmail.error_log_path'), s('') )->getValue();
		$qdmail_error_log_filename = $config->getString( s('qdmail.error_log_filename'), s('') )->getValue();

		$this->qdmail->errorDisplay( $qdmail_error_display );
		$this->qdmail->errorlogLevel( $qdmail_error_log_level );
		$this->qdmail->errorlogPath( $qdmail_error_log_path );
		$this->qdmail->errorlogFilename( "/" . $qdmail_error_log_filename );

		// =========================================
		// QdSMTP設定
		// =========================================

		$qdsmtp = $config->getBoolean( s('qdsmtp'), b(FALSE) );
		if ( $qdsmtp->isFalse() ){
			$this->qdmail->smtp(false);
		}
		else{
			// ログ設定
			$qdsmtp_log_level          = $config->getInteger( s('qdsmtp.log_level'), i(0) )->getValue();
			$qdsmtp_log_filename       = $config->getString( s('qdsmtp.log_filename'), s('') )->getValue();
			$qdsmtp_error_log_level    = $config->getInteger( s('qdsmtp.error_log_level'), i(0) )->getValue();
			$qdsmtp_error_log_filename = $config->getString( s('qdsmtp.error_log_filename'), s('') )->getValue();
			$qdsmtp_error_display      = $config->getBoolean( s('qdsmtp.error_display'), b(FALSE) )->getValue();

			$this->qdmail->smtpObject()->logLevel( $qdsmtp_log_level );
			$this->qdmail->smtpObject()->logFilename( $qdsmtp_log_filename );
			$this->qdmail->smtpObject()->errorlogLevel( $qdsmtp_error_log_level );
			$this->qdmail->smtpObject()->errorlogFilename( $qdsmtp_error_log_filename );
			$this->qdmail->smtpObject()->error_display = $qdsmtp_error_display;

			// サーバ設定
			$qdsmtp_host     = $config->getString( s('qdsmtp.host'), s('') )->getValue();
			$qdsmtp_port     = $config->getString( s('qdsmtp.port'), s('') )->getValue();
			$qdsmtp_from     = $config->getString( s('qdsmtp.from'), s('') )->getValue();
			$qdsmtp_protocol = $config->getString( s('qdsmtp.protocol'), s('') )->getValue();
			$qdsmtp_user     = $config->getString( s('qdsmtp.user'), s('') )->getValue();
			$qdsmtp_pass     = $config->getString( s('qdsmtp.pass'), s('') )->getValue();

			$options = array(
					'host' => $qdsmtp_host,
					'port' => $qdsmtp_port,
					'from' => $qdsmtp_from,
					'protocol' => $qdsmtp_protocol,
					'user' => $qdsmtp_user,
					'pass' => $qdsmtp_pass,
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
		else if ( $data instanceof Charcoal_QdmailAddress ){
			// アドレスは１つのみ、QdmailAddressのインスタンス
			$address = $data;
			if ( $address->hasLabel() ){
				$out[] = array( $address->getAddress(), $address->getLabel() );
			}
			else{
				$out[] = us( $address->getAddress() );
			}
		}
		else if ( is_array($data) ){
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
				else if ( $item instanceof Charcoal_QdmailAddress ){
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

