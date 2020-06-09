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
 *Arquivos para efetuar o delete (arquivo trimmado, arquivo filtrado, para o re
 * latório basta deletar o último criado, pois equivale ao relatório de filter
 * e trimmer
 * )
 *  
 */

session_start(); // inicio da sessao 

sleep(1);//dormir 1 milisegundo


class Operacao
{
    
 
    public function deletePreviousReportAndFiles1()
    { 
        
        try{
        unlink('trimFiles/' . $_SESSION['newNameFile']);
        unlink('trimFiles/' . $_SESSION['saidaTrimmer']);
        //echo ("File deleted " . $_SESSION['newNameFile']);
        }catch(Exception $err){
          echo "Exceção" .$err->getMessage(), "\n";
        }
    }

     public function deletePreviousReportAndFiles2()

    { 
         try{
        //unlink(end($_SESSION['report_fastqc']));
        // echo "Report deleted " . end($_SESSION['report_fastqc']);
        }catch(Exception $err){
         // echo "Exceção" .$err->getMessage(), "\n";
        }
    }

     
 

}


$operacao = $_GET['operacao'];
$op = new Operacao();
$op->{$operacao}();



