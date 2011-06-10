<?php

class MypageComponent extends Component{

    static function wrap($ctx){
        $content = $ctx->getContent();
        return Utils::applyTemplate($content,'page');
    }

}

