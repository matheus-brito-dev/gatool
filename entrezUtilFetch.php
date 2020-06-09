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
sleep(1); // dormir 1 milisegundo



    $idOrganism = trim($_GET['id']);

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

public $listIdsLinks = array();
public $fullListReferences = array();



public function executeFetch1()
{

header('Content-Type: application/json');
echo json_encode("");

}


public function executeFetch2($id)

{

        $myLink = sprintf('%s%s', self::BASE_URL . self::E_UTIL_LINK . self::PARAMS1_LINK . self::DBG . self::PARAMS2_LINK . self::DBN . self::params4, $id); 
        
        $retLink = simplexml_load_file($myLink);
       
        //print_r($retLink);
        
        foreach ($retLink->LinkSet->LinkSetDb->Link as $value) {
          
            $this->listIdsLinks [] =  strval($value->Id);
            
         
        }
      
        $listByLink = implode(',', $this->listIdsLinks);
      
        $summaryByIds = sprintf('%s%s', self::BASE_URL . self::E_UTIL_SUMMARY . self::params1 . self::DBN . self::params4, $listByLink);
        
        $retSummaryById = simplexml_load_file($summaryByIds);
        
        //print_r($retSummaryById);
         $k=0;
         foreach ($retSummaryById->DocSum as $value) {
 
            $this->fullListReferences[$k]['id'] = strval($value->Id);
            $this->fullListReferences[$k]['referenceName'] = strval($value->Item[0]);
            $k++;
        }
      
        header('Content-Type: application/json');
        echo json_encode($this->fullListReferences);
    }


}

$operacao = $_GET['operacao'];
$op = new Operacao();
$op->{$operacao}($idOrganism);



