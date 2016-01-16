<?php
/**
 * Web App REST API
 *
 * @link https://github.com/kobabasu/rest-php.git
 */

namespace Lib\Db;

/**
 * Getクラス用のテストファイル
 *
 * @package Db
 */
class GetTest extends \PHPUnit_Framework_TestCase
{
    protected $object;

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp()
    {
        $pdo = new \Lib\Db\Connect(
            '127.0.0.1',
            'api',
            'api',
            'api012',
            '3306',
            true
        );
        $dbh = $pdo->getConnection();
        $stub = new \Lib\Db\Get($dbh, true);

        $this->object = $stub;
    }

    /**
     * @ignore
     */
    protected function tearDown()
    {
    }

    /**
     * executeが正しく返すか
     *
     * @covers Lib\Db\Get::execute()
     * @test testExecuteFail()
     */
    public function testExecuteFail()
    {
        try {
            $sql = 'SELECT count(*) as `res` FROM `user`';
            $res = $this->object->execute($sql);
            $this->fail('fail');
        } catch (\Exception $e) {
            $this->assertEquals('fail', $e->getMessage());
        }
    }
}
