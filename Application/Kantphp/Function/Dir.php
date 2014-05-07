<?php

/**
 * @package KantPHP
 * @author  Zhenqiang Zhang <565364226@qq.com>
 * @copyright (c) 2011 - 2013 KantPHP Studio, All rights reserved.
 * @license http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 */

/**
 *
 * Enter dir path
 * 
 * @param path string
 * @return path string
 */
function dir_path($path) {
    $path = str_replace('\\', '/', $path);
    if (substr($path, -1) != '/') {
        $path = $path . '/';
    }
    return $path;
}

/**
 *
 * Create dir
 *
 * @param path string
 * @param mode string
 * @return boolean True on success or false
 */
function dir_create($path, $mode = 0777) {
    if (is_dir($path)) {
        return true;
    }
    $path = dir_path($path);
    $temp = explode('/', $path);
    $cur_dir = '';
    $max = count($temp) - 1;
    for ($i = 0; $i < $max; $i++) {
        $cur_dir .= $temp[$i] . '/';
        if (@is_dir($cur_dir)) {
            continue;
        }
        @mkdir($cur_dir, 0777, true);
        @chmod($cur_dir, 0777);
    }
    return is_dir($path);
}

/**
 *
 * Copy dir recursively
 *
 * @param fromdir string
 * @param todir string
 * @return boolean true on success or false
 */
function dir_copy($fromdir, $todir) {
    $fromdir = dir_path($fromdir);
    $todir = dir_path($todir);
    if (!is_dir($fromdir)) {
        return false;
    }
    if (!is_dir($todir)) {
        dir_create($todir);
    }
    $list = glob($fromdir . '*');
    if (!empty($list)) {
        foreach ($list as $v) {
            $path = $todir . basename($v);
            if (is_dir($v)) {
                dir_copy($v, $path);
            } else {
                copy($v, $path);
                @chmod($path, 0777);
            }
        }
    }
    return true;
}

/**
 *
 * Character conversion
 *
 * @param in_charset string
 * @param out_charset string
 * @param dir string
 * @param fileexts string
 * @return boolean True on success or false
 */
function dir_iconv($in_charset, $out_charset, $dir, $fileexts = 'php|html|htm|shtml|shtm|js|txt|xml') {
    if ($in_charset == $out_charset) {
        return false;
    }
    $list = dir_list($dir);
    foreach ($list as $v) {
        if (preg_match("/\.($fileexts)/i", $v) && is_file($v)) {
            file_put_contents($v, iconv($in_charset, $out_charset, file_get_contents($v)));
        }
    }
    return true;
}

/**
 *
 * List files
 *
 * @param path string
 * @param exts string
 * @param list array
 * @return array
 */
function dir_list($path, $exts = '', $list = array()) {
    $path = dir_path($path);
    $files = glob($path . '*');
    foreach ($files as $v) {
        $fileext = fileext($v);
        if (!$exts || preg_match("/\.($exts)/i", $v)) {
            $list[] = $v;
            if (is_dir($v)) {
                $list = dir_list($v, $exts, $list);
            }
        }
    }
    return $list;
}

/**
 * Touch time
 * 
 * @param type $path
 * @param type $mtime
 * @param type $atime
 * @return boolean
 */
function dir_touch($path, $mtime = TIME, $atime = TIME) {
    if (!is_dir($path)) {
        return false;
    }
    $path = dir_path($path);
    if (!is_dir($path)) {
        touch($path, $mtime, $atime);
    }
    $files = glob($path . '*');
    foreach ($files as $v) {
        is_dir($v) ? dir_touch($v, $mtime, $atime) : touch($v, $mtime, $atime);
    }
    return true;
}

/**
 * Dir tree
 * 
 * @global int $id
 * @param string $dir
 * @param integer $parentid
 * @param array $dirs
 * @return array
 */
function dir_tree($dir, $parentid = 0, $dirs = array()) {
    global $id;
    if ($parentid == 0) {
        $id = 0;
    }
    $list = glob($dir . '*');
    foreach ($list as $v) {
        if (is_dir($v)) {
            $id++;
            $dirs[$id] = array('id' => $id, 'parentid' => $parentid, 'name' => basename($v), 'dir' => $v . '/');
            $dirs = dir_tree($v . '/', $id, $dirs);
        }
    }
    return $dirs;
}

/**
 *  Delete dir
 * 
 * @param type $dir
 * @return boolean
 */
function dir_delete($dir) {
    $dir = dir_path($dir);
    if (!is_dir($dir)) {
        return false;
    }
    $list = glob($dir . '*');
    foreach ($list as $v) {
        is_dir($v) ? dir_delete($v) : @unlink($v);
    }
    return @rmdir($dir);
}

?>