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


var cont = 0; // variável para contar até o final e jogar na lista o resultado do relatorio
var contNewAnalsysis = 0; // variável para contar até o final e jogar na lista o resultado do relatorio
var i =1; // variável criada para nomear os relatório na tela
var contAnalysisTimes = 0;
var contDelete=0; // contador para deletar os itens
var item; // item para criar a lista
var nTimeListAnalysis =0;

function goTabAnalysis(){
    
    $('#tabAnalysis').click();
    $('#jumboEntry').removeClass('alert-info');
    $('#jumboAnalysis').addClass('alert-info');
    $('#file_upload').val("");

    /*passando dados entre as abas(colocando saida na tela para o user saber 
    qual arquivo ta sendo analisado)*/
    //$("#saida_analysis").html(uploadedFileName).prepend("Analysis for: ");
   // alert(uploadedFileName);
    $("#saida_analysis").append(uploadedFileName).prepend('Analysis for ');
    //Chamando funcao p já iniciar a análise sem precisar clicar em nada
    opAnalysis1();
}

/*
 * Função ajax para chamar o php e realizar as operações do FastQC
 * @param {String} operacao
 * @param {int} pValor
 * @param {function} proximo
 * @returns {undefined}
 */
function executeAnalysis(operacao, pValor, proximo) {
    $.ajax({
        async: true,
        url: 'operacao.php',
        data: 'operacao=' + operacao,
        success: function (ret) {
            cont += 1;
            
            $("#fileBarAnalysis").css('width', pValor).attr('aria-valuenow', pValor);
            if (cont == 4) {
                proximo();
                fitList(ret);

            }
            proximo();


        }
    });
}
/*
 * Função para criar a lista dinamicamente e inserir os links dos relatórios,
 * recebendo como parametro os links.
 */
function fitList(result) {
    
    nTimeListAnalysis=nTimeListAnalysis+1;
    //alert(nTimeListAnalysis);
    item = $(document.createElement('li'));
    item.addClass('list-group-item');
    var link = $(document.createElement('a'));
    if(nTimeListAnalysis==1){
    link.html('Analysis Report raw').click(function() {
       openReportPopup(result);
    });
    }else{
    link.html('Analysis Report Trimmed/Filter').click(function() {
       openReportPopup(result);
    });
}
    item.append(link);
    $('#report_itens').append(item);
         openReportPopup(result);
         goChooseModal(i);
    i++;
    cont = 0;
    
   
}

function removeItemList(result) {
     
    item.removeClass('list-group-item');
    item.remove('li');
}


/*
 * 
 * Função para abrir uma Popup na tela com os relatórios
 * 
 */
function openReportPopup(e) {
    
        var w = 1050;
        var h = 610;
        var scroll = 'yes';
        var leftPosition = (screen.width) ? (screen.width-w)/2 : 0;
	var topPosition = (screen.height) ? (screen.height-h)/2 : 0;
        var settings = 'height='+h+',width='+w+',top='+topPosition+',left='+leftPosition+',scrollbars='+scroll+',resizable'
        window.open(e, "Report", settings);
}


/*
* Funções no js para executar junto com a função do ajax e simular a barra 
* de progresso

 * @returns {undefined} */
function opAnalysis1() {
    $('#btn').attr('disabled', true);
    $('fileBarAnalysis').css('width', 0).attr('aria-valuenow', 0);
    executeAnalysis('opAnalysis1', 150, opAnalysis2);
}
function opAnalysis2() {
    $('#btn').attr('disabled', true);
    executeAnalysis('opAnalysis2', 350, opAnalysis3);
}
function opAnalysis3() {
    $('#btn').attr('disabled', true);
    executeAnalysis('opAnalysis3', 500, opAnalysis4);
}

function opAnalysis4() {
    executeAnalysis('opAnalysis4', 1000, function () {
        $('#btn').attr('disabled', false);


    });
}


/*
* Funções no js para executar junto com a função do ajax e simular a barra 
* de progresso depois que o arquivo é trimmado e filtrado.

 * @returns {undefined} */
function newAnalysisFilter1(){
        //$('#btn').attr('disabled', true);
        $('fileBarAnalysis').css('width', 0).attr('aria-valuenow', 0);
        runNewAnalysisnewFile('newAnalysisFilter1', 150, newAnalysisFilter2);
    }
    function newAnalysisFilter2() {
       // $('#btn').attr('disabled', true);
        runNewAnalysisnewFile('newAnalysisFilter2', 350, newAnalysisFilter3);
    }
    function newAnalysisFilter3() {
       // $('#btn').attr('disabled', true);
        runNewAnalysisnewFile('newAnalysisFilter3', 500, newAnalysisFilter4);
    }

    function newAnalysisFilter4() {
        runNewAnalysisnewFile('newAnalysisFilter4', 1000, function () {
           // $('#btn').attr('disabled', false);
           
           

        });
    }


/*
 * Função ajax para chamar o php e realizar as operações do FastQC, sendo esta
 * a segunda vez que ela é usada devido a geração do novo arquivo no trimmer e
 * no filter.
 * @param {String} operacao
 * @param {int} pValor
 * @param {function} proximo
 * @returns {undefined}
 */

function runNewAnalysisnewFile(operacao,pValor,proximo){
   
    $.ajax({
        async: true,
        url: 'newAnalysis.php',
        data: 'operacao=' + operacao + '&opt=' + opt ,
        success: function (ret) {

       // $('#retorno').html(ret);
            contNewAnalsysis += 1;
            $("#fileBarAnalysis").css('width', pValor).attr('aria-valuenow', pValor);
            if (contNewAnalsysis == 3){
                //alert("retorno do php de new analise" + ret);
                proximo();
                fitList(ret);
                contNewAnalsysis=0;
         
            }

            //}
            proximo();


        }
    });

}

/*
     * Função que manda de volta para a aba de Análise, para executar o fastqc
     * do arquivo modificado pelo trimmer e pelo filter
     * @param {type} e = nome do arquivo modificado 
     * @returns {nome_sem_extensao|nomeArquivoPosTrimmerFilter|getNomeArquivo.nome_sem_extensao}
     */
     function backTabAnalysis(e) {
     //alert("analise" + e);
     $('#tabAnalysis').click();
     $('#jumboTrimmer').removeClass('alert-info');
     $('#jumboAnalysis').addClass('alert-info');
     $('fileBarFilter').css('width', 0).attr('aria-valuenow', 0);
     //$('#saida_analysis').html(e).prepend("Analysis for: ");
     //alert(e);
     $('#saida_analysis').append(e).prepend('Analysis for ');
     newAnalysisFilter1();
 }
 
 
function deletePreviousReportAnalysis(){
    
        deletePreviousReportAndFiles1();
    
    
}

function deletePreviousReportAndFiles1() {
    runDelete('deletePreviousReportAndFiles1', deletePreviousReportAndFiles2);
}
function deletePreviousReportAndFiles2() {
    runDelete('deletePreviousReportAndFiles2', function () {
        

    });
}

function runDelete(operacao,proximo){
   
    $.ajax({
        async: true,
        url: 'deleteFiles.php',
        data: 'operacao=' + operacao,
        success: function (ret) {
                
               //if(contDelete==0){ alert("File Deleted " + temp_nome);}
       // $('#retorno').html(ret);
            contDelete += 1;
           // $("#fileBarAnalysis").css('width', pValor).attr('aria-valuenow', pValor);
            if (contDelete == 2) {
                //alert("Report Deleted " + temp_nome);
                proximo();
                //removeItemList();
                 /*alert("contDelete " + contDelete);
                 alert("contQtdFiles " + contQtdFiles);*/
                 //alert("contQtdFilesAssembly " + contQtdFilesAssembly);
    
                 $("#selectFileAssembler option[value=" + contQtdFilesAssembly + "]").remove();
                 contQtdFilesAssembly--;
                //removeSelectFiles('#selectFileAssembler',contQtdFilesAssembly);
                contDelete=0;
         
          }

            //}
            proximo();


        }
    });

}