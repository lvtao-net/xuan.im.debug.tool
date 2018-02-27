<?php
function post($remote_server, $post_string) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $remote_server);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_string);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
}

if(isset($_POST['key']))
{
    $key = $_POST['key'];
    $iv  = substr($key, 0, 16);

    include "phpaes.class.php";
    $aes = new phpAES();
    $aes->init($key, $iv);
    $content = $aes->encrypt($_POST['content']);
    $content = post($_POST['url'], $content);
    $content = $aes->decrypt($content);
    $content = json_decode($content);
}
?>
<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>喧喧接口调试工具</title>
    <!-- ZUI 标准版压缩后的 CSS 文件 -->
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/zui/1.8.1/css/zui.min.css">
    <style>
        body{padding-top: 20px;}
        .mb{margin-bottom: 20px;}
        .btn{margin:0 5px;}
        .hljs{display:block;overflow-x:auto;padding:0.5em;color:#657b83}
        .hljs-comment,.hljs-quote{color:#93a1a1}
        .hljs-keyword,.hljs-selector-tag,.hljs-addition{color:#859900}
        .hljs-number,.hljs-string,.hljs-meta .hljs-meta-string,.hljs-literal,.hljs-doctag,.hljs-regexp{color:#2aa198}
        .hljs-title,.hljs-section,.hljs-name,.hljs-selector-id,.hljs-selector-class{color:#268bd2}
        .hljs-attribute,.hljs-attr,.hljs-variable,.hljs-template-variable,.hljs-class .hljs-title,.hljs-type{color:#b58900}
        .hljs-symbol,.hljs-bullet,.hljs-subst,.hljs-meta,.hljs-meta .hljs-keyword,.hljs-selector-attr,.hljs-selector-pseudo,.hljs-link{color:#cb4b16}
        .hljs-built_in,.hljs-deletion{color:#dc322f}
        .hljs-formula{background:#eee8d5}
        .hljs-emphasis{font-style:italic}
        .hljs-strong{font-weight:bold}
    </style>
    <script src="highlight/jquery-2.1.1.min.js"></script>
    <script src="highlight/highlight.js"></script>
</head>
<body>
<div class="container">
    <form method="post" action="">
        <div class="col-xs-6 col-sm-offset-3 mb">
            <div class="input-group mb">
                <span class="input-group-addon">地址:</span>
                <input type="text" name="url" class="form-control" value="<?php echo isset($_POST['url']) ? $_POST['url'] : 'http://www.lvtao.net/xuanxuan.php';?>">
            </div>
            <div class="input-group mb">
                <span class="input-group-addon">密钥:</span>
                <input type="text" name="key" class="form-control" value="<?php echo isset($_POST['key']) ? $_POST['key'] : '88888888888888888888888888888888';?>">
            </div>
            <div class="input-group mb">
                <span class="input-group-addon">内容:</span>
                <textarea name="content" rows="8" class="form-control"><?php echo isset($_POST['key']) ? $_POST['content'] : '{"module":"chat","method":"userGetlist","params":[""],"userID":1}';?></textarea>
            </div>
            <div class="text-center">
                <input type="submit" class="btn btn-primary" value="Go..."/>
            </div>
        </div>
    </form>
    <?php if(isset($_POST['key'])):?>
    <div class="col-xs-12">
        <div class="row text-center"><?php echo $aes->getEngine();?></div>
        <pre><?php echo json_encode($content,JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);?></pre>
    </div>
    <?php endif;?>
</div>

<script>
    $(document).ready(function() {
        $('pre').each(function(i, block) {
            hljs.highlightBlock(block);
        });
    });
</script>
</body>
</html>

