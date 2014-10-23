<?php
/**
 * ReponseMiddleware.php
 *
 * (c) 2014 overtrue <anzhengchao@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author overtrue <anzhengchao@gmail.com>
 * @github https://github.com/overtrue
 * @url    http://overtrue.me
 * @date   2014-10-23T21:48:45
 */

class ResponseMiddleware extends \Slim\Middleware
{
    /**
     * Run middleware
     *
     * @return
     */
    public function call()
    {
        $app = $this->app;
        $env = $app->environment;
        $req = $app->request;
        $res = $app->response;

        // 如果禁用了格式处理则跳过
        if ($app->config['response_format'] == 'disabled') {
            $this->next->call();
        }

        var_dump($this->getAcceptHeader($req));
    }

    public function getAcceptHeader($req)
    {
        $accept = $req->headers->get('Accept');
    }
}