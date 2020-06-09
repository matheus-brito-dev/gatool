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
 * Variáveis de sessão criadas aqui na página operacao.php
 * $_SESSION['nameExt'] -> guarda somente o nome do arquivo enviado sem a extensao
 * $_SESSION['report_fastqc'] -> um vetor de sessão para guardar todos 
 * os relatórios gerados pelo fastqc
 *  */
ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);

session_start(); //inicio da sessao
sleep(1);// dormir 1 milisegundo
include 'util/Util.php';
//Arquivo passado por js p.ex: exemplo1.fastq
//$_SESSION['file_name'] = $_GET['arquivo'];
   
   //Arquivo cortado p.ex: exemplo1
$_SESSION['nameExt'] = Util::getFileName($_SESSION['fileName']);

//var_dump($_SESSION['file_name']);
class Operacao
{
    
    const ANALYSIS_APP = '/usr/local/bin/fastqc';
    const OUTPUT_FILE ='-o /var/www/html/gatool/reports';
    const INPUT_FILE = '/var/www/html/gatool/uploads/';
    
    
    public function opAnalysis1()
    {
        //echo "Analyzing...";    
    }

     public function opAnalysis2()

    {
        //echo "Building report...";
    }

     public function opAnalysis3()
    {
        
        $cmd_fastqc = sprintf('%s %s %s%s', self::ANALYSIS_APP,  self::OUTPUT_FILE, self::INPUT_FILE,$_SESSION['fileName']. " 2>&1");
        exec($cmd_fastqc,$out_fastqc,$exit_fastqc);
        //var_dump($cmd_fastqc);
        
    }

    public function opAnalysis4()

    {
        if(file_exists("reports/" . $_SESSION['nameExt'] . "_fastqc.zip")){
	 unlink("reports/" . $_SESSION['nameExt'] . "_fastqc.zip");
      //echo "Done...";
        }
      // $arr = array('nome'=>$_SESSION['file_name'],'file'=>$_SESSION['nameExt']);
      if(!isset($_SESSION['report_fastqc'])) {
        $_SESSION['report_fastqc'] = array();
      }
      array_push($_SESSION["report_fastqc"], "reports/". $_SESSION['nameExt'] ."_fastqc.html");
	    echo end($_SESSION['report_fastqc']);
      // echo "<a href='fastx.php?" . http_build_query($arr). "'>Trimmer your file</a>";
       
           }

 

}


$operacao = $_GET['operacao'];
$op = new Operacao();
$op->{$operacao}();


