<?php
namespace SimplifyMenuUsage\Interfaces;

if(!defined('ABSPATH')){
    exit;
}

interface IValidator{
    public function validate($input);
}

