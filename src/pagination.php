<?php

namespace ptcms\extend;

class Pagination
{
    
    public $pagesize;
    
    public $totalnum;
    public $pagenum;
    public $page;
    
    
    public $section = 4;
    
    public function __construct($totalnum, $page = 1, $pagesize = 20)
    {
        $this->page     = $page;
        $this->pagesize = $pagesize;
        $this->totalnum = $totalnum;
    }
    
    public function data()
    {
        $this->pagenum = ceil($this->totalnum / $this->pagesize);
        
        $list['num'] = [];
        if ($this->page == 1) {
            $list['first'] = ['num' => 1, 'status' => 1];
            $list['prev']  = ['num' => 1, 'status' => 1];
        } else {
            $list['first'] = ['num' => 1, 'status' => 0];
            $list['prev']  = ['num' => $this->page - 1, 'status' => 0];
        }
        if ($this->page == $this->pagenum) {
            $list['last'] = ['num' => $this->pagenum, 'status' => 1];
            $list['next'] = ['num' => $this->pagenum, 'status' => 1];
        } else {
            $list['last'] = ['num' => $this->pagenum, 'status' => 0];
            $list['next'] = ['num' => $this->page + 1, 'status' => 0];
        }
        $start = $this->page - $this->section;
        if ($start >= 1) {
            $end = $this->page + $this->section;
            if ($end > $this->pagenum) {
                $end   = $this->pagenum;
                $start = $this->pagenum - 2 * $this->section;
                $start = ($start < 1) ? 1 : $start;
            }
        } else {
            $start = 1;
            $end   = 1 + 2 * $this->section;
            $end   = ($end > $this->pagenum) ? $this->pagenum : $end;
        }
        for ($i = $start; $i <= $end; $i++) {
            $list['num'][] = [
                'num'    => $i,
                'status' => ($i == $this->page) ? 1 : 0,
            ];
        }
        $list['totalnum'] = $this->totalnum;
        $list['page']     = $this->page;
        $list['pagenum']  = $this->pagenum;
        $list['pagesize'] = $this->pagesize;
        return $list;
    }
    
}