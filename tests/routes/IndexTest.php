<?php
/**
 * Web App REST API
 *
 * @link https://github.com/kobabasu/rest-php.git
 */

namespace Routes;

/**
 * Dbクラス用のテストファイル
 *
 * @package Routes
 */
class IndexTest extends AppMock
{
    /** @var Object $object 対象クラス */
    protected $path = '/';

    /** @var Object $object 対象クラス */
    protected $filename = 'routes/index.php';

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp()
    {
    }

    /**
     * @ignore
     */
    protected function tearDown()
    {
    }

    /**
     * 正常系 '/'のgetがHelloと返すか
     *
     * @test testIndexzGetNormal()
     */
    public function testIndexGetNormal()
    {
        $app = $this->create($this->path);
        require $this->filename;
        $resOut = $this->invoke($app);

        $this->assertEquals(
            'hello',
            (string)$resOut->getBody()
        );
    }

    /**
     * 正常系 '/'のgetのContent-Typeがtext/htmlか
     *
     * @test testIndexzGetContentTypeNormal()
     */
    public function testIndexGetContentTypeNormal()
    {
        $app = $this->create($this->path);
        require $this->filename;
        $resOut = $this->invoke($app);

        $this->assertEquals(
            'text/html',
            $resOut->getHeader('Content-Type')[0]
        );
    }
}
