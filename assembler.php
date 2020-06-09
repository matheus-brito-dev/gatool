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

sleep(1);//dormir 1 milisegundo
include 'util/Util.php';
//Arquivo passado por js p.ex: exemplo1.fastq
//$_SESSION['file_new_name'] = $_GET['arquivo'];
   
   //Arquivo cortado p.ex: exemplo1
//$_SESSION['newNameFileExt'] = capturaNome($_SESSION['newNameFile']);

//var_dump($_SESSION['newNameFile']);


    try{
        
        $fileAssembly =   $_GET['fa'];
        $cutoffAssembly = $_GET['co'];
        
        if($cutoffAssembly==0){
            $cutoffAssembly = 'auto';
        }
        
        $nameFileUpload = Util::getFileName($_SESSION['fileName']);
        $outAssembly = $_GET['outA'] . $nameFileUpload; // mexer aqui o nome tem que ser o mesmo
      
        $fast = $_GET['fastA'];
        
        $sc =   $_GET['sc'];
        
    }catch(Exception $err){
        echo "Exception " . $err->getMessage(), "\n";
        
    }


class Operacao
{
    
    
    
     /************SPAdes Assembler constants**************/
        
        const APP_ASSEMBLY = '/usr/local/bin/spades.py';
        const PARAMSK1 = '-k 21,33,55,77,99,127';
        const PARAMSK2 = '-k 21,33,55,77';
        const SCPARAMSK3 = '-k 21,33,55';
        const CUTOFF = '--cov-cutoff';
        const ION = '--iontorrent';
        const ONLY_ASSEMBLY = '--only-assembler';
        const INPUT_ASSEMBLY_ORIG = '-s /var/www/html/gatool/uploads/';
        const INPUT_ASSEMBLY_MOD = '-s /var/www/html/gatool/trimFiles/';
        const OUTPUT_ASSEMBLY = '-o /var/www/html/gatool/assemblers/';
        const SC_MODE = '--sc';
    
    /***********************QUAST CONSTANTS*************************************************/
        
        const APP_QUAST = '/usr/local/bin/quast'; 
        const OUTPUT_QUAST = '-o /var/www/html/gatool/assemblerReports';
        const INPUT_QUAST1 = '/var/www/html/gatool/assemblers/';
        const INPUT_QUAST2 = '/scaffolds.fasta';
        const FAST = '--no-plots --no-html';
        const FILE = '/var/www/html/gatool/assemblerReports';
   
 /***************************************************************************/
    public function runAssembler1()
    {
 
    }

     public function runAssembler2()

    {
        
    }

     public function runAssembler3($fa,$co,$oa,$f,$sc)
    {
         switch ($fa){
             
         
             case 1: //caso arquivo original
                 
                 if ($f == 'true' && $sc == 'true') {
                    
                    
                    try{
                            $cmd_assembler = sprintf('%s %s %s %s %s %s %s %s%s %s%s', self::APP_ASSEMBLY,  self::SC_MODE, self::ONLY_ASSEMBLY, self::SCPARAMSK3, self::CUTOFF, $co, self::ION, self::INPUT_ASSEMBLY_ORIG, $_SESSION['fileName'], self::OUTPUT_ASSEMBLY, $oa . " 2>&1");
                            exec($cmd_assembler, $out_assembler, $exit_assembler);
                            //var_dump($cmd_assembler);
                            
                            
                            $dirQuast = self::OUTPUT_QUAST . "/" . $oa;
                            //var_dump($dirQuast);
                            $cmd_quast = sprintf('%s %s %s', self::APP_QUAST, $dirQuast, self::INPUT_QUAST1 . $oa . self::INPUT_QUAST2);
                            exec($cmd_quast, $out_quast, $exit_quast);
                            
                            $_SESSION['scaffoldPath'] = self::INPUT_QUAST1 . $oa . self::INPUT_QUAST2;
                            //var_dump($cmd_quast);
                           
                            if (!isset($_SESSION['report_fastqc'])) {
                            $_SESSION['report_fastqc'] = array();
                                }

                            array_push($_SESSION["report_fastqc"], 'assemblerReports/' . $oa  . '/report.html');
                            echo end($_SESSION['report_fastqc']);
                    }catch(Exception $err){
                                echo "Exception " . $err->getMessage(), "\n";
                            }

                } else if ($f == 'true' && $sc == 'false') {

                            
                         try{


                            $cmd_assembler = sprintf('%s %s %s %s %s %s %s%s %s%s', self::APP_ASSEMBLY, self::ONLY_ASSEMBLY, self::PARAMSK2, self::CUTOFF, $co, self::ION, self::INPUT_ASSEMBLY_ORIG, $_SESSION['fileName'], self::OUTPUT_ASSEMBLY, $oa . " 2>&1");
                            exec($cmd_assembler, $out_assembler, $exit_assembler);

                             $dirQuast = self::OUTPUT_QUAST . "/" . $oa;
                            //var_dump($dirQuast);
                            $cmd_quast = sprintf('%s %s %s', self::APP_QUAST, $dirQuast, self::INPUT_QUAST1 . $oa . self::INPUT_QUAST2);
                            exec($cmd_quast, $out_quast, $exit_quast);
                            
                            $_SESSION['scaffoldPath'] = self::INPUT_QUAST1 . $oa . self::INPUT_QUAST2;
                            
                            //var_dump($cmd_quast);
                           
                            if (!isset($_SESSION['report_fastqc'])) {
                            $_SESSION['report_fastqc'] = array();
                                }

                            array_push($_SESSION["report_fastqc"], 'assemblerReports/' . $oa  . '/report.html');
                            echo end($_SESSION['report_fastqc']);
                            
                        } catch (Exception $err) {
                            echo "Exception " . $err->getMessage(), "\n";
                            }
                            
                        }else if ($f == 'false' && $sc == 'true') {
                                 
                            try{

                            $cmd_assembler = sprintf('%s %s %s %s %s %s %s%s %s%s', self::APP_ASSEMBLY, self::SC_MODE, self::SCPARAMSK3, self::CUTOFF, $co, self::ION, self::INPUT_ASSEMBLY_ORIG, $_SESSION['fileName'], self::OUTPUT_ASSEMBLY, $oa . " 2>&1");
                            exec($cmd_assembler, $out_assembler, $exit_assembler);

                             $dirQuast = self::OUTPUT_QUAST . "/" . $oa;
                            //var_dump($dirQuast);
                            $cmd_quast = sprintf('%s %s %s', self::APP_QUAST, $dirQuast, self::INPUT_QUAST1 . $oa . self::INPUT_QUAST2);
                            exec($cmd_quast, $out_quast, $exit_quast);
                            
                            $_SESSION['scaffoldPath'] = self::INPUT_QUAST1 . $oa . self::INPUT_QUAST2;
                            
                            //var_dump($cmd_quast);
                           
                            if (!isset($_SESSION['report_fastqc'])) {
                            $_SESSION['report_fastqc'] = array();
                                }

                            array_push($_SESSION["report_fastqc"], 'assemblerReports/' . $oa  . '/report.html');
                            echo end($_SESSION['report_fastqc']);
                          

                            } catch(Exception $err) {
                                echo "Exception " . $err->getMessage(), "\n";
                            }


                    
                } else {
                      
                    
                        try {

                            $cmd_assembler = sprintf('%s %s %s %s %s %s%s %s%s', self::APP_ASSEMBLY, self::PARAMSK1, self::CUTOFF, $co, self::ION, self::INPUT_ASSEMBLY_ORIG, $_SESSION['fileName'], self::OUTPUT_ASSEMBLY, $oa . " 2>&1");
                            exec($cmd_assembler, $out_assembler, $exit_assembler);

                             $dirQuast = self::OUTPUT_QUAST . "/" . $oa;
                            //var_dump($dirQuast);
                            $cmd_quast = sprintf('%s %s %s', self::APP_QUAST, $dirQuast, self::INPUT_QUAST1 . $oa . self::INPUT_QUAST2);
                            exec($cmd_quast, $out_quast, $exit_quast);
                            
                            $_SESSION['scaffoldPath'] = self::INPUT_QUAST1 . $oa . self::INPUT_QUAST2;
                            
                            //var_dump($cmd_quast);
                           
                            if (!isset($_SESSION['report_fastqc'])) {
                            $_SESSION['report_fastqc'] = array();
                                }

                            array_push($_SESSION["report_fastqc"], 'assemblerReports/' . $oa  . '/report.html');
                            echo end($_SESSION['report_fastqc']);

                        } catch (Exception $err) {
                            echo "Exception " . $err->getMessage(), "\n";
                        }
                }
                break;
             
         
                
           
         
             case 2: // caso arquivo trimmado e filtrado
                 if($_SESSION['optTipoFastx'] == '0'){
                        
                     if ($f == 'true' && $sc == 'true') {
                     //echo 'teste 2 true';
                    
                    try {
                        $cmd_assembler = sprintf('%s %s %s %s %s %s %s %s%s %s%s', self::APP_ASSEMBLY, self::SC_MODE, self::ONLY_ASSEMBLY, self::SCPARAMSK3, self::CUTOFF, $co, self::ION, self::INPUT_ASSEMBLY_MOD, $_SESSION['newNameFile'], self::OUTPUT_ASSEMBLY, $oa . " 2>&1");
                        exec($cmd_assembler, $out_assembler, $exit_assembler);
                        
                        
                         $dirQuast = self::OUTPUT_QUAST . "/" . $oa;
                            //var_dump($dirQuast);
                            $cmd_quast = sprintf('%s %s %s', self::APP_QUAST, $dirQuast, self::INPUT_QUAST1 . $oa . self::INPUT_QUAST2);
                            exec($cmd_quast, $out_quast, $exit_quast);
                            
                            $_SESSION['scaffoldPath'] = self::INPUT_QUAST1 . $oa . self::INPUT_QUAST2;
                            
                            //var_dump($cmd_quast);
                           
                            if (!isset($_SESSION['report_fastqc'])) {
                            $_SESSION['report_fastqc'] = array();
                                }

                            array_push($_SESSION["report_fastqc"], 'assemblerReports/' . $oa  . '/report.html');
                            echo end($_SESSION['report_fastqc']);
                        
                    } catch (Exception $err) {
                        echo "Exception " . $err->getMessage(), "\n";
                    }
                } else if ($f == 'true' && $sc == 'false') {
                        //echo 'teste 1 true 1 false';
                       
                        
                try{
                     
                        
                        $cmd_assembler = sprintf('%s %s %s %s %s %s %s%s %s%s', self::APP_ASSEMBLY, self::ONLY_ASSEMBLY, self::PARAMSK2, self::CUTOFF, $co, self::ION, self::INPUT_ASSEMBLY_MOD, $_SESSION['newNameFile'], self::OUTPUT_ASSEMBLY, $oa . " 2>&1");
                        exec($cmd_assembler, $out_assembler, $exit_assembler);

                         $dirQuast = self::OUTPUT_QUAST . "/" . $oa;
                            //var_dump($dirQuast);
                            $cmd_quast = sprintf('%s %s %s', self::APP_QUAST, $dirQuast, self::INPUT_QUAST1 . $oa . self::INPUT_QUAST2);
                            exec($cmd_quast, $out_quast, $exit_quast);
                            
                            $_SESSION['scaffoldPath'] = self::INPUT_QUAST1 . $oa . self::INPUT_QUAST2;
                            
                            //var_dump($cmd_quast);
                           
                            if (!isset($_SESSION['report_fastqc'])) {
                            $_SESSION['report_fastqc'] = array();
                                }

                            array_push($_SESSION["report_fastqc"], 'assemblerReports/' . $oa  . '/report.html');
                            echo end($_SESSION['report_fastqc']);
                        
                    } catch (Exception $err) {
                        echo "Exception " . $err->getMessage(), "\n";
                    }
                }
                        
                 else if ($f == 'false' && $sc == 'true') {
                     //echo 'teste 1 false 1 true';
                     
                      try {

                        $cmd_assembler = sprintf('%s %s %s %s %s %s %s%s %s%s', self::APP_ASSEMBLY, self::SC_MODE, self::SCPARAMSK3, self::CUTOFF, $co, self::ION, self::INPUT_ASSEMBLY_MOD, $_SESSION['newNameFile'], self::OUTPUT_ASSEMBLY, $oa . " 2>&1");
                        exec($cmd_assembler, $out_assembler, $exit_assembler);

                         $dirQuast = self::OUTPUT_QUAST . "/" . $oa;
                            //var_dump($dirQuast);
                            $cmd_quast = sprintf('%s %s %s', self::APP_QUAST, $dirQuast, self::INPUT_QUAST1 . $oa . self::INPUT_QUAST2);
                            exec($cmd_quast, $out_quast, $exit_quast);
                            
                            $_SESSION['scaffoldPath'] = self::INPUT_QUAST1 . $oa . self::INPUT_QUAST2;
                            
                            //var_dump($cmd_quast);
                           
                            if (!isset($_SESSION['report_fastqc'])) {
                            $_SESSION['report_fastqc'] = array();
                                }

                            array_push($_SESSION["report_fastqc"], 'assemblerReports/' . $oa  . '/report.html');
                            echo end($_SESSION['report_fastqc']);
                        
                    } catch (Exception $err) {
                        echo "Exception " . $err->getMessage(), "\n";
                    }

                } else {
                 // echo 'teste 1 false 1 false';
                    try {
                        $cmd_assembler = sprintf('%s %s %s %s %s %s%s %s%s', self::APP_ASSEMBLY, self::PARAMSK1, self::CUTOFF, $co, self::ION, self::INPUT_ASSEMBLY_MOD, $_SESSION['newNameFile'], self::OUTPUT_ASSEMBLY, $oa . " 2>&1");
                        exec($cmd_assembler, $out_assembler, $exit_assembler);

                         $dirQuast = self::OUTPUT_QUAST . "/" . $oa;
                            //var_dump($dirQuast);
                            $cmd_quast = sprintf('%s %s %s', self::APP_QUAST, $dirQuast, self::INPUT_QUAST1 . $oa . self::INPUT_QUAST2);
                            exec($cmd_quast, $out_quast, $exit_quast);
                            
                            $_SESSION['scaffoldPath'] = self::INPUT_QUAST1 . $oa . self::INPUT_QUAST2;
                            
                            //var_dump($cmd_quast);
                           
                            if (!isset($_SESSION['report_fastqc'])) {
                            $_SESSION['report_fastqc'] = array();
                                }

                            array_push($_SESSION["report_fastqc"], 'assemblerReports/' . $oa  . '/report.html');
                            echo end($_SESSION['report_fastqc']);
                        
                    } catch (Exception $err) {
                        echo "Exception " . $err->getMessage(), "\n";
                    }
                }
                     
                 }else if($_SESSION['optTipoFastx']=='1'){ //caso seja apenas trimmed
                     if ($f == 'true' && $sc == 'true') {
                     //echo 'teste 2 true';
                    
                    try {
                        $cmd_assembler = sprintf('%s %s %s %s %s %s %s %s%s %s%s', self::APP_ASSEMBLY, self::SC_MODE, self::ONLY_ASSEMBLY, self::SCPARAMSK3, self::CUTOFF, $co, self::ION, self::INPUT_ASSEMBLY_MOD, $_SESSION['saidaTrimmer'], self::OUTPUT_ASSEMBLY, $oa . " 2>&1");
                        exec($cmd_assembler, $out_assembler, $exit_assembler);
                        
                        
                         $dirQuast = self::OUTPUT_QUAST . "/" . $oa;
                            //var_dump($dirQuast);
                            $cmd_quast = sprintf('%s %s %s', self::APP_QUAST, $dirQuast, self::INPUT_QUAST1 . $oa . self::INPUT_QUAST2);
                            exec($cmd_quast, $out_quast, $exit_quast);
                            
                            $_SESSION['scaffoldPath'] = self::INPUT_QUAST1 . $oa . self::INPUT_QUAST2;
                            
                            //var_dump($cmd_quast);
                           
                            if (!isset($_SESSION['report_fastqc'])) {
                            $_SESSION['report_fastqc'] = array();
                                }

                            array_push($_SESSION["report_fastqc"], 'assemblerReports/' . $oa  . '/report.html');
                            echo end($_SESSION['report_fastqc']);
                        
                    } catch (Exception $err) {
                        echo "Exception " . $err->getMessage(), "\n";
                    }
                } else if ($f == 'true' && $sc == 'false') {
                        //echo 'teste 1 true 1 false';
                       
                        
                try{
                     
                        
                        $cmd_assembler = sprintf('%s %s %s %s %s %s %s%s %s%s', self::APP_ASSEMBLY, self::ONLY_ASSEMBLY, self::PARAMSK2, self::CUTOFF, $co, self::ION, self::INPUT_ASSEMBLY_MOD, $_SESSION['saidaTrimmer'], self::OUTPUT_ASSEMBLY, $oa . " 2>&1");
                        exec($cmd_assembler, $out_assembler, $exit_assembler);

                         $dirQuast = self::OUTPUT_QUAST . "/" . $oa;
                            //var_dump($dirQuast);
                            $cmd_quast = sprintf('%s %s %s', self::APP_QUAST, $dirQuast, self::INPUT_QUAST1 . $oa . self::INPUT_QUAST2);
                            exec($cmd_quast, $out_quast, $exit_quast);
                            
                            $_SESSION['scaffoldPath'] = self::INPUT_QUAST1 . $oa . self::INPUT_QUAST2;
                            
                            //var_dump($cmd_quast);
                           
                            if (!isset($_SESSION['report_fastqc'])) {
                            $_SESSION['report_fastqc'] = array();
                                }

                            array_push($_SESSION["report_fastqc"], 'assemblerReports/' . $oa  . '/report.html');
                            echo end($_SESSION['report_fastqc']);
                        
                    } catch (Exception $err) {
                        echo "Exception " . $err->getMessage(), "\n";
                    }
                }
                        
                 else if ($f == 'false' && $sc == 'true') {
                     //echo 'teste 1 false 1 true';
                     
                      try {

                        $cmd_assembler = sprintf('%s %s %s %s %s %s %s%s %s%s', self::APP_ASSEMBLY, self::SC_MODE, self::SCPARAMSK3, self::CUTOFF, $co, self::ION, self::INPUT_ASSEMBLY_MOD, $_SESSION['saidaTrimmer'], self::OUTPUT_ASSEMBLY, $oa . " 2>&1");
                        exec($cmd_assembler, $out_assembler, $exit_assembler);

                         $dirQuast = self::OUTPUT_QUAST . "/" . $oa;
                            //var_dump($dirQuast);
                            $cmd_quast = sprintf('%s %s %s', self::APP_QUAST, $dirQuast, self::INPUT_QUAST1 . $oa . self::INPUT_QUAST2);
                            exec($cmd_quast, $out_quast, $exit_quast);
                            
                            $_SESSION['scaffoldPath'] = self::INPUT_QUAST1 . $oa . self::INPUT_QUAST2;
                            
                            //var_dump($cmd_quast);
                           
                            if (!isset($_SESSION['report_fastqc'])) {
                            $_SESSION['report_fastqc'] = array();
                                }

                            array_push($_SESSION["report_fastqc"], 'assemblerReports/' . $oa  . '/report.html');
                            echo end($_SESSION['report_fastqc']);
                        
                    } catch (Exception $err) {
                        echo "Exception " . $err->getMessage(), "\n";
                    }

                } else {
                 // echo 'teste 1 false 1 false';
                    try {
                        $cmd_assembler = sprintf('%s %s %s %s %s %s%s %s%s', self::APP_ASSEMBLY, self::PARAMSK1, self::CUTOFF, $co, self::ION, self::INPUT_ASSEMBLY_MOD, $_SESSION['saidaTrimmer'], self::OUTPUT_ASSEMBLY, $oa . " 2>&1");
                        exec($cmd_assembler, $out_assembler, $exit_assembler);

                         $dirQuast = self::OUTPUT_QUAST . "/" . $oa;
                            //var_dump($dirQuast);
                            $cmd_quast = sprintf('%s %s %s', self::APP_QUAST, $dirQuast, self::INPUT_QUAST1 . $oa . self::INPUT_QUAST2);
                            exec($cmd_quast, $out_quast, $exit_quast);
                            
                            $_SESSION['scaffoldPath'] = self::INPUT_QUAST1 . $oa . self::INPUT_QUAST2;
                            
                            //var_dump($cmd_quast);
                           
                            if (!isset($_SESSION['report_fastqc'])) {
                            $_SESSION['report_fastqc'] = array();
                                }

                            array_push($_SESSION["report_fastqc"], 'assemblerReports/' . $oa  . '/report.html');
                            echo end($_SESSION['report_fastqc']);
                        
                    } catch (Exception $err) {
                        echo "Exception " . $err->getMessage(), "\n";
                    }
                }
                     
                 }else{ //caso seja apenas filter
                     
                     if ($f == 'true' && $sc == 'true') {
                     //echo 'teste 2 true';
                    
                    try {
                        $cmd_assembler = sprintf('%s %s %s %s %s %s %s %s%s %s%s', self::APP_ASSEMBLY, self::SC_MODE, self::ONLY_ASSEMBLY, self::SCPARAMSK3, self::CUTOFF, $co, self::ION, self::INPUT_ASSEMBLY_MOD, $_SESSION['saidaFilter'], self::OUTPUT_ASSEMBLY, $oa . " 2>&1");
                        exec($cmd_assembler, $out_assembler, $exit_assembler);
                        
                        
                         $dirQuast = self::OUTPUT_QUAST . "/" . $oa;
                            //var_dump($dirQuast);
                            $cmd_quast = sprintf('%s %s %s', self::APP_QUAST, $dirQuast, self::INPUT_QUAST1 . $oa . self::INPUT_QUAST2);
                            exec($cmd_quast, $out_quast, $exit_quast);
                            
                            $_SESSION['scaffoldPath'] = self::INPUT_QUAST1 . $oa . self::INPUT_QUAST2;
                            
                            //var_dump($cmd_quast);
                           
                            if (!isset($_SESSION['report_fastqc'])) {
                            $_SESSION['report_fastqc'] = array();
                                }

                            array_push($_SESSION["report_fastqc"], 'assemblerReports/' . $oa  . '/report.html');
                            echo end($_SESSION['report_fastqc']);
                        
                    } catch (Exception $err) {
                        echo "Exception " . $err->getMessage(), "\n";
                    }
                } else if ($f == 'true' && $sc == 'false') {
                        //echo 'teste 1 true 1 false';
                       
                        
                try{
                     
                        
                        $cmd_assembler = sprintf('%s %s %s %s %s %s %s%s %s%s', self::APP_ASSEMBLY, self::ONLY_ASSEMBLY, self::PARAMSK2, self::CUTOFF, $co, self::ION, self::INPUT_ASSEMBLY_MOD, $_SESSION['saidaFilter'], self::OUTPUT_ASSEMBLY, $oa . " 2>&1");
                        exec($cmd_assembler, $out_assembler, $exit_assembler);

                         $dirQuast = self::OUTPUT_QUAST . "/" . $oa;
                            //var_dump($dirQuast);
                            $cmd_quast = sprintf('%s %s %s', self::APP_QUAST, $dirQuast, self::INPUT_QUAST1 . $oa . self::INPUT_QUAST2);
                            exec($cmd_quast, $out_quast, $exit_quast);
                            
                            $_SESSION['scaffoldPath'] = self::INPUT_QUAST1 . $oa . self::INPUT_QUAST2;
                            
                            //var_dump($cmd_quast);
                           
                            if (!isset($_SESSION['report_fastqc'])) {
                            $_SESSION['report_fastqc'] = array();
                                }

                            array_push($_SESSION["report_fastqc"], 'assemblerReports/' . $oa  . '/report.html');
                            echo end($_SESSION['report_fastqc']);
                        
                    } catch (Exception $err) {
                        echo "Exception " . $err->getMessage(), "\n";
                    }
                }
                        
                 else if ($f == 'false' && $sc == 'true') {
                     //echo 'teste 1 false 1 true';
                     
                      try {

                        $cmd_assembler = sprintf('%s %s %s %s %s %s %s%s %s%s', self::APP_ASSEMBLY, self::SC_MODE, self::SCPARAMSK3, self::CUTOFF, $co, self::ION, self::INPUT_ASSEMBLY_MOD, $_SESSION['saidaFilter'], self::OUTPUT_ASSEMBLY, $oa . " 2>&1");
                        exec($cmd_assembler, $out_assembler, $exit_assembler);

                         $dirQuast = self::OUTPUT_QUAST . "/" . $oa;
                            //var_dump($dirQuast);
                            $cmd_quast = sprintf('%s %s %s', self::APP_QUAST, $dirQuast, self::INPUT_QUAST1 . $oa . self::INPUT_QUAST2);
                            exec($cmd_quast, $out_quast, $exit_quast);
                            
                            $_SESSION['scaffoldPath'] = self::INPUT_QUAST1 . $oa . self::INPUT_QUAST2;
                            
                            //var_dump($cmd_quast);
                           
                            if (!isset($_SESSION['report_fastqc'])) {
                            $_SESSION['report_fastqc'] = array();
                                }

                            array_push($_SESSION["report_fastqc"], 'assemblerReports/' . $oa  . '/report.html');
                            echo end($_SESSION['report_fastqc']);
                        
                    } catch (Exception $err) {
                        echo "Exception " . $err->getMessage(), "\n";
                    }

                } else {
                 // echo 'teste 1 false 1 false';
                    try {
                        $cmd_assembler = sprintf('%s %s %s %s %s %s%s %s%s', self::APP_ASSEMBLY, self::PARAMSK1, self::CUTOFF, $co, self::ION, self::INPUT_ASSEMBLY_MOD, $_SESSION['saidaFilter'], self::OUTPUT_ASSEMBLY, $oa . " 2>&1");
                        exec($cmd_assembler, $out_assembler, $exit_assembler);

                         $dirQuast = self::OUTPUT_QUAST . "/" . $oa;
                            //var_dump($dirQuast);
                            $cmd_quast = sprintf('%s %s %s', self::APP_QUAST, $dirQuast, self::INPUT_QUAST1 . $oa . self::INPUT_QUAST2);
                            exec($cmd_quast, $out_quast, $exit_quast);
                            
                            $_SESSION['scaffoldPath'] = self::INPUT_QUAST1 . $oa . self::INPUT_QUAST2;
                            
                            //var_dump($cmd_quast);
                           
                            if (!isset($_SESSION['report_fastqc'])) {
                            $_SESSION['report_fastqc'] = array();
                                }

                            array_push($_SESSION["report_fastqc"], 'assemblerReports/' . $oa  . '/report.html');
                            echo end($_SESSION['report_fastqc']);
                        
                    } catch (Exception $err) {
                        echo "Exception " . $err->getMessage(), "\n";
                    }
                }}
                 
                break;
    }
   
    }
    
    public function runAssembler4()

    {
        
        
        echo $_SESSION['scaffoldPath'];
        
           }

 

}

$operacao = $_GET['operacao'];
$op = new Operacao();
$op->{$operacao}($fileAssembly,$cutoffAssembly,$outAssembly,$fast,$sc);