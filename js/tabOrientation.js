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

var term;
var contOrientation=0;
var contFetch=0;
var contDownload=0;
var opcaoRadio;
var idOrganism;
var idOrganismReference;
var valor = 0;
var contRadio=0;
var uploadedFileNameFasta;
var uploadedFileNameGb;
var flag = -1;
var contOrientationReport =0;
var directoryName;

/*
 * 
 * @returns {undefined}
 * 
 */

function goTabOrientation(){

    
    $('#tabOrientation').click();
    $('#jumboAnalysis').removeClass('alert-info');
    $('#jumboTrimmer').removeClass('alert-info');
    $('#jumboEntry').removeClass('alert-info');
    $('#jumboAssembler').removeClass('alert-info');
    $('#jumboOrientation').addClass('alert-info');
    
    $('#organisms').hide();
    $('#references').hide();
    $('#label1').hide();
    $('#label2').hide();
    
    $('#opUpload').hide();
    $('#opOnline').hide();
    
    $("#fileBarOrientation").css('width', 0).attr('aria-valuenow', 0);
    
    choose();

}
/*
function getDirectoryName(e){
                e.preventDefault();
                var oldURL = e;
                directoryName = oldURL.split('/')[3];
                
                listLinksToDownload(directoryName,oldURL);
}

function listLinksToDownload(name, linkOrig){
    
    
    var link1 = 'localhost/gatool/orientationOutput/' + e + '/_mapped/PseudoContig.fsa' 
    var link2 = 'localhost/gatool/orientationOutput/' + e + '/_mapped/PseudoContig.fsa' 
    
    alert(link1);
}*/
//Método para escolher qual o tipo de meio que irá
//ser usado para escolher os arquivos do genoma de referência
function choose(){
    
    $('input:radio[name=type]').click(function () {
        if($(this).attr("value")=="0"){
            
            $('#opUpload').show();
            $('#opOnline').hide();
            clearFieldsOrientation();
       
        }else{
             $('#opOnline').show();
             //desabilitando o botão de orientar
             $('#btOrientation').prop("disabled",true);
             $('#opUpload').hide();
        }

    });
}

//método para resetar os componentes
function resetComponenents(){
    
    $('#organisms').attr('disabled',false);
    $('#organisms').empty();
    $('#organisms').hide();
    
    $('#references').attr('disabled',false);
    $('#references').empty();
    $('#references').hide();
    
    $('#label1').hide();
    $('#label2').hide();
    
    $('#term').attr("disabled", true);
    $('#btTerm').attr('disabled',true);
    
    //start orientation
    
}
//método para limpar os campos da área de 
//escolha dos arquivos online
function clearFieldsOrientation(){
    
    $('#term').val("");
    $('#organisms').hide();
    $('#references').hide();
    $('#label1').hide();
    $('#label2').hide();
    $('#organisms').val(0);
    $('#references').val(0);
    $('#term').attr("disabled", false);
    $('#btTerm').attr('disabled',false);
    $('#term').css("background-color","#fff");
    $('#references').prop('disabled',false);
    $('#organisms').prop('disabled',false);
    $('#organisms').empty();
    $('#references').empty();
    $('#btMultipleUp').prop("disabled","disabled");
    
                 
}
//habilitando o botão ao escolher o primeiro arquivo no upload
function enableButton(e){
    $("#btMultipleUp").prop("disabled",false);
}


function fitListOrientation(e){
    
    //e.preventDefault();
    item = $(document.createElement('li'));
    item.addClass('list-group-item');
    var link = $(document.createElement('a'));
    link.html('Orientation Report').click(function(){
        openReportPopup(e);
    });
    item.append(link);
    $('#report_itens').append(item);
    openReportPopup(e);

}



function fitListOrientationDownReports(e){
   
           
            var array = e.split('/');
            
            item = $(document.createElement('li'));
            item.addClass('list-group-item');
    
            var link = $(document.createElement('a'));
    
    //<span class="glyphicon glyphicon-download" aria-hidden="true"></span>
            link.html(array[1]  + '.zip').click(function(){
       
                link.attr('href','/gatool/zips/' + array[1] + '.zip');
                link.attr('download',array[1]);
    });
            item.append(link);
            $('#files_itens').append(item);
    
    
    
}    


/****************** upload dos arquivos que representam o genoma de referência Fasta******************/


                        function _(el) {
                            return document.getElementById(el);
                        }

                        function uploadFasta(e) {
                            e.preventDefault();
                            //pegando o nome dos campos de entrada
                            var fileFasta = document.getElementById("fileFastaUpload");
                            
                            //Verificando se o arquivo tem a extensão fasta
                            if (fileFasta.value.indexOf('.fasta') === -1) {
                                alert('Only fasta files are allowed.');
                                //location.href = '';
                                return;
                            }
                            
                            //desabilitando o botão de realizar o upload
                            $('#btMultipleUp').prop('disabled','disabled');
                            
                            //criando o formdata para o envio de dados com POST e ajax
                            //arquivo fasta
                            var formdataFasta = new FormData();
                            formdataFasta.append("fileFastaUpload", fileFasta.files[0]);
                            formdataFasta.append("MAX_UPLOAD_SIZE", $('#MAX_FILE_SIZE').val());
                            
                            $.ajax({
                                url: 'upload2.php',
                                data: formdataFasta,
                                processData: false,
                                contentType: false,
                                type: 'POST',
                                xhr: function () {
                                    var xhrFasta = new window.XMLHttpRequest();
                                    xhrFasta.upload.addEventListener("progress", progressHandlerFasta, false);
                                    xhrFasta.addEventListener("progress", progressHandlerFasta, false);
                                    return xhrFasta;
                                },
                                success: function () {
                                     //console.log('upload 1 concluido');
                                    //setTimeout(completeHandler, 2000);
                                    
                                }
                            });
                            //chamando o upload do segundo upload dentro do primeiro
                            uploadGb(event);
                            return false;
                        }
                       
                        function progressHandlerFasta(event) {
                            if (event.lengthComputable) {
                                var percentFasta = Math.round((event.loaded / event.total) * 100);
                                //$("#fileBarMultipleUp").css('width', percentFasta + '%').attr('aria-valuenow', percentFasta);
                                //console.log(percent);
                            }
                        }

                        function completeHandlerFasta(event) {
                           
                            $("#fileBarMultipleUp").css('width', '0%').attr('aria-valuenow', 0);
                            $('#fileFastaUpload').html("");
                            // pegando somente o nome do arquivo upado, descartando os diretórios
                            uploadedFileNameFasta = $("#fileFastaUpload").val().split("\\").pop();
                       
                       }

                        function errorHandlerfasta(event) {
                            _("status").innerHTML = "Upload Failed";
                        }

                        function abortHandlerFasta(event) {
                            _("status").innerHTML = "Upload Aborted";
                        }


/*****************************************fim********************************/




/****************** upload dos arquivos que representam o genoma de referência Gb******************/

                        function __(el) {
                            return document.getElementById(el);
                        }

                        function uploadGb(e) {
                            e.preventDefault();
                            //pegando o nome dos campos de entrada
                            var fileGb = document.getElementById("fileGbUpload");
            
                            //Verificando se o arquivo tem a extensão fasta
                            if (fileGb.value.indexOf('.gb') === -1) {
                                alert('Only gb files are allowed.');
                                return;
                            }
                            
                            //desabilitando o botão de realizar o upload
                            //$('#btOrientation').prop('disabled','disabled');
                            
                            //criando o formdata para o envio de dados com POST e ajax
                            //arquivo fasta
                            var formdataGb = new FormData();
                            formdataGb.append("fileGbUpload", fileGb.files[0]);
                            formdataGb.append("MAX_UPLOAD_SIZE", $('#MAX_FILE_SIZE').val());
                            
                            $.ajax({
                                url: 'upload3.php',
                                data: formdataGb,
                                processData: false,
                                contentType: false,
                                type: 'POST',
                                xhr: function () {
                                    var xhrGb = new window.XMLHttpRequest();
                                    xhrGb.upload.addEventListener("progress", progressHandlerGb, false);
                                    xhrGb.addEventListener("progress", progressHandlerGb, false);
                                    return xhrGb;
                                },
                                success: function () {
                                  
                                  setTimeout(completeHandlerGb, 2000);
                                    
                                }
                            });
                            
                            return false;
                        }
                       
                        function progressHandlerGb(event) {
                            if (event.lengthComputable) {
                                var percentGb = Math.round((event.loaded / event.total) * 100);
                               // $("#fileBarMultipleUp").css('width', percentGb + '%').attr('aria-valuenow', percentGb);
                                //console.log(percent);
                            }
                        }

                        function completeHandlerGb(event) {
                          // console.log(status);
                            //$("#fileBarMultipleUp").css('width', '0%').attr('aria-valuenow', 0);
                            $('#fileGbUpload').html("");
                            // pegando somente o nome do arquivo upado, descartando os diretórios
                            uploadedFileNameGb = $("#fileGbUpload").val().split("\\").pop();
                           
                              if(status==1){ //se a montagem foi feita no SPAdes
                                flag = 0; //via upload
                                orientation1();
                                    
                                   
                                }else if(status==2){ // se a montagem foi feita no velvet
                                flag = 1; //via upload
                                //orientationContigsUpload1();
                                orientation1();
                                    }

                       }

                        function errorHandlerGb(event) {
                            _("status").innerHTML = "Upload Failed";
                        }

                        function abortHandlerGb(event) {
                            _("status").innerHTML = "Upload Aborted";
                        }


/*****************************************fim********************************/

function initOrientation(e){
    
    e.preventDefault();
    //escolhendo o termo de busca do genoma de referencia
    term =  $('#term').val();
    //se o termo é diferente de vazio manda buscar
   if(term!=""){
   //chamada ajax para começar o processo de busca do genoma de referencia
   execute1();
   //desabilitando campos em tempo real
   $('#term').attr("disabled", true);
   $('#btTerm').attr('disabled',true);
   $('#term').css("background-color", "#d3d3d3");
 
   $('#retornoOrientation').html('');
    }else{
        $('#retornoOrientation').html('Please make a search');
    }
   
}
/**************Inicio do processo de orientação dos contigs usando a opção online**********************/
//método que inicia o processo de orientação dos contigs
function orientContigs(e){

    e.preventDefault();
    
    if(status==1){
        flag = 2;
        orientationContigsUpload1();
        
    }else if(status==2){
        flag =3;
        orientationContigsUpload1();
         }
    
}

function orientation(operacao,pValor,proximo){
    $.ajax({
        async: true,
        //dataType: 'json',
        url: 'orientation.php',
        data: 'operacao=' + operacao + '&f=' + flag,
        success: function (ret) {
           
            contOrientationReport+=1;
            $("#fileBarMultipleUp").css('width',pValor).attr('aria-valuenow',pValor);
            
            if(contOrientationReport==2){
                
              
                     fitListOrientation(ret);
                     fitListOrientationDownReports(ret);
                     proximo();
       
            }
            proximo();
        }

    });
}


function orientation1() {
    orientation('orientation1', 350, orientation2);
                        }

function orientation2() {
    orientation('orientation2', 1000, function () {
        flag=-1;
        contOrientationReport=0;
    });
}

function clean(){
    $('#fileFastaUpload').val("");
    $('#fileGbUpload').val("");
    $("#fileBarMultipleUp").css('width',0).attr('aria-valuenow',0);
    $("#fileBarOrientation").css('width',0).attr('aria-valuenow',0);
}

/******************************fim************************************************/

/**************Inicio do processo de orientação dos contigs usando a opção local**********************/
//método que inicia o processo de orientação dos contigs


function orientationUp(operacao,pValor,proximo){
    $.ajax({
        async: true,
        //dataType: 'json',
        url: 'orientation2.php',
        data: 'operacao=' + operacao + '&f=' + flag,
        success: function (ret) {
            console.log(ret);
            contOrientationReport+=1;
            //$("#fileBarMultipleUp").css('width', pValor).attr('aria-valuenow', pValor);
            $("#fileBarOrientation").css('width', pValor).attr('aria-valuenow', pValor);
            if(contOrientationReport==2){
                
                proximo();
                fitListOrientation(ret);
                fitListOrientationDownReports(ret);
             
            }
            proximo();
        }
    });
}

function orientationContigsUpload1(){
    orientationUp('orientationContigsUpload1', 350, orientationContigsUpload2);
                        }

function orientationContigsUpload2(){
    orientationUp('orientationContigsUpload2', 1000, function () {
        flag=-1;
        contOrientationReport=0;
    });
}

/******************************fim************************************************/



function executeMethodEntrezSearch(operacao,pValor,proximo){
      
            $.ajax({
                async: true,
                dataType: 'json',
                url: 'entrezUtil.php',
                data: 'operacao=' + operacao + '&term=' + term,
                success: function(ret){
                   
                   contOrientation +=1;
                  
                   if(contOrientation == 2){
                       
                       for(i=0;i<ret.length;i++){

                            fillSelectOrganisms('#organisms',ret[i].organismName,ret[i].id);                   
                       }
                           $('#organisms').show();
                           $('#label1').show();
                     }
                   proximo();
                }
            });
}


function wait(){

          
      $("#fileBarOrientation").css('width', 0).attr('aria-valuenow', 0);


}

function execute1() {
       
            executeMethodEntrezSearch('execute1', 250, execute2);
}

function execute2() {
    
            executeMethodEntrezSearch('execute2', 1000, function () {
                    contOrientation=0;
                    

    });
}

function executeMethodEntrezFetch(operacao, pValor, proximo) {

    $.ajax({
        async: true,
        contentType: "application/json; charset=utf-8",
        dataType: 'json',
        url: 'entrezUtilFetch.php',
        data: 'operacao=' + operacao + '&id=' + idOrganism,
        success: function (ret) {

               
                contFetch = contFetch +1;
                
            if (contFetch == 2) {
               
                for (var i = 0; i < ret.length; i++) {

                    fillSelectOrganisms('#references', ret[i].referenceName, ret[i].id);
                }
                $('#references').show();
                $('#label2').show();
            }

            proximo();
        }

    });
}

function executeFetch1() {
    
    executeMethodEntrezFetch('executeFetch1', 250, executeFetch2);
}

function executeFetch2() {

    executeMethodEntrezFetch('executeFetch2', 900, function () {
                contFetch = 0;
    });
}


function executeMethodEntrezDownload(operacao, pValor, proximo) {

    $.ajax({
        async: true,
        contentType: "application/json; charset=utf-8",
        //dataType: 'json',
        url: 'entrezUtilDownload.php',
        data: 'operacao=' + operacao + '&id=' + idOrganismReference,
        success: function (ret) {
                contDownload++;
                if(contDownload == 2){
                    alert(ret);
            }
                proximo();
            }

    });
}

function executeDownload1() {

    executeMethodEntrezDownload('executeDownload1', 250, executeDownload2);
}

function executeDownload2() {

    executeMethodEntrezDownload('executeDownload2', 900, function () {
        
            contDownload = 0;
            
            $('#btOrientation').attr('disabled',false);
    });
}

function onClickOrganisms(e){
    e.preventDefault();
    idOrganism = $('#organisms').val();
    if(idOrganism != ""){
    executeFetch1();
    $('#retornoOrientation').html('');
    $('#organisms').prop('disabled','disabled');
    }else{
        $('#retornoOrientation').html('Please select the organism');
    }
}

function onClickOrganismsReferece(e){
    
    idOrganismReference = $('#references').val();
    if(idOrganismReference != ""){
    executeDownload1();
    $('#organisms').prop('enabled','enabled');
    $('#references').prop('disabled','disabled');
    $('#retornoOrientation').html('');
    }else{
        $('#retornoOrientation').html('Please select the reference');
    }
    
}
 
function fillSelectOrganisms(comp,file,n) {
    
    var opt1 = document.createElement("option");
    opt1.value = n;
    opt1.text = file;
    $(comp).append(opt1);
}

