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
 * Variáveis de sessão criadas aqui na página newAnalysis.php
 * $_SESSION['newNameFileExt'] -> guarda o novo nome do arquivo após o 
 * trimmer e o filter sem a extensão para refazer a análise
 *  
 */
session_start(); // inicio da sessao 
require_once('util/Util.php');
ini_set('display_errors', true);
error_reporting(E_ALL);
sleep(1);//dormir 1 milisegundo

//Arquivo passado por js p.ex: exemplo1.fastq
//$_SESSION['file_new_name'] = $_GET['arquivo'];
   
  
 try{
       
            $opt = $_GET['opt'];
            $_SESSION['optTipoFastx'] = $opt;
    
     
        
    }catch(Exception $err){
        ehco ("Exception " . $err->getMessage(), "\n");
    }



class Operacao
{
    const ANALYSIS_APP = '/usr/local/bin/fastqc';
    const OUTPUT_FILE ='-o /var/www/html/gatool/reports';
    const INPUT_FILE = '/var/www/html/gatool/trimFiles/';
    
 
    public function newAnalysisFilter1()
    {
        //echo "Analyzing...";    
    }

     public function newAnalysisFilter2()

    {
        //echo "Building report...";
    }

     public function newAnalysisFilter3($opt)
    {
         
        
        if($_SESSION['optTipoFastx']=='0'){
           $_SESSION['newNameFileExt'] = Util::getFileName($_SESSION['newNameFile']);
             $cmd_fastqc = sprintf('%s %s %s%s', self::ANALYSIS_APP,  self::OUTPUT_FILE, self::INPUT_FILE,$_SESSION['newNameFile']. " 2>&1");
             exec($cmd_fastqc,$out_fastqc,$exit_fastqc);
             //var_dump($cmd_fastqc);
             
             
             unlink("reports/" . $_SESSION['newNameFileExt'] . "_fastqc.zip");

             if(!isset($_SESSION['report_fastqc'])) {
             $_SESSION['report_fastqc'] = array();
            }
             array_push($_SESSION["report_fastqc"], "reports/". $_SESSION['newNameFileExt'] ."_fastqc.html");
	     echo end($_SESSION['report_fastqc']);
  
             
         }else if($_SESSION['optTipoFastx']=='1'){
             $_SESSION['saidaTrimmerName'] = Util::getFileName($_SESSION['saidaTrimmer']);
             $cmd_fastqc = sprintf('%s %s %s%s', self::ANALYSIS_APP,  self::OUTPUT_FILE, self::INPUT_FILE,$_SESSION['saidaTrimmer']. " 2>&1");
             exec($cmd_fastqc,$out_fastqc,$exit_fastqc);
             //var_dump($cmd_fastqc);
            
            
             
             unlink("reports/" . $_SESSION['saidaTrimmerName'] . "_fastqc.zip");

             if(!isset($_SESSION['report_fastqc'])) {
             $_SESSION['report_fastqc'] = array();
            }
             array_push($_SESSION["report_fastqc"], "reports/". $_SESSION['saidaTrimmerName'] ."_fastqc.html");
	     echo end($_SESSION['report_fastqc']);
  
         }else{
             $_SESSION['saidaFilterName'] = Util::getFileName($_SESSION['saidaFilter']);
             $cmd_fastqc = sprintf('%s %s %s%s', self::ANALYSIS_APP,  self::OUTPUT_FILE, self::INPUT_FILE,$_SESSION['saidaFilter']. " 2>&1");
             exec($cmd_fastqc,$out_fastqc,$exit_fastqc);
             //var_dump($cmd_fastqc);
             
             if(file_exists("reports/" . $_SESSION['saidaFilterName'] . "_fastqc.zip")){
             unlink("reports/" . $_SESSION['saidaFilterName'] . "_fastqc.zip");
             }

             if(!isset($_SESSION['report_fastqc'])) {
             $_SESSION['report_fastqc'] = array();
            }
             array_push($_SESSION["report_fastqc"], "reports/". $_SESSION['saidaFilterName'] ."_fastqc.html");
	     echo end($_SESSION['report_fastqc']);
  
         }

         

    }

    public function newAnalysisFilter4()

    {
             
       
           }

 

}

$operacao = $_GET['operacao'];
$op = new Operacao();
$op->{$operacao}($opt);

?>

