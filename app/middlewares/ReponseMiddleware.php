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

        $wants = $req->headers->get('Content-Type');

        !empty($wants) || $wants = 'application/json';

        $format = last(explode('/', $wants));

        if (method_exists($this, $format)) {
            $this->{$format}($req, $res);
        }
    }

    /**
     * json/jsonp格式输出
     *
     * @param \Slim\Http\Request  $req
     * @param \Slim\Http\Response $res
     *
     * @return \Slim\Http\Response
     */
    protected function json($req, $res)
    {
        $data = $res->getBody();

        $json = json_encode($data, JSON_UNESCAPED_UNICODE);

        $callback = $req->get('callback');

        $body = $callback ? "{$callback}($json)" : $json;

        $res->headers->set('Content-Type', 'application/json');

        return $res->setBody($body, 200);
    }

    /**
     * xml格式输出
     *
     * @param \Slim\Http\Request  $req
     * @param \Slim\Http\Response $res
     *
     * @return \Slim\Http\Response
     */
    protected function xml($req, $res)
    {
        $res->headers->set('Content-Type', 'application/xml');

        return $res->setBody($body, 200);
    }

}