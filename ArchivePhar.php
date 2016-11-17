<?php
/**
 *
 * php工程打包
 *
 * @author: rudy
 * @date: 2016/11/16
 */

$phar = new Phar("/data/swoole_chat.phar",0,"swoole_chat.phar");
$phar->buildFromDirectory(__DIR__);
$phar->setStub($phar->createDefaultStub("index.php","index.php"));
$phar->compressFiles(Phar::GZ);