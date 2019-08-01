<?php
/**
 * Web App REST API
 *
 * @link https://github.com/kobabasu/rest-php.git
 */

namespace Lib\SwiftMailer;

/**
 * SwiftMailerの初期化クラス
 *
 * @package SwiftMailer
 */
class Init
{
    /** クラスの保存先 最後にseparatorはいらない */
    const LOG_DIR = 'logs/mail';

    /** メール分割送信の分割数 */
    const INTERVAL_COUNT = 100;

    /** メール分割送信の間隔 */
    const INTERVAL_TIME = 30;

    /** リターンメール内で宛先元をヘッダに含める */
    const X_ORIGINAL_TO = true;

    /** @var String $host ホスト名 */
    private $host;

    /** @var String $port ポート番号 */
    private $port;

    /** @var String $user ユーザ名 */
    private $user;

    /** @var String $pass パスワード */
    private $pass;

    /** @var String $pass リターンメール用アドレス */
    private $fail;

    /** @var String $charset 文字コード */
    private $charset;

    /** @var String $path ログファイルパス */
    private $path;

    /** @var Object $logger ログ機能のオブジェクト */
    private $logger;

    /** @var Object $message Swift Message Object */
    private $message;

    /**
     * Swift初期化
     *
     * @param String $host
     * @param String $port
     * @param String $user
     * @param String $pass
     * @param String $charset
     * @return vold
     * @codeCoverageIgnore
     */
    public function __construct(
        $host,
        $port,
        $user,
        $pass,
        $fail = null,
        $charset = 'iso-2022-jp'
    ) {
        $this->host = $host;
        $this->port = $port;
        $this->user = $user;
        $this->pass = $pass;
        $this->fail = $fail;
        $this->charset = $charset;
        $this->setPath();

        \Swift::init(function () use ($charset) {
            \Swift_DependencyContainer::getInstance()
                ->register('mime.qpheaderencoder')
                ->asAliasOf('mime.base64headerencoder');
            \Swift_Preferences::getInstance()->setCharset(
                $charset
            );
        });
    }

    /**
     * Mailerを返す
     *
     * @return Object
     * @codeCoverageIgnore
     */
    public function getMailer()
    {
        $transport = new \Swift_SmtpTransport(
            $this->host,
            $this->port
        );

        $transport->setUsername($this->user);
        $transport->setPassword($this->pass);

        $mailer = new \Swift_Mailer($transport);

        $mailer = $this->setAntiFlood($mailer);
        $mailer = $this->setLog($mailer);

        return $mailer;
    }

    /**
     * Swiftのメッセージオブジェクトを返す
     *
     * @param String $subject
     * @param Array $from
     * @param String $body
     * @return Object
     * @codeCoverageIgnore
     */
    public function setMessage(
        $subject,
        $from,
        $body
    ) {
        $message = (new \Swift_Message($subject))
            ->setFrom($from)
            ->setBody($body);

        $message->setCharset($this->charset);
        /*
        $message->setEncoder(
            \Swift_Encoding::get7BitEncoding()
        );
         */

        $this->message = $message;

        $this->setReturnPath();
        $this->addTextHeader('X-Original-To');
        
        return $message;
    }

    /**
     * Mailerに分割送信機能を追加
     *
     * @param Object $mailer
     * @return Object
     * @codeCoverageIgnore
     */
    private function setAntiFlood(\Swift_Mailer $mailer)
    {
        $mailer->registerPlugin(
            new \Swift_Plugins_AntiFloodPlugin(
                self::INTERVAL_COUNT,
                self::INTERVAL_TIME
            )
        );

        return $mailer;
    }

    /**
     * Mailerにログ機能を追加
     *
     * @param Object $mailer
     * @return object
     * @codeCoverageIgnore
     */
    private function setLog(\Swift_Mailer $mailer)
    {
        $logger = new \Swift_Plugins_Loggers_ArrayLogger();
        $mailer->registerPlugin(
            new \Swift_Plugins_LoggerPlugin($logger)
        );

        $this->logger = $logger;
        return $mailer;
    }

    /**
     * ログファイルのパスを決定
     *
     * @param String $dir
     * @param String $filename
     * @return void
     */
    public function setPath(
        $dir = null,
        $filename = null
    ) {
        if (is_null($dir)) {
            $dir = self::LOG_DIR;
        }

        if (is_null($filename)) {
            $filename = date('ymd');
        }

        $this->path = $dir . '/' . $filename . '.log';
    }

    /**
     * ログファイルのパスを返す
     *
     * @return String
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * ログを保存
     *
     * @return void
     */
    public function saveLog()
    {
        $log = $this->logger->dump();

        file_put_contents(
            $this->path,
            $log,
            FILE_APPEND
        );
    }

    /**
     * リターンメール用のアドレスを設定
     * もし、configで空であればなにもしない
     *
     * @return void
     */
    private function setReturnPath()
    {
        if (!empty($this->fail)) {
            $this->message->setReturnPath(
                $this->fail
            );
        }
    }

    /**
     * テキストのカスタムヘッダを追加
     * (テキストタイプのヘッダのみ)
     * mailbox, date, key-valueタイプはマニュアル参照
     * もし、X_ORIGINAL_TOがfalseであればなにもしない
     *
     * @param String $field
     * @return void
     */
    private function addTextHeader($field)
    {
        if (self::X_ORIGINAL_TO) {
            $header = $this->message->getHeaders();
            $header->addTextHeader($field, '');
        }
    }
}
