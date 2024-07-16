<div id="modalBradescoSaude" class="modal fade" tabindex="-1" role="dialog"
    aria-labelledby="modalLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog" role="document" style="width: 95%;">
        <div class="modal-content">
            
            <div class="modal-header modal-header-bradesco-saude">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <img class="logo-bradesco-saude" src="<?=base_url('uploads/gente_gestao/adm_pessoal/rh_bradescoSaude.jpg');?>"> <br>
            </div>

            <div class="modal-body">
                
                <div class="m-b-40">
                    <h4><?=lang("descricao_link_acesso_bradesco_saude")?></h4>
                    <a class="link-primary" href="https://play.google.com/store/apps/details?id=br.com.bradseg.bscelular&hl=pt_BR" target="_blank">https://play.google.com/store/apps/details?id=br.com.br</a><br>
                    <a class="link-primary" href="https://wwws.bradescosaude.com.br/PCBS-LoginSaude/td/inicioLoginSegurado.do" target="_blank">https://wwws.bradescosaude.com.br/PCBS-LoginSaude/td/inicioLoginSegurado.do</a>
                </div>
                <div class="m-b-40">
                    <h4><?=lang("passo_a_passo_primeiro_acesso_bradesco_saude")?></h4>
                    <a class="link-primary" href="<?=base_url('uploads/gente_gestao/adm_pessoal/bradesco/passoPasso_-_AplicBradescoSeg.pdf');?>"
                        target="_blank"><?=lang("passo_a_passo_aplicativo_bradesco_seguros")?></a><br>
                    <a class="link-primary" href="<?=base_url('uploads/gente_gestao/adm_pessoal/bradesco/passoPassoCartaoVirtBradescoSaudePatria_-_2019_07.pdf');?>"
                        target="_blank"><?=lang("passo_a_passo_cartao_bradesco_seguros")?></a>
                </div>
                
            </div>

            <div class="modal-footer">
                <button type="button" class="btn" data-dismiss="modal"><?=lang("fechar")?></button>
            </div>

        </div>
    </div>
</div>


<script>

    $(document).ready(function()
    {
        $("#modalBradescoSaude").modal();
    });

</script>

<style>
    .logo-bradesco-saude {
        height: 104px;
        width: 150px;
    }
    
    .modal-header-bradesco-saude {
        padding: 15px 25px 0px 25px !important;
    }

</style>