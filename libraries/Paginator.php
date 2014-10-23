<?php
/**
 * Paginator.php
 *
 * (c) 2014 overtrue <anzhengchao@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author overtrue <anzhengchao@gmail.com>
 * @github https://github.com/overtrue
 * @url    http://overtrue.me
 * @date   2014-10-23T20:05:33
 */

class Paginator
{
    protected $request;
    protected $pager;
    protected $pageSize;
    protected $total;

    /**
     * Constructor
     *
     * @param \Slim\Http\Request $request
     * @param string             $pager
     */
    public function __construct($request, $pager = 'page')
    {
        $this->request = $request;
        $this->pager = $pager;
    }

    /**
     * Make a pagination
     *
     * @param array   $items
     * @param integer $total
     * @param integer $pageSize
     *
     * @return array
     */
    public function make($items, $total, $pageSize = 10)
    {
        $this->total    = abs($total);
        $this->pageSize = $pageSize;

        return $items;
    }

    /**
     * Return current page
     *
     * @param integer $total
     *
     * @return integer
     */
    public function getCurrentPage($total = 1)
    {
        $page = abs($this->request->get('page', 1));
        $this->total = $total;

        $totalPage = $this->getTotalPage();

        $page >= 1 || $page = 1;

        $page <= $totalPage || $page = $totalPage;

        return $page;
    }

    /**
     * Return total pages
     *
     * @return integer
     */
    public function getTotalPage()
    {
        $this->pageSize > 0 || $this->pageSize = 10;

        $totalPage = ceil($this->total / $this->pageSize);

        $totalPage >= 1 || $totalPage = 1;

        return $totalPage;
    }
}