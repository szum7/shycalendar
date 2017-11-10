<?php
header("Content-Type: text/html; charset=utf-8");
require_once 'db.php';
require_once 'class/calendar.php';
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>ShyCalendar</title>

        <link rel="shortcut icon" href="img/icon.gif" />
        
        <link rel="stylesheet" type="text/css" href="components/jquery-ui-1.12.1/jquery-ui.min.css" />
        <link rel="stylesheet" type="text/css" href="css/index.css" />
        
        <script type="text/javascript" src="components/jquery-3.2.1.min.js"></script>
        <script type="text/javascript" src="components/jquery-ui-1.12.1/jquery-ui.min.js"></script>
        <script type="text/javascript" src="js/animateHeight.js"></script>
        <script type="text/javascript" src="js/index.js"></script>

    </head>
    <body>

        <div class="siteheader">
            <section class="menu">
                <ul>
                    <li class="todolist_btn"><div class="btn"><p>ToDoList</p></div>
                        <div class="todolist abs">
                            <span>                            
                                <form action="actions.php" method="post" name="f1n" id="f1i" enctype="multipart/form-data">
                                    <input type="text" value="" name="date" id="date" placeholder="0000-00-00 00:00:00"/><br/>
                                    <textarea name="intro" id="intro" placeholder="Short intro..."></textarea><br/>
                                    <textarea name="content" id="content" placeholder="Content..."></textarea>
                                </form>
                            </span>
                        </div>
                    </li><li><div class="btn"><p>New event</p></div>
                    </li><li class="last"><div class="btn"><p>Sql command</p></div>
                    </li>
                </ul>
            </section>
            <section class="days">
                <ul>
                    <li>Hétfő</li><li>Kedd</li><li>Szerda</li><li>Csütörtök</li><li>Péntek</li><li>Szombat</li><li>Vasárnap</li>
                </ul>
            </section>
        </div>

        <?php
        $b = new calender();
        $b->SetRelativeMasterDate(60 * 60 * 24 * 90, 60 * 60 * 24 * 60);
        $b->buildCalendar($connection_id);
        ?>

        <footer>
            <p><strong>ShyCalendar 1.0</strong><br/>
                ---<br/>
                Created by szum7<br/>
                2014</p>
        </footer>

        <div id="dayEvent">
            <div class="close">X</div>
            <form action="actions.php" method="post" name="de" id="de" enctype="multipart/form-data">
                <input type="hidden" name="dayEventDay" id="dayEventDay" />
                <input type="hidden" name="originalMysql" id="originalMysql" />
                <textarea name="de_events" id="de_events" placeholder=""></textarea>
            </form>
        </div>

    </body>
</html>
