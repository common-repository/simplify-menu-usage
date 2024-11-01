<?php
namespace SimplifyMenuUsage\Interfaces;

if(!defined('ABSPATH')){
    exit;
}

interface IFactory{
    public function get_object();
}

