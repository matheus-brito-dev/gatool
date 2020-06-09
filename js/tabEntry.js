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


/* código para upload com ajax - 
                        tab entry
                        */
                       //variavel responsável por pegar o nome do arquivo que foi upado
                        var uploadedFileName;
                        //variavel para contar qtd de arquivos nas combos
                        var contQtdFiles = 0;
                        var contQtdFilesAssembly = 0;
                          /* Rotina em js para fazer o upload através do ajax*/ 
                          
                          
                          //init();
                          $('#about').modal();
                          function init(){
                              //$('#btTeste').show();
                             
                              $('#jumboEntry a').removeAttr("href");
                              $('#jumboAnalysis a').removeAttr("href");
                              $('#jumboTrimmer a').removeAttr("href");
                              $('#jumboAssembler a').removeAttr("href");
                              $('#jumboOrientation a').removeAttr("href");
                              
                              //$('#').unbind('mouseenter mouseleave');
                              $('.nav nav-pills li').click(function () {
                                $(this).unbind("mouseenter mouseleave");
                            });
                              
                          }
                          
                          
                        function _(el) {
                            return document.getElementById(el);
                        }

                        function upload_click(e) {
                            e.preventDefault();
                            var file = document.getElementById("file_upload");
                            if (file.value.indexOf('.fastq') === -1) {
                                alert('Only fastq files are allowed.');
                                return;
                                window.location = 'http://localhost/gatool';
                            }
                            $("#btUpload").attr('disabled', true);
                            var formdata = new FormData();
                            formdata.append("file_upload", file.files[0]);
                            formdata.append("MAX_UPLOAD_SIZE", $('#MAX_FILE_SIZE').val());
                            
                            $.ajax({
                                url: 'upload.php',
                                data: formdata,
                                processData: false,
                                contentType: false,
                                type: 'POST',
                                xhr: function () {
                                    var xhr = new window.XMLHttpRequest();
                                    xhr.upload.addEventListener("progress", progressHandler, false);
                                    xhr.addEventListener("progress", progressHandler, false);
                                    return xhr;
                                },
                                success: function () {
                                  
                                    setTimeout(completeHandler, 2000);
                                    
                                }
                            });
                            return false;
                        }

                        function progressHandler(event) {
                            if (event.lengthComputable) {
                                var percent = Math.round((event.loaded / event.total) * 100);
                                $("#fileBar").css('width', percent + '%').attr('aria-valuenow', percent);
                                //console.log(percent);
                            }
                        }

                        function completeHandler(event) {
                           
                            $("#fileBar").css('width', '0%').attr('aria-valuenow', 0);
                            $('#file_upload').html("");
                            // pegando somente o nome do arquivo upado, descartando os diretórios
                            uploadedFileName = $("#file_upload").val().split("\\").pop();
                            
                            /*avançar p outra aba*/
                            fillSelectFiles('#selectFiles',uploadedFileName,contQtdFiles);
                            fillSelectFiles('#selectFileAssembler',uploadedFileName,contQtdFilesAssembly);
                            contQtdFilesAssembly += 1;
                            goTabAnalysis();
                            
                              
                        }

                        function errorHandler(event) {
                            _("status").innerHTML = "Upload Failed";
                        }

                        function abortHandler(event) {
                            _("status").innerHTML = "Upload Aborted";
                        }
                       
                        
                        
                        /* fim do código para upload com ajax - 
                        tab entry
                        */
                      
