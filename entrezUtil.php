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
 * # =======================================================================
#
#                            PUBLIC DOMAIN NOTICE
#               National Center for Biotechnology Information
#
#  This software/database is a "United States Government Work" under the
#  terms of the United States Copyright Act.  It was written as part of
#  the author's official duties as a United States Government employee and
#  thus cannot be copyrighted.  This software/database is freely available
#  to the public for use. The National Library of Medicine and the U.S.
#  Government have not placed any restriction on its use or reproduction.
#
#  Although all reasonable efforts have been taken to ensure the accuracy
#  and reliability of the software and data, the NLM and the U.S.
#  Government do not and cannot warrant the performance or results that
#  may be obtained by using this software or data. The NLM and the U.S.
#  Government disclaim all warranties, express or implied, including
#  warranties of performance, merchantability or fitness for any particular
#  purpose.
#
#  Please cite the author in any work or product based on this material.
#  Author:  Oleg Khovayko
# =======================================================================
#
# Author: Matheus Brito
#
# File Description: Cria lista de ids baseado na pesquisa do usuário
# e associa escolha com o genoma referencia, permitindo o download
#  do mesmo
# ---------------------------------------------------------------------
# 
 */
session_start(); //inicio da sessao
sleep(1);// dormir 1 milisegundo


    //if(isset($_GET['term'])){
        $term = trim($_GET['term']);
    //}


class Operacao
{
    
    const DBG = 'genome';
    const DBN = 'nucleotide';
    const BASE_URL = 'http://eutils.ncbi.nlm.nih.gov/entrez/eutils/';
    const E_UTIL = 'esearch.fcgi?';
    const params1 = 'db=';
    const params2 = '&term=';
    const params3 = '&usehistory=y';
    const params4 = '&id='; 
    const E_UTIL_SUMMARY = 'esummary.fcgi?';
    const PARAMS4_SUMMARY = '&query_key=';
    const PARAMS5_SUMMARY = '&WebEnv=';
    const E_UTIL_LINK = 'elink.fcgi?';
    const PARAMS1_LINK = 'dbfrom=';
    const PARAMS2_LINK = '&db=';
    
    public $idLists = array();
    public $listNames = array();
    public $listIdsLinks = array();
    public $idListNames = array();
    
    var  $webEnv;
    var  $query_key;
    var  $query;
   
     /*
         * Para resolver o problema do efetch no genome database eu tive que usar o elink para
         * associar os ids encontrados com o banco nucleotide, resultando assim os id' dos genomas
         * de referência.
         * 
         * Fluxo:
         * 1-Fazer o esearch com o termo correspondente
         * retorno: lista de id's no db genome
         * 2- Fazer esummary para pegar o nome da especie e mostrar ao usuário
         * 3-Pegar escolha do usuário; funcionalidade ao passar o mouse: mostrar uma descrição
         * 4-Fazer elink com a escolha do usuário e mostrar para ele a lista de links com os genomas de referencia;
         * retorno: lista de id's no db nucleotide
         * 5-Pegar a escolha do usuário e fazer o download do arquivo no server funcionalidade ao passar o mouse: mostrar uma descrição
         * retorno: file
         */
  
    public function execute1()
    {
        
     header('Content-Type: application/json');
     echo json_encode("");
        
    }
    
    
     public function execute2($t)

    {
         //@header("Content-Type: text/html; charset=utf-8");
        $this->query = $t;
        $mySearch = sprintf('%s%s', self::BASE_URL . self::E_UTIL . self::params1 . self::DBG . self::params2, $t . self::params3);

        $retSearch = simplexml_load_file($mySearch);

        $this->webEnv = $retSearch->WebEnv;

        $this->query_key = $retSearch->QueryKey;
       
        foreach ($retSearch->IdList->Id as $value) {

            $this->idLists[] = strval($value);
        }
        
        $this->idLists[] = strval($this->query_key);
        $this->idLists[] = strval($this->webEnv);
        
        $mySummary = sprintf('%s',  self::BASE_URL . self::E_UTIL_SUMMARY . self::params1 . self::DBG .  self::PARAMS4_SUMMARY . $this->query_key . self::PARAMS5_SUMMARY . $this->webEnv);
         
        $retSummary = simplexml_load_file($mySummary);
         
         $k=0;
         foreach ($retSummary->DocSum as $value) {
 
             $this->listNames[$k]['id'] = strval($value->Id);
             $this->listNames[$k]['organismName'] = strval($value->Item[0]);
             $k++;
        }
         //serialize($this->listNames);
         header('Content-Type: application/json');
         echo json_encode($this->listNames);
               
        
        
        
        
        
        
    }
    
     public function execute3()

    {
    
    }
 }

  

$operacao = $_GET['operacao'];
$op = new Operacao();
$op->{$operacao}($term);



