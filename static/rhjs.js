function getElementsByTagNames(list,obj) {
    if (!obj) var obj = document;
    var tagNames = list.split(',');
    var resultArray = new Array();
    for (var i=0;i<tagNames.length;i++) {
        var tags = obj.getElementsByTagName(tagNames[i]);
        for (var j=0;j<tags.length;j++) {
            resultArray.push(tags[j]);
        }
    }
    var testNode = resultArray[0];
    if (!testNode) return [];
    if (testNode.sourceIndex) {
        resultArray.sort(function (a,b) {
                return a.sourceIndex - b.sourceIndex;
        });
    }
    else if (testNode.compareDocumentPosition) {
        resultArray.sort(function (a,b) {
                return 3 - (a.compareDocumentPosition(b) & 6);
        });
    }
    return resultArray;
}

function urlencode(str) {
    str = escape(str);
    str = str.replace('+', '%2B');
    str = str.replace('%20', '+');
    str = str.replace('*', '%2A');
    str = str.replace('/', '%2F');
    str = str.replace('@', '%40');
    return str;
}

function RHJSFormHandler(rhjs,form){

    function handleSuccess(resp){
        console.log("success",resp);
        if (action=="insert" && target){
            target.innerHTML = resp.responseText;
            //rhjs.init();
            setTimeout(rhjs.init, 0);
        }else if (action=="prepend"){
            target.innerHTML = resp.responseText + target.innerHTML;
            //rhjs.init();
            setTimeout(rhjs.init, 0); 
        }
        disableElements(false);
        clearElements(); 
    }

    function handleFailure(resp){
        console.log("failure",resp);
        disableElements(false);
    } 

    function disableElements(val){
        for(var i in form.elements){
            form.elements[i].disabled = val;
        }
    }

    function clearElements(){
        for(var i in form.elements){
            var e = form.elements[i];
            if ((e.tagName=="INPUT" && e.type.toLowerCase()=="text")
                || e.tagName=="TEXTAREA"){
                e.value="";
            }
        }
    }

    function doSubmit(){
        console.log("submitting");
        var callback = {
            success : handleSuccess,
            failure : rhjs.handleFailure
            }
        YAHOO.util.Connect.setForm(form); 
        console.log("method:",form.method);
        console.log("action:",form.action);
        console.log("callback:",callback);
        var cObj = YAHOO.util.Connect.asyncRequest(form.method, form.action, callback); 
        console.log("asyncRequest returned:",cObj);
        disableElements(true);
    }


    this.handler = function(e){
        YAHOO.util.Event.stopEvent(e);
        console.log("form handle got", e);
        if (e.type=="submit") doSubmit();
    }

    var action = "";
    if (form.rh_action){
        action = form.rh_action.value;
        console.log("action=",action);
    }
    
    var target = null;
    if (form.rh_target){
        target = document.getElementById(form.rh_target.value);
        console.log("target=",target);
    }

    if (typeof form.hasRHJS == "undefined"){
        var r = YAHOO.util.Event.addListener(form, "submit", this.handler);
        //console.log("attached to ", form, r, rhjs);
        form.hasRHJS = true;
    }

    this.submit = doSubmit;

}

function RHJSLinkHandler(rhjs,link){

    function doClick(){
        var callback = {
            success : rhjs.handleSuccess,
            failure : rhjs.handleFailure,
            argument: { url : link.href }
            }
        var cObj = YAHOO.util.Connect.asyncRequest("GET", link.href, callback); 
    }

    this.handler = function(e){
        YAHOO.util.Event.stopEvent(e);
        console.log("link handle got", e);
        if (e.type=="click") doClick();
    }

    if (typeof link.hasRHJS == "undefined"){
        var r = YAHOO.util.Event.addListener(link, "click", this.handler);
        //console.log("attached to ", link, r, rhjs);
        link.hasRHJS = true;
    }

}

function RHJS(){
    var handlers = new Array();
    var me = this;

    this.handleSuccess = function(resp){
        console.log("success",resp);
        try{
            var obj = YAHOO.lang.JSON.parse(resp.responseText);
        }catch(err){
            console.log(err);
            var msg = "Ack! The server returned incomprehensible data!";
            if (('argument' in resp) && ('url' in resp.argument)){
                msg += "<br/><br/><a href=\""+resp.argument.url+"\">";
                msg += "Offending URL</a>";
            }
            me.doError(msg);
            return; //TODO: something sensible?
        }

        if (typeof obj.action == "undefined") obj.action="insert";
        doAction(obj);
    }

    this.handleFailure = function(resp){
        console.log(resp);

        var msg = "Connection failed: " + resp.statusText;
        msg += "<br/><br/>Check your internet connection, ";
        msg += "and try reloading the page...";
        me.doError(msg);        
    }


    //show a modal error dialog. only use for critical errors
    this.doError = function(msg){
        var p = new YAHOO.widget.Panel("error",  
            { width:"240px", 
              fixedcenter:true, 
              close:true, 
              draggable:false, 
              zindex:4,
              modal:true,
              visible:false
            } 
        );
        p.setHeader("Oops! Something went wrong :-(");
        p.setBody(msg);
        p.render(document.body);
        p.show();
    }

    function initForms(){
        var forms = YAHOO.util.Dom.getElementsByClassName('rh-ajax', 'form');
        if (forms.length==0) return;

        for(var i in forms){
            if(typeof forms[i].hasRHJS == "undefined"){
                new RHJSFormHandler(me,forms[i]);
            }
        }
    }

    function initLinks(){
        var links = YAHOO.util.Dom.getElementsByClassName('rh-link', 'a');
        if (links.length==0) return;

        for(var i in links){
            if(typeof links[i].hasRHJS == "undefined"){
                new RHJSLinkHandler(me,links[i]);
            }
        }
    }

    function initDHTML(){
        var a = YAHOO.util.Dom.getElementsByClassName('rh-dhtml', 'script');
        if (a.length==0) return;
        console.log("scripts",a);

        for(var i in a){
            console.log("doing:",a[i].innerHTML);
            doScript(a[i].innerHTML);
            a[i].parentNode.removeChild(a[i]);
        } 
    }

    function replaceNode(html,target){
        var e = document.createElement('div');
        e.innerHTML = html;
        target.parentNode.replaceChild(e.childNodes[0],target);
    }

    function getDelay(obj){
        return ('delay' in obj ? obj.delay : 0);
    }

    function getTarget(obj){
       return ('target' in obj ? document.getElementById(obj.target) : null);
    }

    function doSESendHandler(type,args,obj){
        console.log("got SESend",type,args,obj);
        YAHOO.util.Event.stopPropagation(args[1]);
        YAHOO.util.Event.preventDefault(args[1]);
        console.log("submitting",obj.id);
        try{
            //document.forms[obj.id].submit();
            var fh = new RHJSFormHandler(window.rhjs,obj);
            fh.submit();
        }catch(e){
            console.log("error submitting:",e);
        }
        
    }

    function doSESend(obj){
        var target = getTarget(obj);
        if (!target) return;
        if ('sesend' in target) return; //already attached;

        var kl =new YAHOO.util.KeyListener(target,
                { shift:true, keys:13 },
                {fn:doSESendHandler,
                 scope:target,
                 correctScope:true});
        kl.enable();
        target.sesend = 'attached';
        console.log('attached', target, kl);
    }

    function doScript(src){
        var obj = YAHOO.lang.JSON.parse(src);
        if (!obj || !obj.action) return;

        doAction(obj);
    }

    function doAction(obj){
        var delay = getDelay(obj); 
        var target = getTarget(obj); 
        if (obj.action=="multi"){
            console.log("doing multi",obj);
            for (var i in obj.actions){
                doAction(obj.actions[i]);
            }
        }else if (obj.action=="insert" && target){
            setTimeout(function(){target.innerHTML=obj.data;setTimeout(me.init, 0);}, delay);
        }else if (obj.action=="replace" && target){
            if (!target) return;
            setTimeout(function(){replaceNode(obj.data,target);setTimeout(me.init, 0);}, delay); 
        }else if (obj.action=="prepend" && target){
            setTimeout(function(){target.innerHTML=obj.data+target.innerHTML;setTimeout(me.init,0);}, delay); 
        }else if (obj.action=="append" && target){
            setTimeout(function(){target.innerHTML=target.innerHTML+obj.data;setTimeout(me.init,0);}, delay); 
        }else if (obj.action=="load"){
            var callback = {
                    success : me.handleSuccess,
                    failure : me.handleFailure
                }
            setTimeout(
                function(){
                    YAHOO.util.Connect.asyncRequest("GET", obj.url, callback);
                    //me.init();
                    setTimeout(me.init, 0);  
                },
                delay
                ); 
        }else if (obj.action=="setvar" && target){    
            target[obj.name] = obj.data;
            console.log("attached var",target, obj.name, target[obj.name]);
        }else if (obj.action=="redirect"){
            if (typeof obj.url!= "undefined") document.location = obj.url;
        }else if (obj.action=="focus"){
            var target = getTarget(obj);
            console.log("focus ",target);
            try{ target.focus(); }catch(e){}
        }else if (obj.action=="sesend"){
            //submit form on shift-enter 
            doSESend(obj);
        }else if (obj.action=="securityhole"){
            if (typeof obj.js!="undefined") eval(obj.js);
        }
        console.log("done script",obj);
    }


    this.init = function(){
        console.log("doing init");
        initForms();
        initLinks();
        initDHTML();
    }
    window.rhjsinit = this.init;
    window.rhjs = this;
}
