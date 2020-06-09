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
 * Variáveis de sessão criadas aqui na página fastx_processor.php
 * $_SESSION['newNameFile'] -> guarda o novo nome do arquivo após o trimmer e o filter
 * $_SESSION['saidaTrimmer'] -> guarda o nome do arquivo de saida apos o trimmer
 *  
 */
session_start();//início da sessão
ini_set('display_errors', true);
error_reporting(E_ALL);
sleep(1);//dormir 1 milisegundo

// Variáveis para guardar os parametros do forumulário de trimmer e filter
    try{
        //if(!isset($_GET['n0']) && !isset($_GET['n1']) && !isset($_GET['n2']) && !isset($_GET['n3']) && !isset($_GET['n4'])){
               
                $n0 = $_GET['n0'];
                $n1 = $_GET['n1'];
                $n2 = $_GET['n2'];
                $n3 = $_GET['n3'];
                $n4 = $_GET['n4'];
                
             
       // }
        
    }catch(Exception $err){
        ehco ("Exception " . $err->getMessage(), "\n");
    }

//Início da classe Operacão
class Operacao{
    
    
        const TRIMMER_APP = '/usr/local/bin/fastq_quality_trimmer';
        const FILTER_APP = '/usr/local/bin/fastq_quality_filter';
        const PHRED_MEASURE = '-Q 33';
        const INPUT_FILE1 = ' -i /var/www/html/gatool/uploads/';
        const INPUT_FILE2 = ' -i /var/www/html/gatool/trimFiles/';
        const OUTPUT_FILE = ' -o /var/www/html/gatool/trimFiles/'; 
        const ARGS_PARAMS1 = ' -t';
        const ARGS_PARAMS2 = ' -l';
        const ARGS_PARAMS3 = '_t';
        const ARGS_PARAMS4 = '_l';
        const ARGS_PARAMS5 = ' -q';
        const ARGS_PARAMS6 = ' -p';
        const ARGS_PARAMS7 = '_q';
        const ARGS_PARAMS8 = '_p';
        const OUT_TRIMMING = '_trimming.fastq 2>&1';
        const OUT_TRIMMING_FILTER = '_trimming_filter.fastq 2>&1';
            
	public function op1FilterAndTrimmer(){


	}

	public function op2FilterAndTrimmer($op0,$op1,$op2,$op3,$op4){
           
            
                    /*
                     * Adaptação do nome do arquivo na saida do filter, pois estava gerando inconsistencia entre os relatorios
                     * se o arquivo tivesse o mesmo nome sobrescrevia o antigo mesmo o trimmer sendo diferente
                     */
                    switch($op0){
                       
                        case 1:
                            try{
                                

                              
                                       
                                //var_dump($_SESSION['fileName']);
                                $cmd_trimmer = sprintf('%s %s %s%d %s%d %s%s %s%s%s%d%s%d%s', self::TRIMMER_APP,  self::PHRED_MEASURE, self::ARGS_PARAMS1, $op1, self::ARGS_PARAMS2, $op2, self::INPUT_FILE1,$_SESSION['fileName'],  self::OUTPUT_FILE, $_SESSION['nameExt'], self::ARGS_PARAMS3, $op1 ,  self::ARGS_PARAMS4,$op2,  self::OUT_TRIMMING);
                               // var_dump($cmd_trimmer);
                                echo exec($cmd_trimmer, $out_trimmer);
                                
                                $_SESSION['saidaTrimmer'] = $_SESSION['nameExt'] . self::ARGS_PARAMS3 . $op1 . self::ARGS_PARAMS4 . $op2 . '_trimming.fastq';
                                //var_dump($_SESSION['saidaTrimmer']);
                                $cmd_filter = sprintf('%s %s %s%d %s%d %s%s %s%s%s%d%s%d%s%d%s%d%s',  self::FILTER_APP, self::PHRED_MEASURE, self::ARGS_PARAMS5, $op3, self::ARGS_PARAMS6, $op4, self::INPUT_FILE2, $_SESSION['saidaTrimmer'],  self::OUTPUT_FILE, $_SESSION['nameExt'], self::ARGS_PARAMS3, $op1,  self::ARGS_PARAMS4, $op2, self::ARGS_PARAMS7, $op3, self::ARGS_PARAMS8,$op4,  self::OUT_TRIMMING_FILTER);
                                echo exec($cmd_filter, $out_filter);
                                //var_dump($cmd_filter);
                                //variável de sessão para guardar o novo nome após o trimmer e o filter, para ser usado na proxima análise */
                                echo $_SESSION['newNameFile'] = $_SESSION['nameExt'] . self::ARGS_PARAMS3 . $op1 . self::ARGS_PARAMS4 . $op2  . self::ARGS_PARAMS7 . $op3 . self::ARGS_PARAMS8 . $op4 . "_trimming_filter.fastq";
                              
                                }catch(Exception $err){
                                        echo "Exception " .$err->getMessage(), "\n";
                            } 
                          
                    
                          break;
                   
                    
                        case 2:
                            
                          
                        break;
                    
                        default:
                        echo "Erro";
                    }
            
            

		

	}
}

$operacao = $_GET['operacao']; // pegando via get o que foi enviado
$op = new Operacao(); // instanciando a classe
$op->{$operacao}($n0,$n1,$n2,$n3,$n4); //passando os atributos por parametro
