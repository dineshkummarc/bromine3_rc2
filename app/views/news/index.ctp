<h1>Bromine News</h1>
<?php
//pr($news);
if (!empty($news)) {
    $commentImage = $html->image('tango/32x32/apps/internet-group-chat.png');
    if(isset($news['Feed']['Entry']['Author'])){
        extract($news['Feed']['Entry'],  EXTR_PREFIX_ALL, 'news');
        echo "<div class='rss-news-item' >";
            echo "<div>";
                echo "<a href='".$news_Link['href']."&utm_source=Bromine&utm_medium=news&utm_campaign=headline'><h3>".$news_title['value']."</h3></a>";
                echo "<div class='rss-item-date'>$news_updated</div>";
                echo "<a href='".$news_Link['href']."&utm_source=Bromine&utm_medium=news&utm_campaign=comment'>";
                    echo "<div class='rss-item-reactionsbox'>$commentImage";
                        //echo "<span>&nbsp;$replyCount&nbsp;</span>";
                    echo "</div>";
                echo "</a>";
            echo "</div>";
            echo "<div class='rss-item-body'>".nl2br($news_content['value'])."</div>";
        echo "</div>";    
    }elseif(is_array($news['Feed']['Entry'])){
        foreach ($news['Feed']['Entry'] as $n) {
            extract($n,  EXTR_PREFIX_ALL, 'news');
            //$replyCount = isset($news_replyCount) ? $news_replyCount : 0;
            echo "<div class='rss-news-item' >";
                echo "<div>";
                    echo "<a href='".$news_Link['href']."&utm_source=Bromine&utm_medium=news&utm_campaign=headline'><h3>".$news_title['value']."</h3></a>";
                    echo "<div class='rss-item-date'>$news_updated</div>";
                    echo "<a href='".$news_Link['href']."&utm_source=Bromine&utm_medium=news&utm_campaign=comment'>";
                        echo "<div class='rss-item-reactionsbox'>$commentImage";
                            //echo "<span>&nbsp;$replyCount&nbsp;</span>";
                        echo "</div>";
                    echo "</a>";
                echo "</div>";
                echo "<div class='rss-item-body'>".nl2br($news_content['value'])."</div>";
            echo "</div>";
        }
    }
} else {
    echo "<div class='warning'>Warning: Could not contact the news server. Check your internet connection√</div>";
}
?>