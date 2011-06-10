<?php 

class StaticComponent extends Component{

    private static function doHeaders($ctx){
        $ctx->setHeader('Content-type','text/javascript');
        $future = date("D, d M Y H:i:s T",time()+(3600*24*30));
        $ctx->setHeader('Expires', $future);
        $ctx->setHeader('Cache-control', 'max-age='.(3600*24*30));
    }

    static function rhjs($ctx){
        return self::doit($ctx,'static/rhjs.js');

        if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE'])){
            $ctx->setHTTPStatus(304, 'Not modified');
            return '';
        }
        self::doHeaders($ctx);
        return file_get_contents('static/rhjs.js',true);
    }

    static function yui($ctx){
        return self::doit($ctx,'static/yui-20110226.js');
    }

    static function doit($ctx,$file){
        if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE'])){
            $ctx->setHTTPStatus(304, 'Not modified');
            return '';
        }
        self::doHeaders($ctx);
        return file_get_contents($file,true);
    }

    static function router($ctx,$path){
        $parts = explode('/', $path);
        $method = $parts[0];
        if ($method=='rhjs'){
            return self::rhjs($ctx);
        }else if ($method=='yui'){
            return self::yui($ctx);
        }else{
            $msg = "Method not yet implemented: ".$parts[0];
            //throw new ContextException($msg, 404);
            return $msg;
        }
    }



}
