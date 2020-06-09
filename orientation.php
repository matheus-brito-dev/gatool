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
 * Arquivo para orientação do genoma apenas no modo upload
 * 
 * 
 * Variáveis de sessão criadas aqui na página operacao.php
 * $_SESSION['nameExt'] -> guarda somente o nome do arquivo enviado sem a extensao
 * $_SESSION['report_fastqc'] -> um vetor de sessão para guardar todos 
 * os relatórios gerados pelo fastqc
 *  */
header('Content-Type: text/html; charset=UTF-8');
ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);
session_start(); //inicio da sessao
sleep(1);// dormir 1 milisegundo
require_once 'util/Util.php';
require_once 'util/Zip.php';
//python CONTIGuatorD.py -c [contigs.montados.fasta] -r referencia.fasta -g ref.gbk > resultOrientation.log


$flag = $_GET['f'];


class Operacao
{
    
    const APP_CONTIGuator = '/var/www/html/gatool/util/CONTIGuatorD.py';
    const PARAMS1 = ' -c ';
    const PARAMS2 = ' -r ';
    const PARAMS3 = ' -g ';
    const PARAMS4 = 'sudo python ';
    const PARAMS5 = ' -f ';
    const OUTPUT_CONTIGuator = ' > /var/www/html/gatool/orientationOutput/orientationLog.log';
    const ORIENTATION_PATH = '/var/www/html/gatool/orientationOutput/';
    const ROOT_PATH = '/var/www/html/gatool/';
    const REF_GB_PATH = '/var/www/html/gatool/referencesGb/';
    const REF_FASTA_PATH = '/var/www/html/gatool/referencesFasta/';
    public $dono = "www-data";
    public $grupo = "root";

    
    public function orientation1()
    {
          // $teste = '58fcd4c935a3158fcd591ef6b4';
           //$ret =  Zipper::Zip(Zipper::PATH . $teste . '/', Zipper::RAIZ . $teste . '.zip');
           
           
           
            //$obj = new Zipper();
            //$obj->Zip(Zipper::PATH . $file. '/', Zipper::RAIZ . $file . '.zip');

    }

     public function orientation2($f)

    {
         //file de teste
        //$file = "123456";
        if($f == 0){ //caso spades com form de upload
            
        
        $file = Util::getFileName($_SESSION['fileName']);
        $file = $file . uniqid();
        
         //fileNameRefFasta fileNameRefGb
        //exec de produção
        $cmd_orientation = sprintf('%s%s%s%s%s', self::PARAMS4 . 
        self::APP_CONTIGuator . self::PARAMS1, $_SESSION['scaffoldPath'] .  
        self::PARAMS2, self::REF_FASTA_PATH . $_SESSION['fileNameFasta'] . 
        self::PARAMS3, self::REF_GB_PATH . $_SESSION['fileNameGb'] .
        self::PARAMS5 , self::ORIENTATION_PATH . self::OUTPUT_CONTIGuator);

         //exec de teste
        /*$cmd_teste = sprintf('%s%s%s%s%s', self::PARAMS4 . self::APP_CONTIGuator . self::PARAMS1,  
                $_SERVER['DOCUMENT_ROOT'] . "/gatool/assemblers/saida58012d222c774/scaffolds.fasta".
        self::PARAMS2, $_SERVER['DOCUMENT_ROOT']."/gatool/referencesFasta/sequence5801322e10de5.fasta" .
        self::PARAMS3, $_SERVER['DOCUMENT_ROOT'] .
        "/gatool/referencesGb/sequence5801322e10de5.gb" .
        self::PARAMS5 , $_SERVER['DOCUMENT_ROOT'] . "/gatool/orientationOutput/" .
        self::OUTPUT_CONTIGuator);*/
         
        
        //echo exec($cmd_teste, $out_teste, $exit_teste);
        echo exec($cmd_orientation, $out_orientation, $exit_orientation);
       //var_dump($cmd_orientation);
 
       if($exit_orientation == 0){
           
           //criando os caminhos para criar as pastas
        $newPathteste = self::ORIENTATION_PATH . $file;
        $path = self::ORIENTATION_PATH . $file . "/";
        
        //pastas para guardar os arquivos mapeados e n mapeados do contiguator
        $path1 = $path . "_mapped";
        $path2 = $path . "_unmapped";
        
       if (!is_dir($newPathteste) && !is_dir($path1) && !is_dir($path2)) {
            
            //mkdir(self::ROOT_PATH . "orientationOutput",0777,true);
            mkdir($newPathteste,0777,true);
            mkdir($path1,0777,true);
            mkdir($path2 ,0777,true);
            
        }
       // lendo dentro do diretório
       $files = scandir(self::ORIENTATION_PATH);
       
       //arquivos(diretórios) dentro do diretório scaneado
       $file1 = $file; // pasta criada para guardar mapeados e não mapeados
       $file2 = $files[count($files) -3];//pasta dos mapeados gerado pelo contiguator
       $file3 = $files[count($files) -2];//pasta dos nao mapeados gerados pelo contiguator
       $file4 = $files[count($files) -1];//arquivo de log
       
       /*
        * Depois que o contiguator cria as pastas ele não reconhece www-data
        * como pertencente ao grupo root, é preciso alterar as permissões
        *
        *            
        */
       
        Util::changeOwnerGroup($this->dono, $this->grupo, self::ORIENTATION_PATH);
        
       //montagem dos caminhos para servir de base para copiar e deletar
       $dirFilesMapByContiguator = self::ORIENTATION_PATH . $file2 . "/";
       $filesMapByContiguator =  scandir($dirFilesMapByContiguator);
       
       $dirFilesUnmapByContiguator = self::ORIENTATION_PATH. $file3 . "/";
       $filesUnmapByContiguator =  scandir($dirFilesUnmapByContiguator);
       
       $dirDestinoMapped = self::ORIENTATION_PATH. $file1 . "/". "_mapped/";
       $dirDestinoUnmapped = self::ORIENTATION_PATH. $file1 . "/". "_unmapped/";
       
       //mandando o arquivo de log gerado no contiguator
       copy(self::ORIENTATION_PATH . $file4 , self::ORIENTATION_PATH . $file1 . "/" . $file4);
       
      //removendo arquivo de resultado do mapeamento
       if(file_exists(self::ORIENTATION_PATH . $file4)){
       unlink(self::ORIENTATION_PATH . $file4);
       }
       
       //mandando os arquivos dentro da pasta dos mapeados
       for($i=2;$i<  count($filesMapByContiguator);$i++)
           copy($dirFilesMapByContiguator . $filesMapByContiguator[$i],$dirDestinoMapped . $filesMapByContiguator[$i]);
       for($i=2;$i<  count($filesUnmapByContiguator);$i++)//mandando os arquivos dentro da pasta dos não mapeados
           copy($dirFilesUnmapByContiguator . $filesUnmapByContiguator[$i],$dirDestinoUnmapped . $filesUnmapByContiguator[$i]);
       
        //removendo as pastas recursivamente
       if(is_dir(self::ORIENTATION_PATH . $file)){
          Util::cleanDirectory(self::ORIENTATION_PATH . $file2);
          Util::cleanDirectory(self::ORIENTATION_PATH . $file3);
          Util::deleteFile(self::ROOT_PATH . 'CONTIGuator.log');
          Util::deleteFile(self::ROOT_PATH . 'error.log');
       }
          
          

           //Chamando instancia ZIP para zipar o conteudo das pastas
           //$obj = new Zipper();
           //$obj->Zip(Zipper::PATH . $file. '/', Zipper::RAIZ . $file . '.zip');
          Zipper::Zip(Zipper::PATH . $file. '/', Zipper::RAIZ . $file . '.zip');
           
          /*Relatório*/
          
          if (!isset($_SESSION['report_fastqc'])) {
                            $_SESSION['report_fastqc'] = array();
                                }

                            array_push($_SESSION["report_fastqc"], 'orientationOutput/' . $file  . '/orientationLog.log');
                            echo end($_SESSION['report_fastqc']);
                            
        }else{ echo $exit_orientation;}
          
          
     }else if($f == 1){ //caso velvet com form upload
         
            $file = Util::getFileName($_SESSION['fileName']);
            $file = $file . uniqid();
            

            //exec de produção
            $cmd_orientation = sprintf('%s%s%s%s%s', self::PARAMS4 .
                    self::APP_CONTIGuator . self::PARAMS1, $_SESSION['scaffoldPathVelvet'] .
                    self::PARAMS2, self::REF_FASTA_PATH . $_SESSION['fileNameFasta'] .
                    self::PARAMS3, self::REF_GB_PATH . $_SESSION['fileNameGb'] .
                    self::PARAMS5, self::ORIENTATION_PATH . self::OUTPUT_CONTIGuator);

            //exec de teste
            /* $cmd_teste = sprintf('%s%s%s%s%s', self::PARAMS4 . self::APP_CONTIGuator . self::PARAMS1,  
              $_SERVER['DOCUMENT_ROOT'] . "/gatool/assemblers/saida58012d222c774/scaffolds.fasta".
              self::PARAMS2, $_SERVER['DOCUMENT_ROOT']."/gatool/referencesFasta/sequence5801322e10de5.fasta" .
              self::PARAMS3, $_SERVER['DOCUMENT_ROOT'] .
              "/gatool/referencesGb/sequence5801322e10de5.gb" .
              self::PARAMS5 , $_SERVER['DOCUMENT_ROOT'] . "/gatool/orientationOutput/" .
              self::OUTPUT_CONTIGuator); */


            //echo exec($cmd_teste, $out_teste, $exit_teste);
            echo exec($cmd_orientation, $out_orientation, $exit_orientation);
            //var_dump($cmd_orientation);
            if($exit_orientation == 0){ 
                
                //criando os caminhos para criar as pastas
            $newPathteste = self::ORIENTATION_PATH . $file;
            $path = self::ORIENTATION_PATH . $file . "/";

            //pastas para guardar os arquivos mapeados e n mapeados do contiguator
            $path1 = $path . "_mapped";
            $path2 = $path . "_unmapped";

            if (!is_dir($newPathteste) && !is_dir($path1) && !is_dir($path2)) {

                //mkdir(self::ROOT_PATH . "orientationOutput", 0777, true);
                mkdir($newPathteste, 0777, true);
                mkdir($path1, 0777, true);
                mkdir($path2, 0777, true);
            }
              // lendo dentro do diretório
              $files = scandir(self::ORIENTATION_PATH);
              
              //arquivos(diretórios) dentro do diretório scaneado
              $file1 = $file; // pasta criada para guardar mapeados e não mapeados
              $file2 = $files[count($files) -3];//pasta dos mapeados gerado pelo contiguator
              $file3 = $files[count($files) -2];//pasta dos nao mapeados gerados pelo contiguator
              $file4 = $files[count($files) -1];//arquivo de log
             
            /*
             * Depois que o contiguator cria as pastas ele não reconhece www-data
             * como pertencente ao grupo root, é preciso alterar as permissões
             *
             *            
             */
            
              Util::changeOwnerGroup($this->dono, $this->grupo, self::ORIENTATION_PATH);

              //montagem dos caminhos para servir de base para copiar e deletar
              $dirFilesMapByContiguator = self::ORIENTATION_PATH . $file2 . "/";
              $filesMapByContiguator =  scandir($dirFilesMapByContiguator);

              $dirFilesUnmapByContiguator = self::ORIENTATION_PATH. $file3 . "/";
              $filesUnmapByContiguator =  scandir($dirFilesUnmapByContiguator);

              $dirDestinoMapped = self::ORIENTATION_PATH. $file1 . "/". "_mapped/";
              $dirDestinoUnmapped = self::ORIENTATION_PATH. $file1 . "/". "_unmapped/";

              //mandando o arquivo de log gerado no contiguator
              copy(self::ORIENTATION_PATH . $file4 , self::ORIENTATION_PATH . $file1 . "/" . $file4);

              //removendo arquivo de resultado do mapeamento
              if(file_exists(self::ORIENTATION_PATH . $file4)){
              unlink(self::ORIENTATION_PATH . $file4);
              }

              //mandando os arquivos dentro da pasta dos mapeados
              for($i=2;$i<  count($filesMapByContiguator);$i++)
                copy($dirFilesMapByContiguator . $filesMapByContiguator[$i],$dirDestinoMapped . $filesMapByContiguator[$i]);
              for($i=2;$i<  count($filesUnmapByContiguator);$i++)//mandando os arquivos dentro da pasta dos não mapeados
                copy($dirFilesUnmapByContiguator . $filesUnmapByContiguator[$i],$dirDestinoUnmapped . $filesUnmapByContiguator[$i]);
            
             //testo p ve se é um diretório para só remover se foram criados
              if(is_dir(self::ORIENTATION_PATH . $file)){
              //removendo as pastas recursivamente
               Util::cleanDirectory(self::ORIENTATION_PATH . $file2);
               Util::cleanDirectory(self::ORIENTATION_PATH . $file3);
               Util::deleteFile(self::ROOT_PATH . 'CONTIGuator.log');
               Util::deleteFile(self::ROOT_PATH . 'error.log');
               }else{echo "Error";}
               
               
               //Chamando instancia ZIP para zipar o conteudo das pastas
                 //$obj = new Zipper();
                 Zipper::Zip(Zipper::PATH . $file. '/', Zipper::RAIZ . $file . '.zip');
                /*Relatório*/
          
          if (!isset($_SESSION['report_fastqc'])){
                            $_SESSION['report_fastqc'] = array();
                                }

                            array_push($_SESSION["report_fastqc"], 'orientationOutput/' . $file  . '/orientationLog.log');
                            echo end($_SESSION['report_fastqc']);
                            
            }else{ echo $exit_orientation;}                 
                
        }
    }
   }


$operacao = $_GET['operacao'];
$op = new Operacao();
$op->{$operacao}($flag);


