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
 * Variáveis de sessão criadas aqui na página upload.php
 * $_SESSION['sendFileName'] -> guarda o nome do arquivo enviado
 * $_SESSION['fileName'] -> guarda o nome único para o arquivo enviado
 *  */
session_start(); // início da sessão


// Vetor com erros que podem acontecer no upload
$upload_errors = array(
    
    UPLOAD_ERR_OK => "No errors.",
    UPLOAD_ERR_INI_SIZE => "Larger than upload_max_file_size.",
    UPLOAD_ERR_FORM_SIZE => "Larger than form MAX_FILE_SIZE.",
    UPLOAD_ERR_PARTIAL => "Partial upload.",
    UPLOAD_ERR_NO_FILE => "No file.",
    UPLOAD_ERR_NO_TMP_DIR => "No temporary directory.",
    UPLOAD_ERR_CANT_WRITE => "Can't write to disk.",
    UPLOAD_ERR_EXTENSION => "File upload stopped by extension."
);

// Definindo o tamanh max do arquivo a ser recebido
$max_filesize = 1024 * 5000000000;

// Definindo variavel temporária para guardar o arquivo
$tmp_file = $_FILES['file_upload']["tmp_name"];


//Testando se o arquivo foi realmente setado e salvado ele 
//na variável$target_file
if (isset($_POST['file_upload'])) {
    $target_file = $_POST['file_upload'];
} else {
    $target_file = basename($_FILES["file_upload"]["name"]);
}
//Definindo o diretório onde salvar o arquivo
$upload_dir = "uploads";
//Pegando o tamanho do arquivo enviado
$size = $_FILES['file_upload']['size'];
//Pegando o tipo do arquivo enviado
$fileType = $_FILES["file_upload"]["type"];
//Salvado na sessão o nome do arquivo
$_SESSION['sendFileName'] = $target_file;

// Pega a extensão do arquivo enviado
$ext = strtolower(end(explode('.', $target_file)));
// Atribuindo um único nome para todos os arquivos que forem 
// enviados e concatenando com a extensão
$nome = uniqid() . '.' . $ext;

//Atribuindo a variavel de sessão file_name o nome único definitivo do arquivo
$_SESSION['fileName'] = $nome;

//Testando o tamanho do arquivo e definindo o diretorio 
if ($size <= $max_filesize && $size != 0) {

    $file_dir = $upload_dir . "/" . $nome ;
}

// Testando se o diretorio existe, se não será criado
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}
//Testando se o arquivo temporário não existe e disparando mensagem de erro
if (!$tmp_file) {
    echo "Error: Please browser for a file before clicking the upload button.";
    exit;
}
//movendo arquivo para a pasta(upload)
$status = move_uploaded_file($tmp_file, $file_dir);

if ($status) {
    echo $_SESSION['sendFileName'] ." uploaded successfully";
    //header("Location:fastqc.php?arq=$target_file"); 
} else {
    //$error = $_FILES['file_upload']['error'];
    //echo "Error: $upload_errors[$error]";
    echo "falhou";
}





