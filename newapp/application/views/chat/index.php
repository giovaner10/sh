<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Chat Shownet</title>

    <link rel="stylesheet" href="<?= base_url('media/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('media/css/chat.css') ?>">

</head>
<body>

<div id="app" class="container">

    <h3 class="text-center">Chat - Shownet</h3>

    <div id="messages">
        <div class="col s12">
            <ul class="list-unstyled" v-cloak>
                <li v-for="message in messages">
                    <span class="date" v-if="message.date">[{{ message.date }}]</span>
                    <span class="name" v-if="message.user">{{ message.user }}:</span>
                    <span class="text" :style="{ color: message.color }">
                        {{ message.text }}
                    </span>
                </li>
            </ul>
        </div>        
    </div>

    <div class="row-fluid">                
        <div class="col-12 col-sm-9">
            <input type="text" class="form-control" placeholder="Mensagem" v-model="text"
                @keyup.enter="sendMessage">
        </div>
    </div>    

</div>

<script type="text/javascript" src="<?= base_url('media/js/vue.min.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('media/js/chat.js') ?>"></script>
</body>
</html>
