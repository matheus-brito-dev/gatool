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
 * Função para desabilitar e habilitar os componentes
 * @returns {undefined}
 */

var filAssembly,assemblerTool,cutoff,outAssembly,fast,scMode,kmerV,minC;
var contAssembly = 0;
var contador = 0;
var dadosQuast;
var status=-1;

function goTabAssembler(){

    $('#tabAssembler').click();
    
    $('#jumboAnalysis').removeClass('alert-info');
    $('#jumboTrimmer').removeClass('alert-info');
    $('#jumboEntry').removeClass('alert-info');
    $('#jumboAssembler').addClass('alert-info');
    
    $("#fileBarAnalysis").css('width', 0).attr('aria-valuenow', 0);
    $('#selectFiles').val("0");
    $('#selectAssemblerTool').val("");
    $('#selectPhredSeq').val("0");
    $('#selectQualPhred').val("0");
    $('#selectQualSeq').val("0");
    $('#selectTrimSeq').val("0");

}
//ao carregar o documento eu testo logo o chnge do select
$(document).ready(function () {

 var valor;
 var scV,fV;
   

    $('#kmerVelvet').change(function(){
        kmerV = ($(this).val());
    });


    $('#selectAssemblerTool').change(function () {

        valor = $("#selectAssemblerTool option:selected").val();
        if (valor == 2){
            disableCheckBoxes();
            $('#ks span').html("21,31,55,77");
            $('#minContigsBox').fadeIn('slow');
            $('#kmerVelvetBox').fadeIn('slow');
        }
        else {
            $('#ks span').html("21,33,55,77,99,127");
            enableCheckBoxes();
            $('#minContigsBox').fadeOut('slow');
            $('#kmerVelvetBox').fadeOut('slow');
            
        }
    });
    
        
        $('#fastAssembly').change(function(){
            fV = $(this).is(':checked');
                if(fV) $('#ks span').html("21,33,55,77");
                 else $('#ks span').html("21,33,55,77,99,127"); 
    });
        
    $('#scMode').change(function(){
        scV = $(this).is(':checked');
        if(scV) $('#ks span').html("21,33,55");
                 else $('#ks span').html("21,33,55,77,99,127");; 
    });
            
        if(fV && scV) {
            
            $('#ks span').html("21,33,55");
        }else if(fV == true && fV == false) {
               
                $('#ks span').html("21,33,55");
                }else if(fV == false && scV == true){
                        $('#ks span').html("21,33,55");
                        }else{
                            $('#ks span').html("21,33,55,77,99,127");
                        }
  
   
});


//Função para startar a montagem
function initAssembler(e){
    
        e.preventDefault();
        assemblerTool = $('#selectAssemblerTool').val();
        
        if(assemblerTool == 1){
            
        fast = $('#fastAssembly').is(':checked');
        scMode = $("#scMode").is(':checked');
        }
        
        
        //Recuperando dados dos campos do form
        filAssembly = $('#selectFileAssembler').val();
        cutoff = $('#selectCutOff').val();
        outAssembly = $.trim($('#inpOutputFolder').val());
        minC = $.trim($('#minContigs').val());
      
        //label dependente do tipo de arquivo escolhido
        if(filAssembly==1){
            $('#saida_assembler').html(uploadedFileName).prepend('Assembly file: ');
        }else {
                $('#saida_assembler').html(temp_nome).prepend('Assembly file: ');
        }
        
        //executa a montagem
      
       if(assemblerTool==1){ //se a montagem foi feita com spades
        status=1;   
        runAssembler1();
           
       }else if(assemblerTool==2){ //se a montagem foi feita com velvet
        status=2;   
        velvet1();
           
       }
       
     
      
}

function disableCheckBoxes(){
            $('#checkboxes').fadeOut('slow');
  
}
function enableCheckBoxes(){
            $('#checkboxes').fadeIn('slow');

}

/*
 * Função ajax para chamar o php e realizar as operações do 
 * SPAdes(montagem do genoma)
 * @param {String} operacao
 * @param {int} pValor
 * @param {function} proximo
 * @returns {undefined}
 */
 function assemblerProcess(operacao, pValor, proximo) {
        $.ajax({
            async: true,
            url: 'assembler.php',  
            data: 'operacao=' + operacao + '&fa=' + filAssembly + '&co=' + cutoff + '&outA=' + outAssembly + '&fastA=' + fast +  '&sc=' + scMode,
            success: function (ret) {
               // alert(ret);
                contAssembly++;
                contador++;
                //alert(ret);
               //contFilter+=1;
               $("#fileBarAssembler").css('width', pValor).attr('aria-valuenow', pValor);
              
               if(contAssembly==3){
                   
               		proximo();
                        fitListAssembly(ret);
                        contAssembly=0;
                        clearFieldsAssembly();
               }
               if(contador==4){ //contador para pegar o retorno do ultimo metodo no php
                   fitListFileAssemblySpades(ret); //funcao para criar um item da lista e colocar p download
                   contador=0;
               }
               proximo();

            }
        });
    }

/*
 *Funções que vão chamar o php e executar o assembler SPAdes
 * @param {type} e
 * @returns {undefined}
 */
    function runAssembler1() {
        $('#btAssembler').attr('disabled', true);
        assemblerProcess('runAssembler1', 200, runAssembler2);
    }
    
    function runAssembler2() {
    
        assemblerProcess('runAssembler2', 300, runAssembler3);
    }
    
    function runAssembler3() {
    
        assemblerProcess('runAssembler3', 500, runAssembler4);
    }
    
    function runAssembler4() {
            assemblerProcess('runAssembler4', 850, function() {
            $('#btAssembler').attr('disabled', false);
     
        });
    }
    /********************************************************/
    
    /*
 *Funções que vão chamar o php e executar o assembler Velvet
 * @param {type} e
 * @returns {undefined}
 */
    function velvet1() {
            $('#btAssembler').attr('disabled', true);
            //alert('iniciar montagem com velvet');
            velvetAssembler('velvet1', 200, velvet2);
    }
    
    function velvet2() {
    
            velvetAssembler('velvet2', 300, velvet3);
    }
    
    function velvet3() {
    
            velvetAssembler('velvet3', 500, velvet4);
    }
    
    function velvet4() {
            velvetAssembler('velvet4', 850, function() {
            $('#btAssembler').attr('disabled', false);
     
        });
    }
 /********************************************************/
    /*
 * Função ajax para chamar o php e realizar as operações do 
 * Velvet(montagem do genoma)
 * @param {String} operacao
 * @param {int} pValor
 * @param {function} proximo
 * @returns {undefined}
 */
 function velvetAssembler(operacao, pValor, proximo) {
        $.ajax({
            async: true,
            url: 'assembler2.php',
            data: 'operacao=' + operacao + '&fa=' + filAssembly + '&co=' + cutoff + '&outA=' + outAssembly + '&m=' + minC + '&k=' + kmerV,
            success: function (ret) {
               //alert(ret);
                contAssembly++;
                contador++;
                //alert(ret);
               //contFilter+=1;
               $("#fileBarAssembler").css('width', pValor).attr('aria-valuenow', pValor);
              
               if(contAssembly==3){
                   
               		proximo();
                        fitListAssembly(ret);
                        contAssembly=0;
                        clearFieldsAssembly();
               }
               
               if(contador==4){ //contador para pegar o retorno do ultimo metodo no php
                   fitListFileAssembly(ret); //funcao para criar um item da lista e colocar p download
                   contador=0;
               }
               proximo();

            }
        });
    }
 
    function fitListAssembly(e){
    
    //e.preventDefault();
   
    item = $(document.createElement('li'));
    item.addClass('list-group-item');
    
    var link = $(document.createElement('a'));
    
    //<span class="glyphicon glyphicon-download" aria-hidden="true"></span>
    link.html('Assembly Statistics').click(function(){
        openReportPopup(e);
    });
    item.append(link);
    $('#report_itens').append(item);

    //openReportPopup(e);
 
}

function fitListFileAssembly(e){
          
            var array = e.split('/');
       
    
            item = $(document.createElement('li'));
            item.addClass('list-group-item');
    
            var link = $(document.createElement('a'));
    
    //<span class="glyphicon glyphicon-download" aria-hidden="true"></span>
            link.html(array[8]).click(function(){
       
                link.attr('href','/gatool/assemblers/' + array[6] + '/' + array[7] + '/' + array[8]);
                link.attr('download',array[8]);
    });
            item.append(link);
            $('#files_itens').append(item);
    
    
}
function fitListFileAssemblySpades(e){
          
            var array = e.split('/');
       
            
            item = $(document.createElement('li'));
            item.addClass('list-group-item');
    
            var link = $(document.createElement('a'));
    
    //<span class="glyphicon glyphicon-download" aria-hidden="true"></span>
            link.html(array[7]).click(function(){
       
                link.attr('href','/gatool/assemblers/' + array[6] + '/' + array[7]);
                link.attr('download',array[7]);
    });
            item.append(link);
            $('#files_itens').append(item);
    
    
}    
    function clearFieldsAssembly(){
        
        $('#selectFileAssembler').val("0");
        $('#selectCutOff').val("0");
        $('#inpOutputFolder').val("");
       
        goTabOrientation();
        
        
    }

    