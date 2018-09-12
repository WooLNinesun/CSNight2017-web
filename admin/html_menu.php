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
                <li><a href="PartInfo.php">部門和表演</a></li>
                <li><a href="WorkerInfo.php">工作人員</a></li>
                <li><a href="HRM.php">人事局</a></li>
                <li><a href="SponsorInfo.php">贊助商</a></li>
                <li><a href="AdminUser.php">管理員</a></li>
            </ul>
                <?php if( isset($_SESSION["adminlogin"]) and $_SESSION["adminlogin"] == true) { ?>
            <ul class="nav navbar-nav navbar-right">
                <li style="border-left: 1px solid gray;"><a href=LogOut.php>Logout&nbsp;<i class="fa fa-sign-out" aria-hidden="true"></i></a></li>
            </ul>
                <?php } ?>
        </div>
    </div>
</nav>