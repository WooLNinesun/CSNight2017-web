<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="index.php">CSIENIGHT</a>
        </div>
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li><a href="index.php#perform">表演介紹</a></li>
                <li><a href="worker3.php">工作人員</a></li>
                <li><a href="announce.php">最新消息</a></li>
                <li><a href="https://nightjudge.csie.org">好康活動</a></li>
            <?php if( time() > 1490803200 ) { ?>
                <li><a href="LotteryResult.php">抽獎結果</a></li>
            <?php } ?>
            </ul>
        </div>
    </div>
</nav>
