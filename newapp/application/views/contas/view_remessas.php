<style>
    .overlay {
        position: fixed;
        width: 100%;
        height: 100%;
        left: 0;
        top: 0;
        background: rgba(70,20,15,0.3);
        z-index: 2;
        background-image: url(<?=base_url()?>media/img/loading2.gif);
        background-repeat: no-repeat;
        background-position: center center;
        background-size: 100px;
    }
</style>
<h3 style="color: #7c7c7c;">Remessas - Show Tecnologia</h3>
<hr>
<div class="row-fluid">
    <ul class="nav nav-tabs">
        <li class="active"><a href="#salario-tab" data-toggle="tab">Remessa de salários</a></li>
        <li><a href="#first-tab" data-toggle="tab">Remessa de instaladores</a></li>
        <li><a href="#second-tab" data-toggle="tab">Retorno de instaladores com erros</a></li>
        <li><a href="#three-tab" data-toggle="tab">Remessa de fornecedores</a></li>
        <li><a href="#titulo-tab" data-toggle="tab">Remessa de títulos</a></li>
        <li><a href="#guia-tab" data-toggle="tab">Remessa de guias</a></li>
    </ul>

    <div class="tab-content">
        <div class="tab-pane active in" id="salario-tab">
            <button class="btn btn-success" onclick="marcar_tudo()">Marcar tudo</button>
            <button class="btn btn-success" onclick="gerar_remessa_salario(1)">Gerar remessa</button>
            <div>
                <table id="salarios" style="width:100%">
                    <thead>
                    <tr>
                        <th style="width:2px"></th>
                        <th style="width:25px">#</th>
                        <th>Vencimento</th>
                        <th>Funcionário</th>
                        <th>Valor</th>
                    </tr>
                    </thread>
                    <tbody id="salarios_pendentes">
                    </tbody>
                </table>
            </div>
        </div>

        <div class="tab-pane" id="first-tab">
            <label>Data de pagamento</label>
            <input name="data_pagamento_tecnico1" id="data_pagamento_tecnico1" type="date">
            <button class="btn btn-success" onclick="gerar_remessa(1)" style="margin-top: -10px;">Gerar</button>
            <div>
                <table id="instaladores" style="width:100%">
                    <thead>
                        <tr>
                            <th style="width:2px"></th>
                            <th style="width:25px">#</th>
                            <th>Fornecedor</th>
                            <th>Valor</th>
                            <th style="width:90px">Descontar TED</th>
                        </tr>
                    </thread>
                    <tbody id="instaladores_pendentes">
                    </tbody>
                </table>
            </div>
        </div>


        <div class="tab-pane" id="second-tab">
            <label>Data de pagamento</label>
            <input name="data_pagamento_tecnico2" id="data_pagamento_tecnico2" type="date">
            <button class="btn btn-success" onclick="gerar_remessa(2)" style="margin-top: -10px;">Gerar</button>
            <div>
                <table id="erros" style="width:100%">
                    <thead>
                        <tr>
                            <th style="width:2px"></th>
                            <th style="width:25px">#</th>
                            <th>Fornecedor</th>
                            <th>Valor</th>
                            <th style="width:90px">Descontar TED</th>
                            <th style="width:90px">Erro</th>
                        </tr>
                    </thread>
                    <tbody id="erros_pendentes">
                    </tbody>
                </table>
            </div>
        </div>


        <div class="tab-pane" id="three-tab">
            <label>Data de pagamento</label>
            <input name="data_pagamento_fornecedor1" id="data_pagamento_fornecedor1" type="date">
            <button class="btn btn-success" onclick="gerar_remessa_fornecedor(1)" style="margin-top: -10px;">Gerar</button>
            <div>
                <table id="fornecedores" style="width:100%">
                    <thead>
                        <tr>
                            <th style="width:2px"></th>
                            <th style="width:25px">#</th>
                            <th>Fornecedor</th>
                            <th>Valor</th>
                            <th style="width:90px">Descontar TED</th>
                        </tr>
                    </thread>
                    <tbody id="fornecedores_pendentes">
                    </tbody>
                </table>
            </div>
        </div>

        <div class="tab-pane" id="titulo-tab">
            <label>Data de pagamento</label>
            <input name="data_pagamento_titulo" id="data_pagamento_titulo" type="date">
            <button class="btn btn-success" onclick="gerar_remessa_titulo(1)" style="margin-top: -10px;">Gerar</button>
            <div>
                <table id="titulo" style="width:100%">
                    <thead>
                        <tr>
                            <th style="width:2px"></th>
                            <th style="width:25px">#</th>
                            <th>Fornecedor</th>
                            <th>Valor</th>
                        </tr>
                    </thread>
                    <tbody id="titulos_pendentes">
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="tab-pane" id="guia-tab">
            <label>Data de pagamento</label>
            <input name="data_pagamento_guia" id="data_pagamento_guia" type="date">
            <button class="btn btn-success" onclick="gerar_remessa_guia(1)" style="margin-top: -10px;">Gerar</button>
            <div>
                <table id="guia" style="width:100%">
                    <thead>
                        <tr>
                            <th style="width:2px"></th>
                            <th style="width:25px">#</th>
                            <th>Fornecedor</th>
                            <th>Valor</th>
                        </tr>
                    </thread>
                    <tbody id="guias_pendentes">
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="load" style="display:none;" class="overlay"></div>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
    <script type="text/javascript" language="javascript" src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" language="javascript" src="https://code.jquery.com/ui/1.12.1/jquery-ui.js "></script>
    <script type="text/javascript" language="javascript" src="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css"></script>
    <script src="https://npmcdn.com/chart.js@2.7.2/dist/Chart.bundle.js"></script>
    <script>
        var today = new Date();
        var newdate = new Date();
        newdate.setDate(today.getDate()+1);
        var newdate1 = new Date();
        newdate1.setDate(today.getDate());
        var newdate2 = new Date();
        newdate2.setDate(today.getDate()-1);
        document.getElementsByName("data_pagamento_tecnico1")[0].setAttribute('min', newdate1.toISOString().split('T')[0]);
        document.getElementsByName("data_pagamento_tecnico2")[0].setAttribute('min', newdate1.toISOString().split('T')[0]);
        document.getElementsByName("data_pagamento_fornecedor1")[0].setAttribute('min', newdate1.toISOString().split('T')[0]);
        document.getElementsByName("data_pagamento_titulo")[0].setAttribute('min', newdate2.toISOString().split('T')[0]);
        document.getElementsByName("data_pagamento_guia")[0].setAttribute('min', newdate.toISOString().split('T')[0]);
    </script>
    <script>
        var table_instaladores=false;
        var table_salarios=false;
        var table_fornecedores=false;
        var table_titulo=false;
        var table_guia=false;
        var table_erros=false;
        
        function get_salarios_pendentes(){
            $('#salarios_pendentes')[0].innerHTML="";
            document.getElementById("load").style.display=null;
            $.getJSON('<?=base_url()?>index.php/api/get_salarios_pendentes<?php if($this->input->get("empresa")){echo "?empresa=".$this->input->get("empresa");}else{echo "?empresa=1"; }?>',function (data){
                var salarios=[];
                data.forEach(function write(d,index){
                    salarios.push(['<input type="checkbox" name="id_contas_instaladores[]" value="'+d.conta_id+'"></input>','#'+d.conta_id,d.data_vencimento,'<span title="CPF/CPNJ: '+d.cpf+', Banco: '+d.banco+', Agência: '+d.agencia+', Conta: '+d.conta+'">'+d.fornecedor+'</span>','R$ '+d.valor]);
                });
                if(table_salarios){
                    table_salarios.clear();
                    table_salarios.rows.add(salarios);
                    table_salarios.draw();
                }
                else{
                    table_salarios=$('#salarios').DataTable( {
                        data: salarios,
                        "scrollCollapse": true,
                        "paging":         false,
                        "order": [[ 1, "asc" ]]
                    } );
                }
                //var template = ['<tr title="Banco: '+d.banco+', Agência: '+d.agencia+', Conta: '+d.conta+'"><td><input type="checkbox" name="id_contas_instaladores[]" value="'+d.conta_id+'"></input></td>'+'<td>'+d.conta_id+'</td><td>'+d.fornecedor+'</td><td>R$ '+d.valor+'</td></tr>'].join('');
                //$('#instaladores_pendentes').append(template);
                document.getElementById("load").style.display='none';
            });
        }

        function get_intaladores_pendentes(){
            $('#instaladores_pendentes')[0].innerHTML="";
            document.getElementById("load").style.display=null;
            $.getJSON('<?=base_url()?>index.php/api/get_instaladores_pendentes<?php if($this->input->get("empresa")){echo "?empresa=".$this->input->get("empresa");}else{echo "?empresa=1"; }?>',function (data){
                var instaladores=[];
                data.forEach(function write(d,index){
                    if(d.banco=="001"){
                        instaladores.push(['<input type="checkbox" name="id_contas_instaladores[]" value="'+d.conta_id+'"></input>','#'+d.conta_id,'<span title="CPF/CPNJ: '+d.cpf+', Banco: '+d.banco+', Agência: '+d.agencia+', Conta: '+d.conta+'">'+d.fornecedor+'</span>','R$ '+d.valor,'']);
                    }
                    else{
                        instaladores.push(['<input type="checkbox" name="id_contas_instaladores[]" value="'+d.conta_id+'"></input>','#'+d.conta_id,'<span title="CPF/CPNJ: '+d.cpf+', Banco: '+d.banco+', Agência: '+d.agencia+', Conta: '+d.conta+'">'+d.fornecedor+'</span>','R$ '+d.valor,'<input type="checkbox" name="descontar_id_contas_instaladores[]" checked value="'+d.conta_id+'"></input>']);
                    }
                });
                if(table_instaladores){
                    table_instaladores.clear();
                    table_instaladores.rows.add(instaladores);
                    table_instaladores.draw();
                }
                else{
                    table_instaladores=$('#instaladores').DataTable( {
                        data: instaladores,
                        "scrollCollapse": true,
                        "paging":         false,
                        "order": [[ 1, "asc" ]]
                    } );
                }
                //var template = ['<tr title="Banco: '+d.banco+', Agência: '+d.agencia+', Conta: '+d.conta+'"><td><input type="checkbox" name="id_contas_instaladores[]" value="'+d.conta_id+'"></input></td>'+'<td>'+d.conta_id+'</td><td>'+d.fornecedor+'</td><td>R$ '+d.valor+'</td></tr>'].join('');
                //$('#instaladores_pendentes').append(template);
                document.getElementById("load").style.display='none';
            });
        }
        function get_fornecedores_pendentes(){
            $('#fornecedores_pendentes')[0].innerHTML="";
            document.getElementById("load").style.display=null;
            $.getJSON('<?=base_url()?>index.php/api/get_fornecedores_pendentes<?php if($this->input->get("empresa")){echo "?empresa=".$this->input->get("empresa");}else{echo "?empresa=1"; }?>',function (data){
                var fornecedores=[];
                data.forEach(function write(d,index){
                    if(d.banco=="001"){
                        fornecedores.push(['<input type="checkbox" name="id_contas_fornecedores[]" value="'+d.conta_id+'"></input>','#'+d.conta_id,'<span title="CPF/CPNJ: '+d.cpf+', Banco: '+d.banco+', Agência: '+d.agencia+', Conta: '+d.conta+'">'+d.fornecedor+'</span>','R$ '+d.valor,'']);
                    }
                    else{
                        fornecedores.push(['<input type="checkbox" name="id_contas_fornecedores[]" value="'+d.conta_id+'"></input>','#'+d.conta_id,'<span title="CPF/CPNJ: '+d.cpf+', Banco: '+d.banco+', Agência: '+d.agencia+', Conta: '+d.conta+'">'+d.fornecedor+'</span>','R$ '+d.valor,'<input type="checkbox" name="descontar_id_contas_fornecedores[]" checked value="'+d.conta_id+'"></input>']);
                    }
                });
                if(table_fornecedores){
                    table_fornecedores.clear();
                    table_fornecedores.rows.add(fornecedores);
                    table_fornecedores.draw();
                }
                else{
                    table_fornecedores=$('#fornecedores').DataTable( {
                        data: fornecedores,
                        "scrollCollapse": true,
                        "paging":         false,
                        "order": [[ 1, "asc" ]]
                    } );
                }
                //var template = ['<tr title="Banco: '+d.banco+', Agência: '+d.agencia+', Conta: '+d.conta+'"><td><input type="checkbox" name="id_contas_instaladores[]" value="'+d.conta_id+'"></input></td>'+'<td>'+d.conta_id+'</td><td>'+d.fornecedor+'</td><td>R$ '+d.valor+'</td></tr>'].join('');
                //$('#instaladores_pendentes').append(template);
                document.getElementById("load").style.display='none';
            });
        }
        function get_remessas_titulo(){
            $('#titulos_pendentes')[0].innerHTML="";
            document.getElementById("load").style.display=null;
            $.getJSON('<?=base_url()?>index.php/api/get_titulo_pendentes<?php if($this->input->get("empresa")){echo "?empresa=".$this->input->get("empresa");}else{echo "?empresa=1"; }?>',function (data){
                var titulos=[];
                data.forEach(function write(d,index){
                    titulos.push(['<input type="checkbox" name="id_contas_titulos[]" value="'+d.conta_id+'"></input>','#'+d.conta_id,'<span title="Código de barras: '+d.codigo_barra+'">'+d.fornecedor+'</span>','R$ '+d.valor]);
                });
                if(table_titulo){
                    table_titulo.clear();
                    table_titulo.rows.add(titulos);
                    table_titulo.draw();
                }
                else{
                    table_titulo=$('#titulo').DataTable( {
                        data: titulos,
                        "scrollCollapse": true,
                        "paging":         false,
                        "order": [[ 1, "asc" ]]
                    } );
                }
                //var template = ['<tr title="Banco: '+d.banco+', Agência: '+d.agencia+', Conta: '+d.conta+'"><td><input type="checkbox" name="id_contas_instaladores[]" value="'+d.conta_id+'"></input></td>'+'<td>'+d.conta_id+'</td><td>'+d.fornecedor+'</td><td>R$ '+d.valor+'</td></tr>'].join('');
                //$('#instaladores_pendentes').append(template);
                document.getElementById("load").style.display='none';
            });
        }
        function get_remessas_guia(){
            $('#guias_pendentes')[0].innerHTML="";
            document.getElementById("load").style.display=null;
            $.getJSON('<?=base_url()?>index.php/api/get_guia_pendentes<?php if($this->input->get("empresa")){echo "?empresa=".$this->input->get("empresa");}else{echo "?empresa=1"; }?>',function (data){
                var guias=[];
                data.forEach(function write(d,index){
                    guias.push(['<input type="checkbox" name="id_contas_guia[]" value="'+d.conta_id+'"></input>','#'+d.conta_id,'<span title="Código de barras: '+d.codigo_barra+'">'+d.fornecedor+'</span>','R$ '+d.valor]);
                });
                if(table_guia){
                    table_guia.clear();
                    table_guia.rows.add(guias);
                    table_guia.draw();
                }
                else{
                    table_guia=$('#guia').DataTable( {
                        data: guias,
                        "scrollCollapse": true,
                        "paging":         false,
                        "order": [[ 1, "asc" ]]
                    } );
                }
                //var template = ['<tr title="Banco: '+d.banco+', Agência: '+d.agencia+', Conta: '+d.conta+'"><td><input type="checkbox" name="id_contas_instaladores[]" value="'+d.conta_id+'"></input></td>'+'<td>'+d.conta_id+'</td><td>'+d.fornecedor+'</td><td>R$ '+d.valor+'</td></tr>'].join('');
                //$('#instaladores_pendentes').append(template);
                document.getElementById("load").style.display='none';
            });
        }
        function get_remessas_erros(){
            $('#erros_pendentes')[0].innerHTML="";
            document.getElementById("load").style.display=null;
            $.getJSON('<?=base_url()?>index.php/api/get_remessa_erros<?php if($this->input->get("empresa")){echo "?empresa=".$this->input->get("empresa");}else{echo "?empresa=1"; }?>',function (data){
                var erros=[];
                data.forEach(function write(d,index){
                    if(d.banco=="001"){
                        erros.push(['<input type="checkbox" name="id_contas_instaladores[]" value="'+d.conta_id+'"></input>','#'+d.conta_id,'<span title="CPF/CPNJ: '+d.cpf+', Banco: '+d.banco+', Agência: '+d.agencia+', Conta: '+d.conta+'">'+d.fornecedor+'</span>','R$ '+d.valor,'',d.status_pagamento]);
                    }
                    else{
                        erros.push(['<input type="checkbox" name="id_contas_instaladores[]" value="'+d.conta_id+'"></input>','#'+d.conta_id,'<span title="CPF/CPNJ: '+d.cpf+', Banco: '+d.banco+', Agência: '+d.agencia+', Conta: '+d.conta+'">'+d.fornecedor+'</span>','R$ '+d.valor,'<input type="checkbox" name="descontar_id_contas_instaladores[]" checked value="'+d.conta_id+'"></input>',d.status_pagamento]);
                    }
                });
                if(table_erros){
                    table_erros.clear();
                    table_erros.rows.add(erros);
                    table_erros.draw();
                }
                else{
                    table_erros=$('#erros').DataTable( {
                        data: erros,
                        "scrollCollapse": true,
                        "paging":         false,
                        "order": [[ 1, "asc" ]]
                    } );
                }
                //var template = ['<tr title="Banco: '+d.banco+', Agência: '+d.agencia+', Conta: '+d.conta+'"><td><input type="checkbox" name="id_contas_instaladores[]" value="'+d.conta_id+'"></input></td>'+'<td>'+d.conta_id+'</td><td>'+d.fornecedor+'</td><td>R$ '+d.valor+'</td></tr>'].join('');
                //$('#instaladores_pendentes').append(template);
                document.getElementById("load").style.display='none';
            });
        }

        function gerar_remessa_salario(guia){

            document.getElementById("load").style.display=null;
            $.ajax({
                type: "POST",
                url: "<?=base_url()?>index.php/api/get_remessa_salario<?php if($this->input->get("empresa")){echo "?empresa=".$this->input->get("empresa");} else {echo "?empresa=1";}?>",
                data: $('input').serialize(),
                success: function (data){
                    data = JSON.parse(data);
                    var element = document.createElement('a');
                    element.setAttribute('href', 'data:text/plain;charset=utf-8,' + encodeURIComponent(data.arquivo));
                    element.setAttribute('download', data.nome);
                    element.style.display = 'none';
                    document.body.appendChild(element);
                    element.click();
                    document.body.removeChild(element);
                    document.getElementById("load").style.display='none';
                }
            });
        }

        function gerar_remessa(guia){
            $continua = false;
            if(!$('#data_pagamento_tecnico'+guia)[0].value){
                alert("Preencha a data de pagamento");
                return;
            }
            if($("#data_pagamento_tecnico"+guia)[0].value<$("#data_pagamento_tecnico"+guia)[0].min){
                alert("Data de pagamento inválida");
                return;
            }
            $.each(document.getElementsByName('id_contas_instaladores[]'),  function (index,data){
                if(data.checked){
                    $continua = true;
                }
            });
            if(!$continua){
                alert("Nenhuma conta marcada");
                return;
            }
            console.log($('input[type="checkbox"][name="id_contas_instaladores[]"]:checked').serialize())
            document.getElementById("load").style.display=null;
            $.ajax({
                type: "POST",
                url: "<?=base_url()?>index.php/api/get_remessa_instaladores<?php if($this->input->get("empresa")){echo "?empresa=".$this->input->get("empresa");}else{echo "?empresa=1";}?>",
                data: $('input').serialize()+"&data_pagamento_tecnico="+$('#data_pagamento_tecnico'+guia)[0].value,
                success: function (data){
                    data = JSON.parse(data);
                    var element = document.createElement('a');
                    element.setAttribute('href', 'data:text/plain;charset=utf-8,' + encodeURIComponent(data.arquivo));
                    element.setAttribute('download', data.nome);
                    element.style.display = 'none';
                    document.body.appendChild(element);
                    element.click();
                    document.body.removeChild(element);
                    document.getElementById("load").style.display='none';
                }
            });
        }

        function gerar_remessa_fornecedor(guia){
            $continua = false;
            if(!$('#data_pagamento_fornecedor'+guia)[0].value){
                alert("Preencha a data de pagamento");
                return;
            }
            if($("#data_pagamento_fornecedor"+guia)[0].value<$("#data_pagamento_fornecedor"+guia)[0].min){
                alert("Data de pagamento inválida");
                return;
            }
            $.each(document.getElementsByName('id_contas_fornecedores[]'),  function (index,data){
                if(data.checked){
                    $continua = true;
                }
            });
            if(!$continua){
                alert("Nenhuma conta marcada");
                return;
            }
            document.getElementById("load").style.display=null;
            $.ajax({
                type: "POST",
                url: "<?=base_url()?>index.php/api/get_remessa_fornecedores<?php if($this->input->get("empresa")){echo "?empresa=".$this->input->get("empresa");}else{echo "?empresa=1";}?>",
                data: $('input').serialize()+"&data_pagamento_fornecedor="+$('#data_pagamento_fornecedor'+guia)[0].value,
                success: function (data){
                    data = JSON.parse(data);
                    var element = document.createElement('a');
                    element.setAttribute('href', 'data:text/plain;charset=utf-8,' + encodeURIComponent(data.arquivo));
                    element.setAttribute('download', data.nome);
                    element.style.display = 'none';
                    document.body.appendChild(element);
                    element.click();
                    document.body.removeChild(element);
                    document.getElementById("load").style.display='none';
                }
            });
        }
        function gerar_remessa_titulo(guia){
            $continua = false;
            if(!$('#data_pagamento_titulo')[0].value){
                alert("Preencha a data de pagamento");
                return;
            }
            if($("#data_pagamento_titulo")[0].value<$("#data_pagamento_titulo")[0].min){
                alert("Data de pagamento inválida");
                return;
            }
            $.each(document.getElementsByName('id_contas_titulos[]'),  function (index,data){
                if(data.checked){
                    $continua = true;
                }
            });
            if(!$continua){
                alert("Nenhuma conta marcada");
                return;
            }
            document.getElementById("load").style.display=null;
            $.ajax({
                type: "POST",
                url: "<?=base_url()?>index.php/api/get_remessa_boleto<?php if($this->input->get("empresa")){echo "?empresa=".$this->input->get("empresa");}else{echo "?empresa=1";}?>",
                data: $('input').serialize()+"&data_pagamento_titulo="+$('#data_pagamento_titulo')[0].value,
                success: function (data){
                    data = JSON.parse(data);
                    var element = document.createElement('a');
                    element.setAttribute('href', 'data:text/plain;charset=utf-8,' + encodeURIComponent(data.arquivo));
                    element.setAttribute('download', data.nome);
                    element.style.display = 'none';
                    document.body.appendChild(element);
                    element.click();
                    document.body.removeChild(element);
                    document.getElementById("load").style.display='none';
                }
            });
        }
        function gerar_remessa_guia(guia){
            $continua = false;
            if(!$('#data_pagamento_guia')[0].value){
                alert("Preencha a data de pagamento");
                return;
            }
            if($("#data_pagamento_guia")[0].value<$("#data_pagamento_guia")[0].min){
                alert("Data de pagamento inválida");
                return;
            }
            $.each(document.getElementsByName('id_contas_guia[]'),  function (index,data){
                if(data.checked){
                    $continua = true;
                }
            });
            if(!$continua){
                alert("Nenhuma conta marcada");
                return;
            }
            document.getElementById("load").style.display=null;
            $.ajax({
                type: "POST",
                url: "<?=base_url()?>index.php/api/get_remessa_boleto?guia=1<?php if($this->input->get("empresa")){echo "&empresa=".$this->input->get("empresa");}else{echo "?empresa=1";}?>",
                data: $('input').serialize()+"&data_pagamento_guia="+$('#data_pagamento_guia')[0].value,
                success: function (data){
                    data = JSON.parse(data);
                    var element = document.createElement('a');
                    element.setAttribute('href', 'data:text/plain;charset=utf-8,' + encodeURIComponent(data.arquivo));
                    element.setAttribute('download', data.nome);
                    element.style.display = 'none';
                    document.body.appendChild(element);
                    element.click();
                    document.body.removeChild(element);
                    document.getElementById("load").style.display='none';
                }
            });
        }
        get_intaladores_pendentes();
        get_salarios_pendentes();
        get_fornecedores_pendentes();
        get_remessas_erros();
        get_remessas_guia();
        get_remessas_titulo();

        function marcar_tudo(source) {
            checkboxes = document.getElementsByName('id_contas_instaladores[]');
            for(var i=0, n=checkboxes.length;i<n;i++) {
                checkboxes[i].checked = true;
            }
        }
    </script>
</div>
