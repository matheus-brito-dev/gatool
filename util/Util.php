<?php
/*
 * 
 * Copyright 2017 Matheus Brito de Oliveira - matheusbrito_si@hotmail.com.
    This file is part of GATOOL - Genome Assembly Tool.

    GATOOL is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    GATOOL is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Foobar.  If not, see <http://www.gnu.org/licenses/>.
 * 
 * 
 * 
 */
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Util
 *
 * @author root
 */
class Util {
    
    
    
    public static function getFileName($file){
        
        $r = explode('.', $file);
        return $r[0];
        
    }


    
    
    public static function cleanDirectory($dir) {
        
	if (!file_exists($dir)) return true;
	if (!is_dir($dir)) return unlink($dir);
	foreach (scandir($dir) as $item) {
		if (($item == '.') or ($item == '..')) continue;
		if (!Util::cleanDirectory($dir . DIRECTORY_SEPARATOR . $item)) return false;
	}
	return rmdir($dir);
}


public static function changeOwnerGroup($u,$g,$dir){
        $testeChown = "sudo chown -R " . $u . ":" . $g  . " " . $dir;
        echo exec($testeChown,$outTesteChown);
       
    
}
    
    public static function deleteFile($file){
        
        unlink($file);
    }
    
    
    public static function makeDir($path){
        mkdir($path,0777,true);
    }
    
    public static function countContigs($path){
       
        $q = "grep -c '>' " . $path . "/contigs.fa";
        exec($q,$sq,$eq);
        return $sq;
    
    }
    
}
