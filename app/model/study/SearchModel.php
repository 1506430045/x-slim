<?php
/**
 * Created by PhpStorm.
 * User: forward
 * Date: 2018/10/20
 * Time: 下午8:35
 */

namespace App\model\study;


class SearchModel
{
    private $list;
    private $val;
    private $count;

    public function __construct(array $list, int $val)
    {
        $this->list = $list;
        $this->val = $val;
        $this->count = count($this->list);
    }

    public function binarySearch()
    {
        $lower = 0;
        $high = $this->count - 1;
        while ($lower <= $high) {
            $middle = intval(($lower + $high) / 2);
            if ($this->val > $this->list[$middle]) {
                $lower = $middle + 1;
            } elseif ($this->val < $this->list[$middle]) {
                $high = $middle - 1;
            } else {
                return $middle;
            }
        }
        return -1;
    }
}