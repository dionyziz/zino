div#notify  {
    position: fixed;
    bottom: -7px;
    right: 0px;
    width: 290px;
    background-position: 0px 0px;
    background-repeat: repeat-y;
    background-image: url('images/notification-bg2.jpg');
}
div#notify ol {
    list-style: none;
    padding: 5px 0px 0 4px;
    margin: 0px 0px 0 0;
    background-position: top left;
    background-repeat: no-repeat;
    background-image: url('images/notification-bg.gif');
    position: relative;
    top: -7px;
}
div#notify ol li {
    padding: 3px 4px 3px 20px;
    height: 20px;
    background-color: #efeeec;
    background-repeat: no-repeat;
    background-position: 1px 3px;
}
div#notify ol li.friend {
    background-image: url('images/notification-friend.jpg');
}
div#notify ol li.comment {
    background-image: url('images/notification-comment.jpg');
}
div#notify ol li.next {
    border-top: 1px solid #c7c7c5;
}
div#notify ol li a.hide {
    background-image: url('images/notification-hide.jpg');
    background-position: top right;
    background-repeat: no-repeat;
    font-size: 0;
    height: 20px;
    width: 20px;
    display: block;
    float: right;
}
