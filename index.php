<!DOCTYPE html>
<!--[if IE 8]> 				 <html class="no-js lt-ie9" lang="en" > <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en" > <!--<![endif]-->
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <title>CSV TO XML</title>
    
    <link rel="stylesheet" href="css/style.css" />
  
    <link rel="stylesheet" href="css/foundation.css">
    <link rel="stylesheet" href="css/foundation-icons.css" />
    
    <script src="js/vendor/custom.modernizr.js"></script>
    
    <script src='js/jquery.js'></script>
    <script src="js/scripts.js"></script>
    <script src="js/foundation.min.js"></script>
    <script src="js/jquery.scrollTo.js"></script>
    <script src="js/jquery.nav.js"></script>
  
    <script>
      $(document).foundation();
    </script>
    
    <!--[if lt IE 9]>
    <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
</head>
<body>
    <section class="container">
        
        <div class="row">
            <div class="large-12 columns">
    <?php
    error_reporting();
    
    //Para que funcione tudo normalmente eu instancio duas vari�veis. $download e $xml2
    $download = '';
    $xml2 = false;
    //dentro d� pagina eu verifico se tem arquivo enviado por post ou n�o.
    //Caso tenha entro no if
    if($_FILES){
        //Pego o nome do arquivo, e seto numa vari�vel chamada $nome
        $nome           = $_FILES['arquivo']['name'];
        //E dou um explode para que eu possa pegar s� o nome e s� a extens�o
        $file = explode('.', $nome);
        //E seto cada uma dela numa vari�vel
        $nomeArquivo = $file[0];
        $ext = $file[1];
        //Seto o typo e o temp do arquivo em outras duas variaveis para ter maior organiza��o
        $tipoArquivo    = $_FILES['arquivo']['type'];
        $tmpArquivo     = $_FILES['arquivo']['tmp_name'];
        
        //Ent�o agora eu fa�o outra valida��o para verificar se realmente � um .csv
        if($ext != 'csv'){
            //Caso n�o seja, exibir� essa resposta
            $resposta = "<div data-alert class='alert-box warning'>
                            Arquivo em formato diferente!
                            <a href='#' class='close'>&times;</a>
                        </div>";
        //Caso seja, continuar� com o c�digo normalmente
        }else{
        //Entrando no else, ele far� o uploado do arquivo para uma pasta e com o mesmo nome    
        move_uploaded_file($tmpArquivo, 'csv/'.$nome);
        //Instancio uma variavel recebendo como valor inicial 1 ( Mais na frente explico porque recebe esse valor )
        
        //Pego o arquivo que foi upado e abro ele todo em um array
        $csv = file("csv/".$nome);
        //Instancio uma variavel recebendo como valor inicial 1 ( Mais na frente explico porque recebe esse valor )
        //E criar uma vari�vel $dados vazia
        $dados = "";
        $i = 1;
        //Ent�o realizarei um foreach para ler cada linha desse array
        foreach($csv as $line) {
                //Darei uma explode para cada $line onde irei come�ar a montar o xml
                $valores = explode(';', $line);
                //Essa valida��o feita pois o documento do cliente vinha com um cabe�alho, caso o do seu cliente n�o tenha � s� tirar
                if($valores[0] != 'Product Name'){
                    //para que o xml seja aceito no momento da gera��o de nota fiscal em lote � necess�rio esses dados abaixo
                    //Informando que os valores na posi��o 10(RazaoSocial) e 12(Email) n�o podem ser vazios, logo todos os usu�rios
                    //Que n�o inserir�o esse c�digo, dever� ser retirados do XML
                    if($valores[10] != ""){
                        if($valores[12] != ""){
                //E ent�o eu irei adicionar o valor de $i dentro dessa vari�vel total.
                //Por que eu iniciei a variavel $i com o valor 1. A gera��o dessas notas existe uma posi��o do xml chamado 
                //QuantidadeRps ( voc� encontrar� mais abaixo ) e essa posi��o precisa ter exatamente o mesmo valor que a quantidade total de notas
                //Fiscais que ir�o aparecer na NFL(Nota Fiscal em Lote)
                $total = $i;
                $dados .= '
                        <Rps>
                            <InfRps Id="rps'.$i.'serieT1">
                                <IdentificacaoRps>
                                    <Numero>'.$i.'</Numero>
                                    <Serie>x</Serie>
                                    <Tipo>x</Tipo>
                                </IdentificacaoRps>
                                <DataEmissao>'.date('Y-m-d').'T'.date('H:m:s').'</DataEmissao>
                                <NaturezaOperacao>x</NaturezaOperacao>
                                <OptanteSimplesNacional>x</OptanteSimplesNacional>
                                <IncentivadorCultural>x</IncentivadorCultural>
                                <Status>x</Status>
                                <Servico>
                                    <Valores>
                                        <ValorServicos>'.$valores[5].'</ValorServicos>
                                        <ValorDeducoes>0</ValorDeducoes>
                                        <ValorPis>0.00</ValorPis>
                                        <ValorCofins>0.00</ValorCofins>
                                        <ValorInss>0.00</ValorInss>
                                        <ValorIr>0.00</ValorIr>
                                        <ValorCsll>0.00</ValorCsll>
                                        <IssRetido>x</IssRetido>
                                        <ValorIss>x.xx</ValorIss>
                                        <OutrasRetencoes>0.00</OutrasRetencoes>
                                        <Aliquota>0.0x</Aliquota>
                                        <DescontoIncondicionado>0</DescontoIncondicionado>
                                        <DescontoCondicionado>0</DescontoCondicionado>
                                    </Valores>
                                    <ItemListaServico>xxx</ItemListaServico>
                                    <CodigoTributacaoMunicipio>xxxxxxx</CodigoTributacaoMunicipio>
                                    <Discriminacao>'.utf8_encode($valores[0]).'</Discriminacao>
                                    <CodigoMunicipio>xxxxxxx</CodigoMunicipio>
                                </Servico>
                                <Prestador>
                                    <Cnpj>xxxxxxxxxxxxxx</Cnpj>
                                    <InscricaoMunicipal>xxxxxxxx</InscricaoMunicipal>
                                </Prestador>
                                <Tomador>
                                    <IdentificacaoTomador>
                                        <CpfCnpj>
                                            <Cpf>xxxxxxxxxxx</Cpf>
                                        </CpfCnpj>
                                    </IdentificacaoTomador>
                                    <RazaoSocial>'.utf8_encode($valores[10]).'</RazaoSocial>
                                    <Contato>
                                        <Email>'.$valores[12].'</Email>
                                    </Contato>
                                </Tomador>
                            </InfRps>
                        </Rps>';
                $i++;
            }
                    }
                }
            
        }
        $header = '
            <EnviarLoteRpsEnvio xmlns="http://www.abrasf.org.br/ABRASF/arquivos/nfse.xsd">
                <LoteRps Id="EnvioDeLote">
                    <NumeroLote>x</NumeroLote>
                    <Cnpj>xxxxxxxxxxxxxx</Cnpj>
                    <InscricaoMunicipal>xxxxxxx</InscricaoMunicipal>
                    <QuantidadeRps>'.$total.'</QuantidadeRps>
                    <ListaRps>';

        $footer = '
                    </ListaRps>
                </LoteRps>
            </EnviarLoteRpsEnvio>';

        //Depois defino que o xml ser� essas 3 vari�veis
        $xml = $header.$dados.$footer;

        //Abro/crio uma arquivo na pasta que eu desejar com o nome que eu dejar
        $arquivo = fopen('xml/'.$nomeArquivo.'.xml','w+');
        //gravamos os dados no arquivo.xml
        fwrite($arquivo,$xml);
        //fechamos nosso arquivo
        fclose($arquivo);
        //E define que a vari�vel $xml2 recebe o valor true
        $xml2 = true;
        //E download recebe o nome do arquivo
        $download = $nomeArquivo;
        //E a mensagem � mostrada que o xml foi gerado com sucesso
        $resposta = "<div data-alert class='alert-box success'>
                        XML Gerado com sucesso!
                        <a href='#' class='close'>&times;</a>
                    </div>";                
            }        
    }
    ?>
    <!-- Se não existir um arquivo upado, então entraremos no if -->
    <?php if(empty($download)){ ?>
    
    <form class="large-8 form" method="post" enctype="multipart/form-data" action="">
                
        <div class="large-12 columns text-center">
            <h1>Conversor de CSV para XML</h1>
        </div><!-- .large-12 columns text-center -->
        
        <div class="large-12 columns text-center">
            <?php if(isset($resposta)) echo $resposta;  ?>
        </div><!-- .large-12 columns text-center -->
        
        <input type="file" class="input" name="arquivo">
        <br />
        <br />
        <button class="large-12" type="submit">Enviar</button>
    </form> <!-- .large-8 form -->
    <!-- Caso exista, iremos gerar um link indo para a página download.php com o arquivo -->
    <?php }else{ ?>
                
    <div class="large-8 form clearfix">
                
        <div class="large-12 columns">
            
            <div class="large-12 columns text-center">
                <h1>Conversor de CSV para XML</h1>
            </div><!-- .large-12 columns text-center -->
            
            <div class="row">
                <div class="large-12 columns text-center">
                    <?php if(isset($resposta)) echo $resposta;  ?>
                </div><!-- .large-12 columns text-center -->
                
                <div class="large-6 columns">
                    <a class="link large-12" href="download.php?arquivo=<?php echo $download.'.xml'; ?>">Download</a>
                </div><!-- .large-6 columns -->
                <div class="large-6 columns">
                    <a class="link large-12" href="index.php">Voltar</a>
                </div><!-- .large-6 columns -->
            
            </div><!-- .row -->
    
        </div><!-- .large-12 columns -->
        
    </div><!-- .row -->
    <?php } ?>
                </div><!-- .large-12 colums -->
    
            </div><!-- .row -->
    </section><!-- .container -->
    
</body>
</html>