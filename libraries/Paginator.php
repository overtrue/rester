<?php

namespace Rester;

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

use Closure;
use Countable;
use ArrayAccess;
use Serializable;
use ArrayIterator;
use JsonSerializable;
use IteratorAggregate;

class Paginator implements
    ArrayAccess,
    Countable,
    IteratorAggregate,
    Serializable,
    JsonSerializable
{
    protected $pager;
    protected $pageSize;
    protected $total;
    protected $items;

    /**
     * Constructor
     *
     * @param \Slim\Http\Request $request
     * @param string             $pager
     */
    public function __construct($pager = 'page')
    {
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
        $this->items = $items;

        return $this;
    }

    /**
     * Return current page
     *
     * @return integer
     */
    public function getCurrentPage($total = null)
    {
        $page = abs(app()->request->get('page', 1));

        if ($total) {
            $this->total = $total;
        }

        $page >= 1 || $page = 1;

        if ($this->items) {
            $totalPage = $this->getTotalPage();
            $page <= $totalPage || $page = $totalPage;
        }

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

    public function links()
    {
        $html = '<ul class="pagination">';

        $totalPage   = $this->getTotalPage();
        $currentPage = $this->getCurrentPage();

        if ($totalPage < 10) {
            for ($i = 1; $i <= $totalPage; $i++) {
                $active = $i == $currentPage ? 'class="active"':'';
                $html .= "<li $active><a href=".$this->getLink($i).">$i</a></li>";
            }
        } else {
            for ($i = $currentPage - 4; $i <= $currentPage; $i++) {
                $active = $i == $currentPage ? 'class="active"':'';
                $html .= "<li $active><a href=".$this->getLink($i).">$i</a></li>";
            }

            if ($totalPage - $currentPage >= 5) {
                $html .= "<li><a href='javascript:void(0)'>...</a></li>";

                for ($i = $currentPage + 1; $i <= $totalPage; $i++) {
                    $html .= "<li><a href=".$this->getLink($i).">$i</a></li>";
                }
            }
        }

        return $html .= '</ul>';
    }

    /**
     * getLink
     *
     * @param integer $page
     *
     * @return string
     */
    public function getLink($page)
    {
        static $query;

        if (is_null($query)) {
            $query = app()->request->get();
        }

        $query['page'] = $page;

        return "?" . http_build_query($query);
    }

     /** {@inhertDoc} */
    public function jsonSerialize()
    {
        return $this->items;
    }

    /** {@inhertDoc} */
    public function serialize()
    {
        return serialize($this->items);
    }
    /** {@inhertDoc} */
    public function unserialize($data)
    {
        return $this->items = unserialize($data);
    }
    /** {@inhertDoc} **/
    public function getIterator()
    {
        return new ArrayIterator($this->items);
    }
    /** {@inhertDoc} */
    public function count($mode = COUNT_NORMAL)
    {
        return count($this->items, $mode);
    }
    /**
     * Get a data by key
     *
     * @param string $key
     *
     * @return mixed
     */
    public function __get($key) {
        return $this[$key];
    }
    /**
     * Assigns a value to the specified data
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return void
     */
    public function __set($key, $value)
    {
        $this->items[$key] = $value;
    }
    /**
     * Whether or not an data exists by key
     *
     * @param string $key
     *
     * @return bool
     */
    public function __isset($key)
    {
        return isset($this->items[$key]);
    }
    /**
     * Unsets an data by key
     *
     * @param string $key
     */
    public function __unset($key)
    {
        unset($this->items[$key]);
    }
    /**
     * Assigns a value to the specified offset
     *
     * @param string $offset
     * @param mixed  $value
     *
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        $this->items[$offset] = $value;
    }
    /**
     * Whether or not an offset exists
     *
     * @param string $offset
     *
     * @access public
     *
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->items[$offset]);
    }
    /**
     * Unsets an offset
     *
     * @param string $offset
     *
     * @return array
     */
    public function offsetUnset($offset)
    {
        if ($this->offsetExists($offset)) {
            unset($this->items[$offset]);
        }
    }
    /**
     * Returns the value at specified offset
     *
     * @param string $offset
     *
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->offsetExists($offset) ? array_get($this->items, $offset) : null;
    }
}
