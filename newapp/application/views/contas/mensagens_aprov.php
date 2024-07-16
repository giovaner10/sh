<style>
a.anexo{
    color: white !important;
}
.bootstrap-filestyle {
    display: none !important;
}
.container{max-width:1170px; margin:auto;}
img{ max-width:100%;}
.inbox_people {
  background: #f8f8f8 none repeat scroll 0 0;
  float: left;
  overflow: hidden;
  width: 40%; border-right:1px solid #c4c4c4;
}
.inbox_msg {
  border: 1px solid #c4c4c4;
  clear: both;
  overflow: hidden;
}
.top_spac{ margin: 20px 0 0;}


.recent_heading {float: left; width:40%;}
.srch_bar {
  display: inline-block;
  text-align: right;
  width: 60%; padding:
}
.headind_srch{ padding:10px 29px 10px 20px; overflow:hidden; border-bottom:1px solid #c4c4c4;}

.recent_heading h4 {
  color: #05728f;
  font-size: 21px;
  margin: auto;
}
.srch_bar input{ border:1px solid #cdcdcd; border-width:0 0 1px 0; width:80%; padding:2px 0 4px 6px; background:none;}
.srch_bar .input-group-addon button {
  background: rgba(0, 0, 0, 0) none repeat scroll 0 0;
  border: medium none;
  padding: 0;
  color: #707070;
  font-size: 18px;
}
.srch_bar .input-group-addon { margin: 0 0 0 -27px;}

.chat_ib h5{ font-size:15px; color:#464646; margin:0 0 8px 0;}
.chat_ib h5 span{ font-size:13px; float:right;}
.chat_ib p{ font-size:14px; color:#989898; margin:auto}
.chat_img {
  float: left;
  width: 11%;
}
.chat_ib {
  float: left;
  padding: 0 0 0 15px;
  width: 88%;
}

.chat_people{ overflow:hidden; clear:both;}
.chat_list {
  border-bottom: 1px solid #c4c4c4;
  margin: 0;
  padding: 18px 16px 10px;
}
.inbox_chat { height: 550px; overflow-y: scroll;}

.active_chat{ background:#ebebeb;}

.incoming_msg_img {
  display: inline-block;
  width: 6%;
}
.received_msg {
  display: inline-block;
  padding: 0 0 0 10px;
  vertical-align: top;
  width: 92%;
 }
 .received_withd_msg p {
  background: #ebebeb none repeat scroll 0 0;
  border-radius: 3px;
  color: #646464;
  font-size: 14px;
  margin: 0;
  padding: 5px 10px 5px 12px;
  width: 100%;
}
.time_date {
  color: #747474;
  display: block;
  font-size: 12px;
  margin: 8px 0 0;
}
.received_withd_msg { width: 57%;}
.mesgs {
  float: left;
  padding: 0px 15px 0 25px;
  width: 97%;
}

.panel-heading {
    margin: -19px -15px 0px !important;
}

 .sent_msg p {
  background: #05728f none repeat scroll 0 0;
  border-radius: 3px;
  font-size: 14px;
  margin: 0; color:#fff;
  padding: 5px 10px 5px 12px;
  width:100%;
}
.outgoing_msg{ overflow:hidden; margin:26px 0 26px;}
.sent_msg {
  float: right;
  width: 46%;
}
.input_msg_write input {
  background: rgba(0, 0, 0, 0) none repeat scroll 0 0;
  border: medium none;
  color: #4c4c4c;
  font-size: 15px;
  min-height: 48px;
  width: 93%;
}

.type_msg {border-top: 1px solid #c4c4c4;position: relative;}
.msg_send_btn {
  background: #05728f none repeat scroll 0 0;
  border: medium none;
  border-radius: 50%;
  color: #fff;
  cursor: pointer;
  font-size: 17px;
  height: 33px;
  position: initial;
  right: 0;
  top: 11px;
  width: 33px;
}
.messaging { padding: 0 0 50px 0;}
.msg_history {
  height: 315px;
  overflow-y: auto;
}
</style>

<!-- PAINEL -->
<div class="panel panel-primary">
    <div class="panel-heading">Chat - Conta #<?= $id_conta ?></div>
    <div class="panel-body" style="display: flex !important;">
        <div class="mesgs">
        <div class="msg_history">
            <?php foreach ($msgs as $m): ?>
                <?php if ($m['tipo'] == 'in'): ?> <!-- MENSAGEM DE ENTRADA (ENVIADA POR OUTRO USUÁRIO) -->
                    <div class="incoming_msg">
                        <div class="incoming_msg_img"> <img src="https://ptetutorials.com/images/user-profile.png" alt="sunil"> </div>
                        <div class="received_msg">
                            <div class="received_withd_msg">
                                <span class="time_date"><?= $m['user'] ?></span>
                                <p><?= $m['comment'] ?></p>
                                <span class="time_date"><?= $m['data'] ?></span>
                            </div>
                        </div>
                    </div>
                <?php else: ?> <!-- MENSAGEM DE SAÍDA (ENVIADA PELO USUÁRIO LOGADO) -->
                    <div class="outgoing_msg">
                        <div class="sent_msg">
                            <span class="time_date">Você</span>
                            <p><?= $m['comment'] ?></p>
                            <span class="time_date"><?= $m['data'] ?></span>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
        <div class="type_msg">
        <div class="input_msg_write">
            <form id="formulario" style="display: none !important;" enctype="multipart/form-data">
                <input type="file" name="arquivo" id="file" style="display: none !important;" />
            </form>
            <input type="text" class="write_msg" placeholder="Digite sua mensagem" />
            <button id="msg_sender" data-id="<?= $id_conta ?>" class="msg_send_btn" type="button"><i class="fa fa-paper-plane-o" aria-hidden="true"></i></button>
            <button data-id="<?= $id_conta ?>" onclick="thisFileUpload();" class="msg_send_btn" type="button"><i class="fa fa-cloud-upload" aria-hidden="true"></i></button>
        </div>
        </div>
    </div>
    </div>
</div>
<!-- ENDPAINEL -->

<script>
    function thisFileUpload() {
        document.getElementById("file").click();
    };

    function scrollBottom() {
        $(".msg_history").animate({ scrollTop: 20000000 }, "slow");
    }

    $('#msg_sender').on('click', function() {
        if ($('.write_msg').val().length) {
            var mensagem = $('.write_msg').val();
            var id_conta = $(this).data('id');

            $.ajax({
                url: "<?= site_url('contas/sendMsgAccount') ?>",
                type: "POST",
                data: {id: id_conta, text: mensagem},
                dataType: "json",
                success: function(callback) {
                    if (callback.status == 'OK') {
                        $('.write_msg').val(''); // Limpa campo de texto
                        // Cria objeto html e inseri no chat
                        $('.msg_history').append('<div class="outgoing_msg">'+
                            '<div class="sent_msg">'+
                                '<span class="time_date">Você</span>'+
                                '<p>'+mensagem+'</p>'+
                                '<span class="time_date">Agora</span>'+
                            '</div>'+
                        '</div>');
                        scrollBottom(); // Scroll Down
                    } else {
                        alert(callback.msg);
                    }
                },
                error: function() {
                    alert('Não foi possível enviar a mensagem, tente novamente mais tarde!');
                }
            });
        }
    });

    $('input#file').change(function() {
        var confirma = confirm('Realmente deseja fazer o upload do arquivo?');
        if (confirma) {
            var formData = new FormData();
            formData.append('file', $('#file')[0].files[0]);
            formData.append('id_conta', $('#msg_sender').data('id'));
            
            $.ajax({
                url : "<?= site_url('contas/uploadArchiveByAccount') ?>",
                type : 'POST',
                data : formData,
                dataType: "json",
                processData: false,  // tell jQuery not to process the data
                contentType: false,  // tell jQuery not to set contentType
                success : function(callback) {
                    if (callback.status == 'OK') {
                        $('.msg_history').append('<div class="outgoing_msg">'+
                            '<div class="sent_msg">'+
                                '<span class="time_date">Você</span>'+
                                '<p>'+callback.msg+'</p>'+
                                '<span class="time_date">Agora</span>'+
                            '</div>'+
                        '</div>');
                        scrollBottom(); // Scroll Down
                    } else {
                        alert(callback.msg)
                    }
                },
                error: function() {
                    alert('Não foi possível realizar o upload do arquivo. Tente novamente mais tarde!');
                }
            });
        }
    });

    scrollBottom(); // Scroll Down
</script>