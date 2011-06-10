<?php

class PageComponent extends Component{

    static function wrap($ctx){
        $content = $ctx->getContent();
        return Utils::applyTemplate($content,'page');
    }

}

