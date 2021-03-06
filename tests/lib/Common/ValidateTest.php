<?php
/**
 * Web App REST API
 *
 * @link https://github.com/kobabasu/rest-php.git
 */

namespace Lib\Common;

use \PHPUnit\Framework\TestCase;

/**
 * Validateクラス用のテストファイル
 *
 * @package Common
 */
class ValidateTest extends TestCase
{
    /** @var Object $object 対象クラス */
    protected $object;

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->object = new Validate();
    }

    /**
     * @ignore
     */
    protected function tearDown(): void
    {
    }

    /**
     * 正常系 ケータイ用ドメインがtrueとなるか
     *
     * @covers Lib\Common\Validate::isKetai()
     * @test testSetIsKetaiNormal()
     */
    public function testSetIsKetaiNormal()
    {
        $addr = 'failure@dj.pdx.ne.jp';
        $res = $this->object->isKetai($addr);
        $this->assertTrue($res);
    }

    /**
     * 異常系エラー 通常のドメインがfalseとなるか
     *
     * @covers Lib\Common\Validate::isKetai()
     * @test testSetIsKetaiError()
     */
    public function testSetIsKetaiError()
    {
        $addr = 'info@example.com';
        $res = $this->object->isKetai($addr);
        $this->assertFalse($res);
    }
}
