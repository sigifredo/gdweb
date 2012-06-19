<?php

class Date
{
    protected $day = null;
    protected $month = null;
    protected $year = null;

    public function __construct($day, $month, $year)
    {
        $this->day = $day;
        $this->month = $month;
        $this->year = $year;
    }

    public function day()
    {
        return $this->day;
    }

    public function month()
    {
        return $this->month;
    }

    public function year()
    {
        return $this->year;
    }

    public function toStr()
    {
        return "$this->day/".(($this->month<10)?"0$this->month":"$this->month")."/$this->year";
    }

}
