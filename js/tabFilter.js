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


var tf0,tf1,tf2,tf3,tf4,opt; //Variáveis com os parametros escolhidos pelo usuário para o trimmer e do filter
var trimmerFilterFileName;// nome do arquivo depois de extrair e concatenar nome correto
var contFilter=0; // variável para contar até o final e jogar na lista o resultado do relatorio
var temp_nome; // variável para auxiliar na captura do nome do arquivo(pós filter e trimmer) sem extensões

/*
* 
*Função para saber se  abre o modal ou nao.
* */
function goChooseModal(result) {
    
    if(result==2){
        
          $('#confirm').modal();
          i=1; //descomentar caso queira apenas dois relatórios, excluir relatorio ao clicar yes
    }else{
        
        goTabTrimmer();
        
    }
    
}

/*
 * Função para ir para a aba de trimmer
 * @returns {undefined}
 */
function goTabTrimmer(){
    
    $('#tabTrimmer').click();
    $('#jumboAnalysis').removeClass('alert-info');
    $('#jumboTrimmer').addClass('alert-info');

    /*Deixando a barra zerada e o nome do arquivo, ao voltar p a outra aba*/
    $("#fileBarAnalysis").css('width', 0).attr('aria-valuenow', 0);
    $("#saida_analysis").html("");
    $('#fileBarFilter').css('width',0).attr('aria-valuenow',0);
    //Restando os componentes da aba trimmer&Filter
    $('#selectFiles').val("");
    $('#selectPhredSeq').val("");
    $('#selectQualPhred').val("");
    $('#selectQualSeq').val("");
    $('#selectTrimSeq').val("");
    
    //chamar uma função para chamar no php outra para deletar os dados das 
    //análises anteriores
 
}

function goTabTrimmerDelete(){
    
    $('#tabTrimmer').click();
    $('#jumboAnalysis').removeClass('alert-info');
    $('#jumboTrimmer').addClass('alert-info');

    /*Deixando a barra zerada e o nome do arquivo, ao voltar p a outra aba*/
    $("#fileBarAnalysis").css('width', 0).attr('aria-valuenow', 0);
    $("#saida_analysis").html("");
    $('#fileBarFilter').css('width',0).attr('aria-valuenow',0);
    //Restando os componentes da aba trimmer&Filter
    $('#selectFiles').val("");
    $('#selectPhredSeq').val("");
    $('#selectQualPhred').val("");
    $('#selectQualSeq').val("");
    $('#selectTrimSeq').val("");
    $('#btnGoAssembly').addClass('disabled',false);
    deletePreviousReportAnalysis();
}



function chooseOpt(e){
    e.preventDefault();
    var optSelect = $("#opt").val();
  
    
    switch(optSelect){
      
        case '0':
            $("#trimmer1").fadeIn('slow');
            $("#trimmer2").fadeIn('slow');
            $("#filter1").fadeIn('slow');
            $("#filter2").fadeIn('slow');
        break;
        
        case '1':
   
            $("#filter1").fadeOut('slow');
            $("#filter2").fadeOut('slow');
            $("#trimmer1").fadeIn('slow');
            $("#trimmer2").fadeIn('slow');
        break;
        
        case '2':
            $("#trimmer1").fadeOut('slow');
            $("#trimmer2").fadeOut('slow');
            $("#filter1").fadeIn('slow');
            $("#filter2").fadeIn('slow');
        break;
        
    }
    
}


/*
 * Função chamada pelo click do botão do formulário de trimmer
 * @param {type} operacao
 * @param {type} pValor
 * @param {type} proximo
 * @returns {undefined}
 */
function filter_click(e){

		e.preventDefault();

		$('#btFilter').attr('disabled', true);
		$('#btnGoAssembly').addClass('disabled',false);
                opt = $('#opt').val();
                tf0 = $('#selectFiles').val();
		
                if(opt==0){
                    
                    tf1 = $('#selectPhredSeq').val();
                    tf2 = $('#selectTrimSeq').val();
                    tf3 = $('#selectQualPhred').val();
                    tf4 = $('#selectQualSeq').val();
                    //alert(tf1);
                    op1FilterAndTrimmer();
                    }else if(opt==1){
                        tf1 = $('#selectPhredSeq').val();
                        tf2 = $('#selectTrimSeq').val();
                        //alert(tf1);
                        trimmer1();
                    }else{
                        tf3 = $('#selectQualPhred').val();
                        tf4 = $('#selectQualSeq').val();
                        filter1();
                    }

}

/*
 *Funções que vão chamar o php e executar o trimmer
 * @param {type} e
 * @returns {undefined}
 */
    function trimmer1() {
        //$('#btFilter').attr('disabled', true);
      
        executarTrimmer('trimmer1', 250, trimmer2);
    }
    
    function trimmer2() {
        executarTrimmer('trimmer2', 650, function() {
            $('#btFilter').attr('disabled', false);
            	$('#btnGoAssembly').removeClass('disabled',true);
     
        });
    }
  
  
  function filter1() {
        //$('#btFilter').attr('disabled', true);
      
        executarFilter('filter1', 250, filter2);
    }
    
    function filter2() {
        executarFilter('filter2', 650, function() {
            $('#btFilter').attr('disabled', false);
            	$('#btnGoAssembly').removeClass('disabled',true);
     
        });
    }
  


/*
 * Função ajax para chamar o php e realizar as operações do 
 * Fast-x(trimmer )
 * @param {String} operacao
 * @param {int} pValor
 * @param {function} proximo
 * @returns {undefined}
 */
 function executarTrimmer(operacao, pValor, proximo) {
        $.ajax({
            async: true,
            url: 'trimmer.php', 
            data: 'operacao=' + operacao + '&n0=' + tf0 + '&n1=' + tf1 + '&n2=' + tf2 ,   
            success: function (ret) {
              
               contFilter+=1;
               $("#fileBarFilter").css('width', pValor).attr('aria-valuenow', pValor);

               if(contFilter==2){
                        
                        temp_nome = getFileName(ret,opt);
               		proximo();
                        //alert("contQtdFilesAssembly " + contQtdFilesAssembly);
                        fitListFilter(ret);
                        fillSelectFiles('#selectFileAssembler',temp_nome,contQtdFilesAssembly); //preenche com os dois arquivos trimmados, mesmo tendo deletado algum deles
                        contQtdFilesAssembly+=1;
                        backTabAnalysis(temp_nome);
                        contFilter=0;

               }
               proximo();

            }
        });
    }




/*
 * Função ajax para chamar o php e realizar as operações do 
 * Fast-x(trimmer )
 * @param {String} operacao
 * @param {int} pValor
 * @param {function} proximo
 * @returns {undefined}
 */
 function executarFilter(operacao, pValor, proximo) {
        $.ajax({
            async: true,
            url: 'filter.php', 
            data: 'operacao=' + operacao + '&n0=' + tf0 + '&n3=' + tf3 + '&n4=' + tf4 ,   
            success: function (ret) {
              
               contFilter+=1;
               $("#fileBarFilter").css('width', pValor).attr('aria-valuenow', pValor);

               if(contFilter==2){
                        
                        temp_nome = getFileName(ret,opt);
               		proximo();
                        //alert("contQtdFilesAssembly " + contQtdFilesAssembly);
                        fitListFilter(ret);
                        fillSelectFiles('#selectFileAssembler',temp_nome,contQtdFilesAssembly); //preenche com os dois arquivos trimmados, mesmo tendo deletado algum deles
                        contQtdFilesAssembly+=1;
                        backTabAnalysis(temp_nome);
                        contFilter=0;

               }
               proximo();

            }
        });
    }


/*
 * Função ajax para chamar o php e realizar as operações do 
 * Fast-x(trimmer e filter)
 * @param {String} operacao
 * @param {int} pValor
 * @param {function} proximo
 * @returns {undefined}
 */
 function executarFilterAndTrimmer(operacao, pValor, proximo) {
        $.ajax({
            async: true,
            url: 'fastx_process.php', 
            data: 'operacao=' + operacao + '&n0=' + tf0 + '&n1=' + tf1 + '&n2=' + tf2 + '&n3=' + tf3 + '&n4=' + tf4 ,   
            success: function (ret) {
              
               contFilter+=1;
               $("#fileBarFilter").css('width', pValor).attr('aria-valuenow', pValor);

               if(contFilter==2){
                        
                        temp_nome = getFileName(ret,opt);
               		proximo();
                        //alert("contQtdFilesAssembly " + contQtdFilesAssembly);
                        fitListFilter(ret);
                        fillSelectFiles('#selectFileAssembler',temp_nome,contQtdFilesAssembly); //preenche com os dois arquivos trimmados, mesmo tendo deletado algum deles
                        contQtdFilesAssembly+=1;
                        backTabAnalysis(temp_nome);
                        contFilter=0;

               }
               proximo();

            }
        });
    }

/*
 *Funções que vão chamar o php e executar o filter e o trimmer
 * @param {type} e
 * @returns {undefined}
 */
    function op1FilterAndTrimmer() {
        //$('#btFilter').attr('disabled', true);
      
        executarFilterAndTrimmer('op1FilterAndTrimmer', 250, op2FilterAndTrimmer);
    }
    
    function op2FilterAndTrimmer() {
        executarFilterAndTrimmer('op2FilterAndTrimmer', 650, function() {
            $('#btFilter').attr('disabled', false);
            	$('#btnGoAssembly').removeClass('disabled',true);
     
        });
    }
    

 /*
  * Função para extrair nome do arquivo sem extenões e concatenando para deixar
  * no formato desejado.
  * @param {type} e
  * @returns {nome_sem_extensao|nomeArquivoPosTrimmerFilter|getNomeArquivo.nome_sem_extensao}
  */
 function getFileName(e,f){
    console.log("getFilename " + e);
    
    
        if(opt == '0'){
           
            var outExtName = uploadedFileName.replace(/.\w+$/,"");
            trimmerFilterFileName = outExtName + e.substring(51,13); //myData_t40_w50_q20_p80_trimming_filter
            
            return trimmerFilterFileName;
    }else if(opt == '1'){
            var outExtName = uploadedFileName.replace(/.\w+$/,"");
            trimmerFilterFileName = outExtName + e.substring(40,13); //myData_t40_w50_q20_p80_trimming_filter
            console.log("nome cortado" + trimmerFilterFileName);
            return trimmerFilterFileName;
    }else{
         
            var outExtName = uploadedFileName.replace(/.\w+$/,"");
            trimmerFilterFileName = outExtName + e.substring(40,13); //myData_t40_w50_q20_p80_trimming_filter
            console.log("nome cortado" + trimmerFilterFileName);
            return trimmerFilterFileName;
    }
 }
 

 
 function fillSelectFiles(comp,file,n) {
    
    n  = n + 1;
    var opt1 = document.createElement("option");
    opt1.value = n;
    opt1.text = file;
    $(comp).append(opt1);
    
}

function removeSelectFiles(comp,value) {
    
    $(comp + " option[value=" + value + "]").remove();
}
    


function fitListFilter(e){
    //console.log(e);
    //e.preventDefault();
   
    item = $(document.createElement('li'));
    item.addClass('list-group-item');
    
    var link = $(document.createElement('a'));
    
    //<span class="glyphicon glyphicon-download" aria-hidden="true"></span>
    link.html(e).click(function(){
       
         link.attr('href','/gatool/trimFiles/' + e);
         link.attr('download',e);
    });
    item.append(link);
    $('#files_itens').append(item);
    //openReportPopup(e);
    
    
}