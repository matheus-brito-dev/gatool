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
 * Description of assembler2
 *
 * @author root
 */
ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);
session_start();
sleep(1);
include "util/Util.php";    

//Recuperando os campos do form

try{
        $fileAssembly =   $_GET['fa'];
        $cutoffAssembly = $_GET['co'];
        
        if($cutoffAssembly==0){
            $cutoffAssembly = 'auto';
        }
       
        $nameFileUpload = Util::getFileName($_SESSION['fileName']);
        $outAssembly = $_GET['outA'] . $nameFileUpload; // mexer aqui o nome tem que ser o mesmo
        
        $m = $_GET['m'];
        
        $k = explode(",", $_GET['k']);
        
       
        
      
}  
catch (Exception $err){echo "Exception " . $err->getMessage(), "\n";}


//Inicio da classe

class assembler2 {
    
    /************Velvet Assembler constants**************/
    const VELEVETH_APP_ASSEMBLY = '/usr/bin/velveth';
    const VELEVETG_APP_ASSEMBLY = '/usr/bin/velvetg';
    public $kvalues = array();
    const VELVET_CUTOFF = '-cov_cutoff';
    const VELVET_FILE = '-fastq';
    const VELEVET_TYPE = '-short';
    const VELVET_INPUT_ASSEMBLY_ORIG = '/var/www/html/gatool/uploads/';
    const VELVET_INPUT_ASSEMBLY_MOD = '/var/www/html/gatool/trimFiles/';
    const VELVET_OUTPUT_ASSEMBLY = '/var/www/html/gatool/assemblers/';

    /*****************************************************/
    
    /***********************QUAST CONSTANTS*************************************************/
        
        const APP_QUAST = '/usr/local/bin/quast'; 
        const OUTPUT_QUAST = '-o /var/www/html/gatool/assemblerReports';
        const INPUT_QUAST1 = '/var/www/html/gatool/assemblers/';
        const INPUT_QUAST2 = '/contigs.fa';
        const FAST = '--no-plots --no-html';
        const FILE = '/var/www/html/gatool/assemblerReports';
        const MIN_CONTIGS = '-m '; // parametro que determina o numero minimo de contigs
        // sem esse parametro ele nao consegue gerar o relatorio do quast
        // para genomas que nao apresentem contigs com 500 bp minimo.
 /***************************************************************************/
    
        /*Inicio das funções para chamar o velvet e simular o loading*/
        public function velvet1() {
                
        }

        public function velvet2() {

        }

        public function velvet3($fa,$co,$oa,$m,$k) {
            $this->kvalues = $k;
          
           
            switch ($fa){ //testar o arquivo que irá ser montado
                
                case 1: //caso seja o arquivo original
                    try {
                        Util::makeDir(self::VELVET_OUTPUT_ASSEMBLY . $oa);
                        $i=0;
                      
                        while($i<count($this->kvalues)){
                        $cmd_assembler2 = sprintf('%s %s%s%s %s %s %s %s %s %s%s%s %s %s', self::VELEVETH_APP_ASSEMBLY, self::VELVET_OUTPUT_ASSEMBLY, 
                        $oa .'/',$this->kvalues[$i], $this->kvalues[$i],  self::VELVET_FILE, self::VELEVET_TYPE, self::VELVET_INPUT_ASSEMBLY_ORIG . $_SESSION['fileName'].";",
                        self::VELEVETG_APP_ASSEMBLY, self::VELVET_OUTPUT_ASSEMBLY,$oa.'/', $this->kvalues[$i]."/",  self::VELVET_CUTOFF,$co . " 2>&1");
                        //var_dump($cmd_assembler2);
                        exec($cmd_assembler2, $out_assembler2, $exit_assembler2);
                        $i++;
                        }
                        
                        
                        //testo se a montagem foi bem sucedida
                        if($exit_assembler2 == 0){
                             //entro em cada pasta e dou um grep no arquivo contigs.fa
                              //para comparar todos os valores e so gerar o relatorio do melhor 
                                $i=0;
                                $min=9999999;
                                $p0=0;
                                $p1=0;
                                
                                while($i<count($this->kvalues)){
                                    $nContigs[] = Util::countContigs(self::VELVET_OUTPUT_ASSEMBLY . $oa . "/" . $this->kvalues[$i]); 
                                    $i++;
                                }
                                
                                
                                for($i=0;$i<count($nContigs);$i++){
                                    $j=0;
                                        
                                    
                                    //print_r($nContigs[$i][$j]);
                                    if($nContigs[$i][$j] < $min){
                                        
                                        $min = $nContigs[$i][$j];
                                        $p0=$i;
                                       // $p1=$j;
                                   }
                                  
                                }
                                 
                               
                        }else 
                            echo "Error in the assembler process";
                        
                        $dirQuast = self::OUTPUT_QUAST . "/" . $oa;
                        //var_dump($dirQuast);
                       // echo "</br>";
                        $cmd_quast = sprintf('%s %s %s %s', self::APP_QUAST, self::MIN_CONTIGS . $m, $dirQuast, self::INPUT_QUAST1 . $oa .'/' . $this->kvalues[$p0] . self::INPUT_QUAST2);
                        exec($cmd_quast, $out_quast, $exit_quast);
                        //var_dump($cmd_quast);
                        $_SESSION['scaffoldPathVelvet'] = self::INPUT_QUAST1 . $oa .'/'. $this->kvalues[$p0] . self::INPUT_QUAST2;

                        //echo "</br>";
                        //var_dump($_SESSION['scaffoldPath']);
                        if (!isset($_SESSION['report_fastqc'])) {
                            $_SESSION['report_fastqc'] = array();
                        }

                        array_push($_SESSION["report_fastqc"], 'assemblerReports/' . $oa . '/report.html');
                        echo end($_SESSION['report_fastqc']);
                    } catch (Exception $err) {
                    echo "Exception " . $err->getMessage(), "\n";
                }
                break;
            
                case 2: // caso seja o arquivo modificado no trimmer
                        
                if($_SESSION['optTipoFastx'] == '0'){
                    
                    
                    try {
                Util::makeDir(self::VELVET_OUTPUT_ASSEMBLY . $oa);
                $i = 0;
                //$n = '57f28a61c12c3_t20_l50_q20_p80_trimming_filter.fastq';
                //$c = 'auto';
                //$s = 'teste/';
                while($i<count($this->kvalues)){
                $cmd_assembler2 = sprintf('%s %s%s%s %s %s %s %s %s %s%s%s %s %s', self::VELEVETH_APP_ASSEMBLY, self::VELVET_OUTPUT_ASSEMBLY,
                $oa .'/', $this->kvalues[$i], $this->kvalues[$i], self::VELVET_FILE, self::VELEVET_TYPE, self::VELVET_INPUT_ASSEMBLY_MOD . $_SESSION['newNameFile'].";",
                self::VELEVETG_APP_ASSEMBLY, self::VELVET_OUTPUT_ASSEMBLY, $oa.'/', $this->kvalues[$i]."/", self::VELVET_CUTOFF, $co . " 2>&1");
                //var_dump($cmd_assembler2);
                exec($cmd_assembler2, $out_assembler2, $exit_assembler2);
                $i++;
                }


                //testo se a montagem foi bem sucedida
                if($exit_assembler2 == 0){
                //entro em cada pasta e dou um grep no arquivo contigs.fa
                //para comparar todos os valores e so gerar o relatorio do melhor 
                $i = 0;
                $min = 9999999;
                $p0 = 0;
                $p1 = 0;

                while($i<count($this->kvalues)){
                $nContigs[] = Util::countContigs(self::VELVET_OUTPUT_ASSEMBLY . $oa . "/" . $this->kvalues[$i]);
                $i++;
                }


                for($i = 0;$i<count($nContigs);$i++){
                     $j = 0;
                     //var_dump($nContigs[$i][$j]);
                //print_r($nContigs[$i][$j]);
                if($nContigs[$i][$j] < $min){
                      
                    $min = $nContigs[$i][$j];
                    $p0 = $i;
                    // $p1=$j;
                    }

                }
                //var_dump($min);

                }else
                echo "Error in the assembler process";

                $dirQuast = self::OUTPUT_QUAST . "/" . $oa;
                //var_dump($dirQuast);
                // echo "</br>";
                $cmd_quast = sprintf('%s %s %s %s', self::APP_QUAST, self::MIN_CONTIGS . $m, $dirQuast, self::INPUT_QUAST1 . $oa .'/' . $this->kvalues[$p0] . self::INPUT_QUAST2);
                exec($cmd_quast, $out_quast, $exit_quast);
                //var_dump($cmd_quast);
                $_SESSION['scaffoldPathVelvet'] = self::INPUT_QUAST1 . $oa .'/'. $this->kvalues[$p0] . self::INPUT_QUAST2;

                //echo "</br>";
                //var_dump($_SESSION['scaffoldPath']);
                if (!isset($_SESSION['report_fastqc'])) {
                $_SESSION['report_fastqc'] = array();
                }

                array_push($_SESSION["report_fastqc"], 'assemblerReports/' . $oa . '/report.html');
                echo end($_SESSION['report_fastqc']);
                } catch (Exception $err) {
                echo "Exception " . $err->getMessage(), "\n";
                }
                    
                    
             
                }else if($_SESSION['optTipoFastx'] == '1'){
                    
                    
                    try {
                Util::makeDir(self::VELVET_OUTPUT_ASSEMBLY . $oa);
                $i = 0;
                //$n = '57f28a61c12c3_t20_l50_q20_p80_trimming_filter.fastq';
                //$c = 'auto';
                //$s = 'teste/';
                while($i<count($this->kvalues)){
                $cmd_assembler2 = sprintf('%s %s%s%s %s %s %s %s %s %s%s%s %s %s', self::VELEVETH_APP_ASSEMBLY, self::VELVET_OUTPUT_ASSEMBLY,
                $oa .'/', $this->kvalues[$i], $this->kvalues[$i], self::VELVET_FILE, self::VELEVET_TYPE, self::VELVET_INPUT_ASSEMBLY_MOD . $_SESSION['saidaTrimmer'].";",
                self::VELEVETG_APP_ASSEMBLY, self::VELVET_OUTPUT_ASSEMBLY, $oa.'/', $this->kvalues[$i]."/", self::VELVET_CUTOFF, $co . " 2>&1");
                //var_dump($cmd_assembler2);
                exec($cmd_assembler2, $out_assembler2, $exit_assembler2);
                $i++;
                }


                //testo se a montagem foi bem sucedida
                if($exit_assembler2 == 0){
                //entro em cada pasta e dou um grep no arquivo contigs.fa
                //para comparar todos os valores e so gerar o relatorio do melhor 
                $i = 0;
                $min = 9999999;
                $p0 = 0;
                $p1 = 0;

                while($i<count($this->kvalues)){
                $nContigs[] = Util::countContigs(self::VELVET_OUTPUT_ASSEMBLY . $oa . "/" . $this->kvalues[$i]);
                $i++;
                }


                for($i = 0;$i<count($nContigs);$i++){
                     $j = 0;
                     //var_dump($nContigs[$i][$j]);
                //print_r($nContigs[$i][$j]);
                if($nContigs[$i][$j] < $min){
                      
                    $min = $nContigs[$i][$j];
                    $p0 = $i;
                    // $p1=$j;
                    }

                }
                //var_dump($min);

                }else
                echo "Error in the assembler process";

                $dirQuast = self::OUTPUT_QUAST . "/" . $oa;
                //var_dump($dirQuast);
                // echo "</br>";
                $cmd_quast = sprintf('%s %s %s %s', self::APP_QUAST, self::MIN_CONTIGS . $m, $dirQuast, self::INPUT_QUAST1 . $oa .'/' . $this->kvalues[$p0] . self::INPUT_QUAST2);
                exec($cmd_quast, $out_quast, $exit_quast);
                //var_dump($cmd_quast);
                $_SESSION['scaffoldPathVelvet'] = self::INPUT_QUAST1 . $oa .'/'. $this->kvalues[$p0] . self::INPUT_QUAST2;

                //echo "</br>";
                //var_dump($_SESSION['scaffoldPath']);
                if (!isset($_SESSION['report_fastqc'])) {
                $_SESSION['report_fastqc'] = array();
                }

                array_push($_SESSION["report_fastqc"], 'assemblerReports/' . $oa . '/report.html');
                echo end($_SESSION['report_fastqc']);
                } catch (Exception $err) {
                echo "Exception " . $err->getMessage(), "\n";
                }
                    
                    
                }else{
                    
                    
                    try {
                Util::makeDir(self::VELVET_OUTPUT_ASSEMBLY . $oa);
                $i = 0;
                //$n = '57f28a61c12c3_t20_l50_q20_p80_trimming_filter.fastq';
                //$c = 'auto';
                //$s = 'teste/';
                while($i<count($this->kvalues)){
                $cmd_assembler2 = sprintf('%s %s%s%s %s %s %s %s %s %s%s%s %s %s', self::VELEVETH_APP_ASSEMBLY, self::VELVET_OUTPUT_ASSEMBLY,
                $oa .'/', $this->kvalues[$i], $this->kvalues[$i], self::VELVET_FILE, self::VELEVET_TYPE, self::VELVET_INPUT_ASSEMBLY_MOD . $_SESSION['saidaFilter'].";",
                self::VELEVETG_APP_ASSEMBLY, self::VELVET_OUTPUT_ASSEMBLY, $oa.'/', $this->kvalues[$i]."/", self::VELVET_CUTOFF, $co . " 2>&1");
                //var_dump($cmd_assembler2);
                exec($cmd_assembler2, $out_assembler2, $exit_assembler2);
                $i++;
                }


                //testo se a montagem foi bem sucedida
                if($exit_assembler2 == 0){
                //entro em cada pasta e dou um grep no arquivo contigs.fa
                //para comparar todos os valores e so gerar o relatorio do melhor 
                $i = 0;
                $min = 9999999;
                $p0 = 0;
                $p1 = 0;

                while($i<count($this->kvalues)){
                $nContigs[] = Util::countContigs(self::VELVET_OUTPUT_ASSEMBLY . $oa . "/" . $this->kvalues[$i]);
                $i++;
                }


                for($i = 0;$i<count($nContigs);$i++){
                     $j = 0;
                     //var_dump($nContigs[$i][$j]);
                //print_r($nContigs[$i][$j]);
                if($nContigs[$i][$j] < $min){
                      
                    $min = $nContigs[$i][$j];
                    $p0 = $i;
                    // $p1=$j;
                    }

                }
                //var_dump($min);

                }else
                echo "Error in the assembler process";

                $dirQuast = self::OUTPUT_QUAST . "/" . $oa;
                //var_dump($dirQuast);
                // echo "</br>";
                $cmd_quast = sprintf('%s %s %s %s', self::APP_QUAST, self::MIN_CONTIGS . $m, $dirQuast, self::INPUT_QUAST1 . $oa .'/' . $this->kvalues[$p0] . self::INPUT_QUAST2);
                exec($cmd_quast, $out_quast, $exit_quast);
                //var_dump($cmd_quast);
                $_SESSION['scaffoldPathVelvet'] = self::INPUT_QUAST1 . $oa .'/'. $this->kvalues[$p0] . self::INPUT_QUAST2;

                //echo "</br>";
                //var_dump($_SESSION['scaffoldPath']);
                if (!isset($_SESSION['report_fastqc'])) {
                $_SESSION['report_fastqc'] = array();
                }

                array_push($_SESSION["report_fastqc"], 'assemblerReports/' . $oa . '/report.html');
                echo end($_SESSION['report_fastqc']);
                } catch (Exception $err) {
                echo "Exception " . $err->getMessage(), "\n";
                }
                    
                }


                break;
            
                default : 
                    echo 'Invalid Option';
       
            }
   
        }

        public function velvet4() {
                echo $_SESSION['scaffoldPathVelvet'];
        }

}

$operacao = $_GET['operacao'];
$op = new assembler2();
$op->{$operacao}($fileAssembly,$cutoffAssembly,$outAssembly,$m,$k);