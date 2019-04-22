<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit982e99603abf7e931d3651829d24ceda
{
    public static $files = array (
        'c65d09b6820da036953a371c8c73a9b1' => __DIR__ . '/..' . '/facebook/graph-sdk/src/Facebook/polyfills.php',
        '21b0eee95a60acf8299f0e7a95e08705' => __DIR__ . '/../..' . '/www/libs/utils.php',
    );

    public static $prefixLengthsPsr4 = array (
        'S' => 
        array (
            'Symfony\\Component\\Yaml\\' => 23,
        ),
        'F' => 
        array (
            'Facebook\\' => 9,
        ),
        'E' => 
        array (
            'Eusonlito\\DisposableEmail\\' => 26,
        ),
        'D' => 
        array (
            'Doctrine\\Instantiator\\' => 22,
            'Doctrine\\Common\\Cache\\' => 22,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Symfony\\Component\\Yaml\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/yaml',
        ),
        'Facebook\\' => 
        array (
            0 => __DIR__ . '/..' . '/facebook/graph-sdk/src/Facebook',
        ),
        'Eusonlito\\DisposableEmail\\' => 
        array (
            0 => __DIR__ . '/..' . '/eusonlito/disposable-email-validator/src',
        ),
        'Doctrine\\Instantiator\\' => 
        array (
            0 => __DIR__ . '/..' . '/doctrine/instantiator/src/Doctrine/Instantiator',
        ),
        'Doctrine\\Common\\Cache\\' => 
        array (
            0 => __DIR__ . '/..' . '/doctrine/cache/lib/Doctrine/Common/Cache',
        ),
    );

    public static $prefixesPsr0 = array (
        'P' => 
        array (
            'PhpOption\\' => 
            array (
                0 => __DIR__ . '/..' . '/phpoption/phpoption/src',
            ),
            'PhpCollection' => 
            array (
                0 => __DIR__ . '/..' . '/phpcollection/phpcollection/src',
            ),
        ),
        'O' => 
        array (
            'OAuth2' => 
            array (
                0 => __DIR__ . '/..' . '/bshaffer/oauth2-server-php/src',
            ),
        ),
        'M' => 
        array (
            'Metadata\\' => 
            array (
                0 => __DIR__ . '/..' . '/jms/metadata/src',
            ),
        ),
        'J' => 
        array (
            'JMS\\Serializer' => 
            array (
                0 => __DIR__ . '/..' . '/jms/serializer/src',
            ),
            'JMS\\' => 
            array (
                0 => __DIR__ . '/..' . '/jms/parser-lib/src',
            ),
        ),
        'D' => 
        array (
            'Doctrine\\Common\\Lexer\\' => 
            array (
                0 => __DIR__ . '/..' . '/doctrine/lexer/lib',
            ),
            'Doctrine\\Common\\Annotations\\' => 
            array (
                0 => __DIR__ . '/..' . '/doctrine/annotations/lib',
            ),
        ),
    );

    public static $classMap = array (
        'AdminUser' => __DIR__ . '/../..' . '/www/libs/admin_user.php',
        'Annotation' => __DIR__ . '/../..' . '/www/libs/annotation.php',
        'Backup' => __DIR__ . '/../..' . '/www/libs/backup.php',
        'Ban' => __DIR__ . '/../..' . '/www/libs/ban.php',
        'BaseFacebook' => __DIR__ . '/../..' . '/www/libs/facebook/base_facebook.php',
        'BasicThumb' => __DIR__ . '/../..' . '/www/libs/webimages.php',
        'Blog' => __DIR__ . '/../..' . '/www/libs/blog.php',
        'CaptchaSecurityImages' => __DIR__ . '/../..' . '/www/libs/ts.php',
        'Comment' => __DIR__ . '/../..' . '/www/libs/comment.php',
        'CommentQA' => __DIR__ . '/../..' . '/www/libs/commenttree.php',
        'CommentTree' => __DIR__ . '/../..' . '/www/libs/commenttree.php',
        'CommentTreeNode' => __DIR__ . '/../..' . '/www/libs/commenttree.php',
        'DbHelper' => __DIR__ . '/../..' . '/www/libs/db_helper.php',
        'Facebook' => __DIR__ . '/../..' . '/www/libs/facebook/facebook.php',
        'FacebookApiException' => __DIR__ . '/../..' . '/www/libs/facebook/base_facebook.php',
        'HG_Parser' => __DIR__ . '/..' . '/crodas/haanga/lib/Haanga/Compiler/Tokenizer.php',
        'Haanga' => __DIR__ . '/..' . '/crodas/haanga/lib/Haanga.php',
        'Haanga_AST' => __DIR__ . '/..' . '/crodas/haanga/lib/Haanga/AST.php',
        'Haanga_Compiler' => __DIR__ . '/..' . '/crodas/haanga/lib/Haanga/Compiler.php',
        'Haanga_Compiler_Exception' => __DIR__ . '/..' . '/crodas/haanga/lib/Haanga/Compiler/Exception.php',
        'Haanga_Compiler_Parser' => __DIR__ . '/..' . '/crodas/haanga/lib/Haanga/Compiler/Parser.php',
        'Haanga_Compiler_Runtime' => __DIR__ . '/..' . '/crodas/haanga/lib/Haanga/Compiler/Runtime.php',
        'Haanga_Compiler_Tokenizer' => __DIR__ . '/..' . '/crodas/haanga/lib/Haanga/Compiler/Tokenizer.php',
        'Haanga_Exception' => __DIR__ . '/..' . '/crodas/haanga/lib/Haanga/Exception.php',
        'Haanga_Extension' => __DIR__ . '/..' . '/crodas/haanga/lib/Haanga/Extension.php',
        'Haanga_Extension_Filter' => __DIR__ . '/..' . '/crodas/haanga/lib/Haanga/Extension/Filter.php',
        'Haanga_Extension_Filter_Capfirst' => __DIR__ . '/..' . '/crodas/haanga/lib/Haanga/Extension/Filter/Capfirst.php',
        'Haanga_Extension_Filter_CleanString' => __DIR__ . '/../..' . '/www/libs/haanga_mnm.php',
        'Haanga_Extension_Filter_CleanText' => __DIR__ . '/../..' . '/www/libs/haanga_mnm.php',
        'Haanga_Extension_Filter_CleanUrl' => __DIR__ . '/../..' . '/www/libs/haanga_mnm.php',
        'Haanga_Extension_Filter_Count' => __DIR__ . '/..' . '/crodas/haanga/lib/Haanga/Extension/Filter/Count.php',
        'Haanga_Extension_Filter_Cut' => __DIR__ . '/..' . '/crodas/haanga/lib/Haanga/Extension/Filter/Cut.php',
        'Haanga_Extension_Filter_Date' => __DIR__ . '/..' . '/crodas/haanga/lib/Haanga/Extension/Filter/Date.php',
        'Haanga_Extension_Filter_Default' => __DIR__ . '/..' . '/crodas/haanga/lib/Haanga/Extension/Filter/Default.php',
        'Haanga_Extension_Filter_Dictsort' => __DIR__ . '/..' . '/crodas/haanga/lib/Haanga/Extension/Filter/Dictsort.php',
        'Haanga_Extension_Filter_Divisibleby' => __DIR__ . '/..' . '/crodas/haanga/lib/Haanga/Extension/Filter/Divisibleby.php',
        'Haanga_Extension_Filter_Dump' => __DIR__ . '/../..' . '/www/libs/haanga_mnm.php',
        'Haanga_Extension_Filter_Empty' => __DIR__ . '/..' . '/crodas/haanga/lib/Haanga/Extension/Filter/Empty.php',
        'Haanga_Extension_Filter_Escape' => __DIR__ . '/..' . '/crodas/haanga/lib/Haanga/Extension/Filter/Escape.php',
        'Haanga_Extension_Filter_Exists' => __DIR__ . '/..' . '/crodas/haanga/lib/Haanga/Extension/Filter/Exists.php',
        'Haanga_Extension_Filter_Explode' => __DIR__ . '/..' . '/crodas/haanga/lib/Haanga/Extension/Filter/Explode.php',
        'Haanga_Extension_Filter_Hostname' => __DIR__ . '/..' . '/crodas/haanga/lib/Haanga/Extension/Filter/Hostname.php',
        'Haanga_Extension_Filter_IsArray' => __DIR__ . '/..' . '/crodas/haanga/lib/Haanga/Extension/Filter/Isarray.php',
        'Haanga_Extension_Filter_Join' => __DIR__ . '/..' . '/crodas/haanga/lib/Haanga/Extension/Filter/Join.php',
        'Haanga_Extension_Filter_Json' => __DIR__ . '/..' . '/crodas/haanga/lib/Haanga/Extension/Filter/Json.php',
        'Haanga_Extension_Filter_Length' => __DIR__ . '/..' . '/crodas/haanga/lib/Haanga/Extension/Filter/Length.php',
        'Haanga_Extension_Filter_Linebreaksbr' => __DIR__ . '/..' . '/crodas/haanga/lib/Haanga/Extension/Filter/Linebreaksbr.php',
        'Haanga_Extension_Filter_Lower' => __DIR__ . '/..' . '/crodas/haanga/lib/Haanga/Extension/Filter/Lower.php',
        'Haanga_Extension_Filter_NoScheme' => __DIR__ . '/../..' . '/www/libs/haanga_mnm.php',
        'Haanga_Extension_Filter_Null' => __DIR__ . '/..' . '/crodas/haanga/lib/Haanga/Extension/Filter/Null.php',
        'Haanga_Extension_Filter_Pluralize' => __DIR__ . '/..' . '/crodas/haanga/lib/Haanga/Extension/Filter/Pluralize.php',
        'Haanga_Extension_Filter_PostsURL' => __DIR__ . '/../..' . '/www/libs/haanga_mnm.php',
        'Haanga_Extension_Filter_Reverse' => __DIR__ . '/..' . '/crodas/haanga/lib/Haanga/Extension/Filter/Reverse.php',
        'Haanga_Extension_Filter_Safe' => __DIR__ . '/..' . '/crodas/haanga/lib/Haanga/Extension/Filter/Safe.php',
        'Haanga_Extension_Filter_Slugify' => __DIR__ . '/..' . '/crodas/haanga/lib/Haanga/Extension/Filter/Slugify.php',
        'Haanga_Extension_Filter_StringFormat' => __DIR__ . '/..' . '/crodas/haanga/lib/Haanga/Extension/Filter/Stringformat.php',
        'Haanga_Extension_Filter_SubId' => __DIR__ . '/../..' . '/www/libs/haanga_mnm.php',
        'Haanga_Extension_Filter_SubName' => __DIR__ . '/../..' . '/www/libs/haanga_mnm.php',
        'Haanga_Extension_Filter_Substr' => __DIR__ . '/..' . '/crodas/haanga/lib/Haanga/Extension/Filter/Substr.php',
        'Haanga_Extension_Filter_TextToHTML' => __DIR__ . '/../..' . '/www/libs/haanga_mnm.php',
        'Haanga_Extension_Filter_Title' => __DIR__ . '/..' . '/crodas/haanga/lib/Haanga/Extension/Filter/Title.php',
        'Haanga_Extension_Filter_Trans' => __DIR__ . '/..' . '/crodas/haanga/lib/Haanga/Extension/Filter/Trans.php',
        'Haanga_Extension_Filter_Translation' => __DIR__ . '/..' . '/crodas/haanga/lib/Haanga/Extension/Filter/Translation.php',
        'Haanga_Extension_Filter_Trim' => __DIR__ . '/..' . '/crodas/haanga/lib/Haanga/Extension/Filter/Trim.php',
        'Haanga_Extension_Filter_Truncatechars' => __DIR__ . '/..' . '/crodas/haanga/lib/Haanga/Extension/Filter/Truncatechars.php',
        'Haanga_Extension_Filter_Truncatewords' => __DIR__ . '/..' . '/crodas/haanga/lib/Haanga/Extension/Filter/Truncatewords.php',
        'Haanga_Extension_Filter_TxtShorter' => __DIR__ . '/../..' . '/www/libs/haanga_mnm.php',
        'Haanga_Extension_Filter_Upper' => __DIR__ . '/..' . '/crodas/haanga/lib/Haanga/Extension/Filter/Upper.php',
        'Haanga_Extension_Filter_UrlEncode' => __DIR__ . '/..' . '/crodas/haanga/lib/Haanga/Extension/Filter/Urlencode.php',
        'Haanga_Extension_Filter_UserUri' => __DIR__ . '/../..' . '/www/libs/haanga_mnm.php',
        'Haanga_Extension_Filter_intval' => __DIR__ . '/..' . '/crodas/haanga/lib/Haanga/Extension/Filter/Intval.php',
        'Haanga_Extension_Tag' => __DIR__ . '/..' . '/crodas/haanga/lib/Haanga/Extension/Tag.php',
        'Haanga_Extension_Tag_Buffer' => __DIR__ . '/..' . '/crodas/haanga/lib/Haanga/Extension/Tag/Buffer.php',
        'Haanga_Extension_Tag_CurrentTime' => __DIR__ . '/..' . '/crodas/haanga/lib/Haanga/Extension/Tag/Currenttime.php',
        'Haanga_Extension_Tag_Cycle' => __DIR__ . '/..' . '/crodas/haanga/lib/Haanga/Extension/Tag/Cycle.php',
        'Haanga_Extension_Tag_Dictsort' => __DIR__ . '/..' . '/crodas/haanga/lib/Haanga/Extension/Tag/Dictsort.php',
        'Haanga_Extension_Tag_Exec' => __DIR__ . '/..' . '/crodas/haanga/lib/Haanga/Extension/Tag/Exec.php',
        'Haanga_Extension_Tag_FirstOf' => __DIR__ . '/..' . '/crodas/haanga/lib/Haanga/Extension/Tag/Firstof.php',
        'Haanga_Extension_Tag_GetStaticURL' => __DIR__ . '/../..' . '/www/libs/haanga_mnm.php',
        'Haanga_Extension_Tag_GetURL' => __DIR__ . '/../..' . '/www/libs/haanga_mnm.php',
        'Haanga_Extension_Tag_Inline' => __DIR__ . '/..' . '/crodas/haanga/lib/Haanga/Extension/Tag/Inline.php',
        'Haanga_Extension_Tag_Lower' => __DIR__ . '/..' . '/crodas/haanga/lib/Haanga/Extension/Tag/Lower.php',
        'Haanga_Extension_Tag_MeneameEndtime' => __DIR__ . '/../..' . '/www/libs/haanga_mnm.php',
        'Haanga_Extension_Tag_Min' => __DIR__ . '/..' . '/crodas/haanga/lib/Haanga/Extension/Tag/Min.php',
        'Haanga_Extension_Tag_SetSafe' => __DIR__ . '/..' . '/crodas/haanga/lib/Haanga/Extension/Tag/Setsafe.php',
        'Haanga_Extension_Tag_Spaceless' => __DIR__ . '/..' . '/crodas/haanga/lib/Haanga/Extension/Tag/Spaceless.php',
        'Haanga_Extension_Tag_Templatetag' => __DIR__ . '/..' . '/crodas/haanga/lib/Haanga/Extension/Tag/Templatetag.php',
        'Haanga_Extension_Tag_Trans' => __DIR__ . '/..' . '/crodas/haanga/lib/Haanga/Extension/Tag/Trans.php',
        'Haanga_Extension_Tag_Tryinclude' => __DIR__ . '/..' . '/crodas/haanga/lib/Haanga/Extension/Tag/Tryinclude.php',
        'Haanga_Extension_Tag_Upper' => __DIR__ . '/..' . '/crodas/haanga/lib/Haanga/Extension/Tag/Upper.php',
        'Haanga_Generator_PHP' => __DIR__ . '/..' . '/crodas/haanga/lib/Haanga/Generator/PHP.php',
        'Haanga_yyStackEntry' => __DIR__ . '/..' . '/crodas/haanga/lib/Haanga/Compiler/Parser.php',
        'Haanga_yyToken' => __DIR__ . '/..' . '/crodas/haanga/lib/Haanga/Compiler/Parser.php',
        'HtmlImages' => __DIR__ . '/../..' . '/www/libs/webimages.php',
        'IXR_Base64' => __DIR__ . '/../..' . '/www/libs/IXR_Library.inc.php',
        'IXR_Client' => __DIR__ . '/../..' . '/www/libs/IXR_Library.inc.php',
        'IXR_ClientMulticall' => __DIR__ . '/../..' . '/www/libs/IXR_Library.inc.php',
        'IXR_Date' => __DIR__ . '/../..' . '/www/libs/IXR_Library.inc.php',
        'IXR_Error' => __DIR__ . '/../..' . '/www/libs/IXR_Library.inc.php',
        'IXR_IntrospectionServer' => __DIR__ . '/../..' . '/www/libs/IXR_Library.inc.php',
        'IXR_Message' => __DIR__ . '/../..' . '/www/libs/IXR_Library.inc.php',
        'IXR_Request' => __DIR__ . '/../..' . '/www/libs/IXR_Library.inc.php',
        'IXR_Server' => __DIR__ . '/../..' . '/www/libs/IXR_Library.inc.php',
        'IXR_Value' => __DIR__ . '/../..' . '/www/libs/IXR_Library.inc.php',
        'LCPBase' => __DIR__ . '/../..' . '/www/libs/LCPBase.php',
        'League' => __DIR__ . '/../..' . '/www/libs/League.php',
        'Link' => __DIR__ . '/../..' . '/www/libs/link.php',
        'LinkValidator' => __DIR__ . '/../..' . '/www/libs/link_validator.php',
        'Log' => __DIR__ . '/../..' . '/www/libs/log.php',
        'LogAdmin' => __DIR__ . '/../..' . '/www/libs/log_admin.php',
        'Mafia' => __DIR__ . '/../..' . '/www/libs/mafia.php',
        'Media' => __DIR__ . '/../..' . '/www/libs/media.php',
        'MenuOption' => __DIR__ . '/../..' . '/www/libs/html1.php',
        'OAuth2Server' => __DIR__ . '/../..' . '/www/libs/oauth2/oauth2_server.php',
        'ObjectIterator' => __DIR__ . '/../..' . '/www/libs/rgdb.php',
        'Poll' => __DIR__ . '/../..' . '/www/libs/poll/poll.php',
        'PollCollection' => __DIR__ . '/../..' . '/www/libs/poll/poll_collection.php',
        'PollOption' => __DIR__ . '/../..' . '/www/libs/poll/poll_option.php',
        'Post' => __DIR__ . '/../..' . '/www/libs/post.php',
        'Preguntame' => __DIR__ . '/../..' . '/www/libs/preguntame.php',
        'PrivateMessage' => __DIR__ . '/../..' . '/www/libs/private.php',
        'Publisher' => __DIR__ . '/../..' . '/www/libs/pubsubhubbub/publisher.php',
        'QueryResult' => __DIR__ . '/../..' . '/www/libs/rgdb.php',
        'RGDB' => __DIR__ . '/../..' . '/www/libs/rgdb.php',
        'ReCaptchaResponse' => __DIR__ . '/../..' . '/www/libs/recaptchalib.php',
        'Report' => __DIR__ . '/../..' . '/www/libs/report.php',
        'S3' => __DIR__ . '/../..' . '/www/libs/S3.php',
        'S3Exception' => __DIR__ . '/../..' . '/www/libs/S3.php',
        'S3Request' => __DIR__ . '/../..' . '/www/libs/S3.php',
        'SimpleImage' => __DIR__ . '/../..' . '/www/libs/simpleimage.php',
        'SitesMgr' => __DIR__ . '/../..' . '/www/libs/sites.php',
        'SphinxClient' => __DIR__ . '/../..' . '/www/libs/sphinxapi.php',
        'Sponsor' => __DIR__ . '/../..' . '/www/libs/sponsor.php',
        'Storage' => __DIR__ . '/../..' . '/www/libs/oauth2/storage.php',
        'Strike' => __DIR__ . '/../..' . '/www/libs/strike.php',
        'Tabs' => __DIR__ . '/../..' . '/www/libs/tabs.php',
        'Tag' => __DIR__ . '/../..' . '/www/libs/tags.php',
        'Time' => __DIR__ . '/../..' . '/www/libs/time.php',
        'Trackback' => __DIR__ . '/../..' . '/www/libs/trackback.php',
        'Twemojis' => __DIR__ . '/../..' . '/www/libs/twemojis.php',
        'Upload' => __DIR__ . '/../..' . '/www/libs/upload.php',
        'User' => __DIR__ . '/../..' . '/www/libs/user.php',
        'UserAuth' => __DIR__ . '/../..' . '/www/libs/login.php',
        'Vote' => __DIR__ . '/../..' . '/www/libs/votes.php',
        'WebThumb' => __DIR__ . '/../..' . '/www/libs/webimages.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit982e99603abf7e931d3651829d24ceda::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit982e99603abf7e931d3651829d24ceda::$prefixDirsPsr4;
            $loader->prefixesPsr0 = ComposerStaticInit982e99603abf7e931d3651829d24ceda::$prefixesPsr0;
            $loader->classMap = ComposerStaticInit982e99603abf7e931d3651829d24ceda::$classMap;

        }, null, ClassLoader::class);
    }
}
