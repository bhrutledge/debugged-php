<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" 
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

{assign var='site_title' value='Framework Sample'}

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <!--<meta name="viewport" content="width=960" />-->
    
    <title>{if $id neq 'home'}{$title} | {/if}{$site_title}</title>
           
    <base href="{$base}" />
    
    <link rel="stylesheet" type="text/css" media="all" href="css/base.css" />
    <link rel="stylesheet" type="text/css" media="screen" href="css/images.css" />
    <link rel="stylesheet" type="text/css" media="print" href="css/print.css" />
    
    <!--[if lte IE 6]>
    <link rel="stylesheet" type="text/css" media="all" href="css/ie.css"/>
    <![endif]-->
    
    <!--[if gte IE 7]>
    <link rel="stylesheet" type="text/css" media="all" href="css/ie7.css"/>
    <![endif]-->
    
    {literal}
    <script type="text/javascript" src="js/modernizr-1.6.min.js"></script>
    <script type="text/javascript">/*<![CDATA[*/(function(){d=document;e=d.documentElement;c="images-on";i=new Image();t=i.style;s=d.enableStateScope=function(s,o){if(o)e.className+=" "+s;else e.className=e.className.replace(new RegExp("\\b"+s+"\\b"),"");};if(t.MozBinding!=null){t.backgroundImage="url("+d.location.protocol+"//0)";b=window.getComputedStyle(i,'').backgroundImage;if(b!="none"&&b!="url(invalid-url:)"||d.URL.substr(0,2)=="fi")s(c,true);}else{t.cssText="-webkit-opacity:0";if(t.webkitOpacity==0){i.onload=function(){s(c,i.width>0);};i.src="data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==";}else{i.onerror=function(){s(c,true);};i.src="about:blank";}}})();//]]></script>
    {/literal}
    
    {$head}
</head>

<body id="{$id}" class="framework {$page_class}">
    <div id="body-header" class="header">
        <h1>{$site_title}</h1>
    </div>

    <div id="body-article" class="article">
        {$content}
        {if $path}
        <ol id="breadcrumb-nav" class="nav">
            {section name=breadcrumb loop=$path}
            <li class="parent">{html_site_link url=$path[breadcrumb]}</li>
            {/section}
            <li class="current">{html_site_link url=$url}</li>
        </ol>
        {/if}
        <div class="clear"></div>
    </div>

    <div id="body-footer" class="footer">
        <ul id="footer-nav" class="nav">
            <li id="section-link">{html_site_link url='section'}</li>
            <li id="sitemap-link">{html_site_link url='sitemap'}</li>
        </ul>
    </div>
</body>
</html>
